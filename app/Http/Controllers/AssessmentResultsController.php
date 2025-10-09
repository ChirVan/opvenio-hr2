<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AssessmentResultsController extends Controller
{
    /**
     * Display assessment results for HR/Admin review
     */
    public function index()
    {
        // Get all assessment submissions with employee details
        $results = DB::connection('ess')
            ->table('assessment_results as ar')
            ->join('user_answers as ua', 'ar.id', '=', 'ua.result_id')
            ->select([
                'ar.id',
                'ar.assignment_id',
                'ar.employee_id',
                'ar.quiz_id',
                'ar.total_questions',
                'ar.attempt_number',
                'ar.score',
                'ar.status',
                'ar.completed_at',
                DB::raw('COUNT(ua.id) as answered_questions'),
                DB::raw('SUM(CASE WHEN ua.is_correct = 1 THEN 1 ELSE 0 END) as correct_count')
            ])
            ->groupBy([
                'ar.id', 'ar.assignment_id', 'ar.employee_id', 'ar.quiz_id', 
                'ar.total_questions', 'ar.attempt_number', 'ar.score', 
                'ar.status', 'ar.completed_at'
            ])
            ->orderBy('ar.completed_at', 'desc')
            ->get();

        // Get employee and quiz details for each result
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

            $result->employee_name = $assignment->employee_name ?? 'Unknown';
            $result->employee_email = $assignment->employee_email ?? 'Unknown';
            $result->quiz_title = $assignment->quiz_title ?? 'Unknown Quiz';
            $result->category_name = $assignment->category_name ?? 'Unknown Category';
            $result->max_attempts = $assignment->max_attempts ?? 3;
            $result->attempts_used = $assignment->attempts_used ?? 0;

            return $result;
        });

        // Group by employee and quiz to show attempt counts
        $groupedResults = $enrichedResults->groupBy(function ($item) {
            return $item->employee_id . '_' . $item->quiz_id;
        })->map(function ($group) {
            $latest = $group->sortByDesc('completed_at')->first();
            $latest->total_attempts = $group->count();
            return $latest;
        })->values();

        return view('learning_management.results', ['results' => $groupedResults]);
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
     * Show step 2 of evaluation - hands-on assessment
     */
    public function evaluateStep2($id)
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

        // Get questions and answers for reference
        $questionsAndAnswers = DB::connection('ess')
            ->table('user_answers')
            ->where('result_id', $id)
            ->get();

        return view('learning_management.evaluate_step2', compact('result', 'assignment', 'questionsAndAnswers'));
    }

    /**
     * Submit final evaluation with hands-on assessment
     */
    public function submitEvaluation(Request $request, $id)
    {
        try {
            $request->validate([
                'competency_1' => 'required|in:exceptional,highly_effective,proficient,inconsistent,unsatisfactory',
                'competency_2' => 'required|in:exceptional,highly_effective,proficient,inconsistent,unsatisfactory',
                'competency_3' => 'required|in:exceptional,highly_effective,proficient,inconsistent,unsatisfactory',
                'competency_4' => 'required|in:exceptional,highly_effective,proficient,inconsistent,unsatisfactory',
                'competency_5' => 'required|in:exceptional,highly_effective,proficient,inconsistent,unsatisfactory',
                'decision' => 'required|in:passed,failed',
                'strengths' => 'nullable|string|max:1000',
                'areas_for_improvement' => 'nullable|string|max:1000',
                'recommendations' => 'nullable|string|max:2000',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        }

        // Calculate performance score based on competency ratings
            $competencyScores = [
                $request->competency_1,
                $request->competency_2,
                $request->competency_3,
                $request->competency_4,
                $request->competency_5
            ];

            // Convert ratings to numeric values for scoring
            $scoreMapping = [
                'exceptional' => 5,
                'highly_effective' => 4,
                'proficient' => 3,
                'inconsistent' => 2,
                'unsatisfactory' => 1
            ];

            $numericScores = array_map(function($rating) use ($scoreMapping) {
                return $scoreMapping[$rating];
            }, $competencyScores);

            $averageScore = array_sum($numericScores) / count($numericScores);

            // Prepare evaluation data
            $evaluationData = [
                'competency_1' => $request->competency_1,
                'competency_2' => $request->competency_2,
                'competency_3' => $request->competency_3,
                'competency_4' => $request->competency_4,
                'competency_5' => $request->competency_5,
                'average_score' => round($averageScore, 2),
                'strengths' => $request->strengths,
                'areas_for_improvement' => $request->areas_for_improvement,
                'recommendations' => $request->recommendations,
                'evaluation_date' => now()->format('Y-m-d H:i:s'),
            ];

        try {
            // Update assessment result with evaluation and decision
            DB::connection('ess')->table('assessment_results')
                ->where('id', $id)
                ->update([
                    'status' => $request->decision,
                    'evaluation_data' => json_encode($evaluationData),
                    'evaluated_by' => Auth::id(),
                    'evaluated_at' => now(),
                    'updated_at' => now()
                ]);

            // Also update the assignment in learning_management database
            $result = DB::connection('ess')->table('assessment_results')->where('id', $id)->first();
            if ($result) {
                $finalScore = $request->decision === 'passed' ? $averageScore * 20 : 0; // Convert 5-point scale to 100-point scale
                
                DB::connection('learning_management')->table('assessment_assignments')
                    ->where('id', $result->assignment_id)
                    ->update([
                        'status' => 'completed',
                        'score' => $finalScore,
                        'updated_at' => now()
                    ]);
            }

            $message = $request->decision === 'passed' 
                ? 'Assessment approved successfully with hands-on evaluation completed.' 
                : 'Assessment rejected with detailed evaluation feedback provided.';

            return redirect()->route('assessment.results')->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('Assessment evaluation submission failed', [
                'id' => $id,
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);
            
            return redirect()->back()->with('error', 'Failed to submit evaluation: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Get all approved employees for reporting or other purposes
     */
    public function getApprovedEmployees()
    {
        $approvedEmployees = DB::connection('ess')
            ->table('assessment_results as ar')
            ->join('users as u', 'ar.employee_id', '=', 'u.employee_id')
            ->leftJoin('learning_management.assessment_assignments as aa', 'ar.assignment_id', '=', 'aa.id')
            ->leftJoin('learning_management.quizzes as q', 'aa.quiz_id', '=', 'q.id')
            ->leftJoin('learning_management.assessment_categories as ac', 'aa.assessment_category_id', '=', 'ac.id')
            ->where('ar.status', 'passed') // Only approved employees
            ->select([
                'ar.id',
                'ar.employee_id',
                'u.name as employee_name',
                'u.email as employee_email',
                'q.quiz_title',
                'ac.category_name',
                'ar.evaluation_data', // Contains all competency ratings
                'ar.evaluated_by',
                'ar.evaluated_at',
                'ar.completed_at',
                'ar.score'
            ])
            ->orderBy('ar.evaluated_at', 'desc')
            ->get();

        return $approvedEmployees;
    }

    /**
     * Get approved employees with decoded evaluation data
     */
    public function getApprovedEmployeesWithDetails()
    {
        $approvedEmployees = $this->getApprovedEmployees();
        
        return $approvedEmployees->map(function ($employee) {
            // Decode the JSON evaluation data
            $evaluationData = json_decode($employee->evaluation_data, true);
            
            return [
                'id' => $employee->id,
                'employee_id' => $employee->employee_id,
                'employee_name' => $employee->employee_name,
                'employee_email' => $employee->employee_email,
                'quiz_title' => $employee->quiz_title,
                'category_name' => $employee->category_name,
                'evaluated_at' => $employee->evaluated_at,
                'competency_ratings' => [
                    'assignment_skills' => $evaluationData['competency_1'] ?? null,
                    'job_knowledge' => $evaluationData['competency_2'] ?? null,
                    'planning_organizing' => $evaluationData['competency_3'] ?? null,
                    'accountability' => $evaluationData['competency_4'] ?? null,
                    'efficiency_improvement' => $evaluationData['competency_5'] ?? null,
                ],
                'evaluation_comments' => [
                    'strengths' => $evaluationData['strengths'] ?? null,
                    'areas_for_improvement' => $evaluationData['areas_for_improvement'] ?? null,
                    'recommendations' => $evaluationData['recommendations'] ?? null,
                ],
                'average_score' => $evaluationData['average_score'] ?? null,
            ];
        });
    }

    /**
     * Show approved employees report page
     */
    public function approvedEmployeesReport()
    {
        $approvedEmployees = $this->getApprovedEmployeesWithDetails();
        return view('learning_management.approved_employees', compact('approvedEmployees'));
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
}