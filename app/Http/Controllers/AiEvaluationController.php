<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\AIService;
use Illuminate\Support\Facades\Log;

class AiEvaluationController extends Controller
{
    /**
     * Build payload for the given assessment result and call AI to evaluate answers.
     * Returns JSON (decoded AI response).
     */
    public function evaluateByAi(Request $request, $resultId, AIService $ai)
    {
        // 1) Load the assessment result (ess connection)
        $result = DB::connection('ess')->table('assessment_results')->where('id', $resultId)->first();
        if (!$result) {
            return response()->json(['success' => false, 'message' => 'Assessment result not found.'], 404);
        }

        // 2) Load the assignment/quiz info (learning_management connection)
        $assignment = DB::connection('learning_management')
            ->table('assessment_assignments as aa')
            ->join('quizzes as q', 'aa.quiz_id', '=', 'q.id')
            ->join('assessment_categories as ac', 'aa.assessment_category_id', '=', 'ac.id')
            ->where('aa.id', $result->assignment_id)
            ->select([
                'aa.employee_name',
                'aa.employee_email',
                'q.quiz_title',
                'ac.category_name',
                'q.id as quiz_id'
            ])
            ->first();

        // 3) Load the user's answers and question meta
        $questionsRaw = DB::connection('ess')
            ->table('user_answers as ua')
            ->where('ua.result_id', $resultId)
            ->get()
            ->map(function ($answer, $index) {
                $question = DB::connection('learning_management')
                    ->table('quiz_questions')
                    ->where('id', $answer->question_id)
                    ->first();

                return [
                    'question_number'    => $index + 1,
                    'question_id'        => $answer->question_id,
                    'question_text'      => $question->question ?? 'Question not found',
                    'employee_answer'    => isset($answer->user_answer) ? trim($answer->user_answer) : null,
                    'correct_answer'     => $question->correct_answer ?? ($answer->correct_answer ?? null),
                    'points_possible'    => $answer->points_possible ?? 1,
                    'user_answer_row_id' => $answer->id,
                ];
            })->values();

        // Quick sanity: if no questions, return early
        if ($questionsRaw->count() === 0) {
            return response()->json(['success' => false, 'message' => 'No user answers found for this result.'], 422);
        }

        // 4) Build the payload for AI
        $payload = [
            'assessment' => [
                'result_id'       => $result->id,
                'quiz_title'      => $assignment->quiz_title ?? null,
                'category_name'   => $assignment->category_name ?? null,
                'completed_at'    => $result->completed_at ?? null,
                'attempt_number'  => $result->attempt_number ?? null,
                'total_questions' => $result->total_questions ?? null,
                'raw_score'       => $result->score ?? null,
            ],
            'employee' => [
                'employee_id'    => $result->employee_id ?? null,
                'employee_name'  => $assignment->employee_name ?? null,
                'employee_email' => $assignment->employee_email ?? null,
            ],
            'questions' => $questionsRaw->toArray(),
            'config' => [
                'grading_style' => 'conceptual',
                'explain_only_when_incorrect' => true,
            ],
        ];

        Log::debug('AI evaluate payload', ['result_id' => $result->id, 'payload' => $payload]);

        // 5) Template existence check and call AI
        $template = resource_path('prompts/auto_grade_template_conceptual.txt');

        if (! file_exists($template)) {
            Log::error("AI template not found: {$template}");
            return response()->json([
                'success' => false,
                'message' => 'AI prompt template not found on server. Expected: resources/prompts/auto_grade_template_conceptual.txt'
            ], 500);
        }

        try {
            $aiResult = $ai->recommendFromPayload($payload, $template);

            // aiResult should already be an array (AIService decodes). Return it.
            return response()->json(['success' => true, 'ai' => $aiResult], 200);

        } catch (\Throwable $e) {
            Log::error('AI evaluation failed for result ' . $resultId . ': ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return response()->json(['success' => false, 'message' => 'AI error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Approve and persist AI grading results.
     * Expects the full AI JSON in request body (ai_result).
     */
    public function approveByAi(Request $request, $resultId)
    {
        $aiResult = $request->input('ai_result');
        $approverId = optional($request->user())->id;

        if (! $aiResult || ! is_array($aiResult)) {
            return response()->json(['success' => false, 'message' => 'Missing or invalid ai_result payload'], 400);
        }

        $payload = [
            'result_id'   => $resultId,
            'reviewer_id' => $approverId,
            'ai_review'   => json_encode($aiResult, JSON_UNESCAPED_UNICODE),
            'created_at'  => now(),
        ];

        try {
            DB::connection('ess')->table('assessment_ai_reviews')->insert($payload);
        } catch (\Throwable $e) {
            // fallback: try to update assessment_results.ai_review if it exists
            try {
                DB::connection('ess')->table('assessment_results')->where('id', $resultId)
                    ->update(['ai_review' => json_encode($aiResult, JSON_UNESCAPED_UNICODE)]);
            } catch (\Throwable $e2) {
                Log::error('Failed to save AI review: ' . $e->getMessage() . ' ; fallback failed: ' . $e2->getMessage());
                return response()->json(['success' => false, 'message' => 'Failed to persist AI review'], 500);
            }
        }

        // optional: update the result's score if AI produced overall_score
        if (isset($aiResult['overall_score'])) {
            try {
                DB::connection('ess')->table('assessment_results')->where('id', $resultId)
                    ->update(['score' => $aiResult['overall_score']]);
            } catch (\Throwable $e) {
                Log::warning('Failed to update assessment_results.score: ' . $e->getMessage());
            }
        }

        return response()->json(['success' => true, 'message' => 'AI review saved']);
    }
}
