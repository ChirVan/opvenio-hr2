<?php

namespace App\Modules\learning_management\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AssessmentResultsController extends Controller
{
    /**
     * Display assessment results for evaluation
     */
    public function index()
    {
        // Get all assessment results from ESS database
        $results = DB::connection('ess')
            ->table('assessment_results as ar')
            ->orderBy('ar.completed_at', 'desc')
            ->get();

        // Enrich results with assignment and employee data
        $enrichedResults = $results->map(function ($result) {
            // Get assignment details from learning_management database
            $assignment = DB::connection('learning_management')
                ->table('assessment_assignments as aa')
                ->join('quizzes as q', 'aa.quiz_id', '=', 'q.id')
                ->join('assessment_categories as ac', 'aa.assessment_category_id', '=', 'ac.id')
                ->where('aa.id', $result->assignment_id)
                ->select([
                    'aa.employee_name',
                    'aa.employee_email',
                    'aa.max_attempts',
                    'aa.attempts_used',
                    'q.quiz_title',
                    'ac.category_name'
                ])
                ->first();

            if ($assignment) {
                $result->employee_name = $assignment->employee_name;
                $result->employee_email = $assignment->employee_email;
                $result->quiz_title = $assignment->quiz_title;
                $result->category_name = $assignment->category_name;
                $result->max_attempts = $assignment->max_attempts;
                $result->attempts_used = $assignment->attempts_used;
            } else {
                $result->employee_name = 'Unknown';
                $result->employee_email = 'Unknown';
                $result->quiz_title = 'Unknown Quiz';
                $result->category_name = 'Unknown Category';
                $result->max_attempts = 3;
                $result->attempts_used = 1;
            }

            return $result;
        });

        return view('learning_management.results', ['results' => $enrichedResults]);
    }

    /**
     * Show detailed evaluation page for a specific assessment submission
     */
    public function evaluate($id)
    {
        // Get the assessment result
        $result = DB::connection('ess')
            ->table('assessment_results')
            ->where('id', $id)
            ->first();

        if (!$result) {
            return redirect()->route('assessment.results')->with('error', 'Assessment result not found.');
        }

        // Get assignment details
        $assignment = DB::connection('learning_management')
            ->table('assessment_assignments as aa')
            ->join('quizzes as q', 'aa.quiz_id', '=', 'q.id')
            ->join('assessment_categories as ac', 'aa.assessment_category_id', '=', 'ac.id')
            ->where('aa.id', $result->assignment_id)
            ->select([
                'aa.employee_name',
                'aa.employee_email',
                'aa.max_attempts',
                'aa.attempts_used',
                'q.quiz_title',
                'ac.category_name'
            ])
            ->first();

        // Get all questions and user answers
        $questionsAndAnswers = DB::connection('ess')
            ->table('user_answers as ua')
            ->where('ua.result_id', $id)
            ->get()
            ->map(function ($answer) {
                // Get the question details from learning_management database
                $question = DB::connection('learning_management')
                    ->table('quiz_questions')
                    ->where('id', $answer->question_id)
                    ->first();

                $answer->question_text = $question->question ?? 'Question not found';
                $answer->question_order = $question->question_order ?? 0;
                $answer->points_possible = $question->points ?? 1;

                return $answer;
            })
            ->sortBy('question_order')
            ->values();

        return view('learning_management.evaluate', compact('result', 'assignment', 'questionsAndAnswers'));
    }

    /**
     * Approve/Pass an assessment
     */
    public function approve($id)
    {
        try {
            // Update the assessment result status
            DB::connection('ess')->table('assessment_results')
                ->where('id', $id)
                ->update([
                    'status' => 'passed',
                    'evaluated_by' => Auth::id(),
                    'evaluated_at' => now(),
                    'updated_at' => now()
                ]);

            // Also update the assignment in learning_management database
            $result = DB::connection('ess')->table('assessment_results')->where('id', $id)->first();
            if ($result) {
                DB::connection('learning_management')->table('assessment_assignments')
                    ->where('id', $result->assignment_id)
                    ->update([
                        'status' => 'completed',
                        'score' => 100.00, // Set to passing score
                        'updated_at' => now()
                    ]);
            }

            return redirect()->route('assessment.results')->with('success', 'Assessment approved successfully.');
        } catch (\Exception $e) {
            return redirect()->route('assessment.results')->with('error', 'Failed to approve assessment: ' . $e->getMessage());
        }
    }

    /**
     * Reject/Fail an assessment
     */
    public function reject($id)
    {
        try {
            // Update the assessment result status
            DB::connection('ess')->table('assessment_results')
                ->where('id', $id)
                ->update([
                    'status' => 'failed',
                    'evaluated_by' => Auth::id(),
                    'evaluated_at' => now(),
                    'updated_at' => now()
                ]);

            // Also update the assignment in learning_management database
            $result = DB::connection('ess')->table('assessment_results')->where('id', $id)->first();
            if ($result) {
                DB::connection('learning_management')->table('assessment_assignments')
                    ->where('id', $result->assignment_id)
                    ->update([
                        'status' => 'completed',
                        'score' => 0.00, // Set to failing score
                        'updated_at' => now()
                    ]);
            }

            return redirect()->route('assessment.results')->with('success', 'Assessment rejected successfully.');
        } catch (\Exception $e) {
            return redirect()->route('assessment.results')->with('error', 'Failed to reject assessment: ' . $e->getMessage());
        }
    }

    public function statistics()
    {
        // Add statistics functionality if needed
        $stats = [];
        return view('learning_management.assessment_results.statistics', compact('stats'));
    }
}