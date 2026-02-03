<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AssessmentResultsController extends Controller
{
    /**
     * Display assessment results for HR/Admin review - grouped by employee
     */
    public function index()
    {
        // Get all assessment submissions with employee details
        // Exclude 'retried' status results as they are previous attempts that were given another chance
        $results = DB::connection('ess')
            ->table('assessment_results as ar')
            ->join('user_answers as ua', 'ar.id', '=', 'ua.result_id')
            ->where('ar.status', '!=', 'retried') // Exclude retried attempts
            ->select([
                'ar.id',
                'ar.assignment_id',
                'ar.employee_id',
                'ar.quiz_id',
                'ar.total_questions',
                'ar.attempt_number',
                'ar.score',
                'ar.status',
                'ar.evaluation_status',
                'ar.evaluated_by',
                'ar.evaluated_at',
                'ar.completed_at',
                'ar.evaluation_data',
                DB::raw('COUNT(ua.id) as answered_questions'),
                DB::raw('SUM(CASE WHEN ua.is_correct = 1 THEN 1 ELSE 0 END) as correct_count')
            ])
            ->groupBy([
                'ar.id', 'ar.assignment_id', 'ar.employee_id', 'ar.quiz_id', 
                'ar.total_questions', 'ar.attempt_number', 'ar.score', 
                'ar.status', 'ar.evaluation_status', 'ar.evaluated_by', 'ar.evaluated_at', 'ar.completed_at',
                'ar.evaluation_data'
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
                ->leftJoin('users as u', 'aa.assigned_by', '=', 'u.id')
                ->where('aa.id', $result->assignment_id)
                ->select([
                    'aa.employee_name',
                    'aa.employee_email',
                    'aa.employee_id',
                    'aa.max_attempts',
                    'aa.attempts_used',
                    'aa.assigned_by',
                    'aa.notes',
                    'q.quiz_title',
                    'ac.category_name',
                    'u.name as assigned_by_name'
                ])
                ->first();

            $result->employee_name = $assignment->employee_name ?? 'Unknown';
            $result->employee_email = $assignment->employee_email ?? 'Unknown';
            $result->quiz_title = $assignment->quiz_title ?? 'Unknown Quiz';
            $result->category_name = $assignment->category_name ?? 'Unknown Category';
            $result->max_attempts = $assignment->max_attempts ?? 3;
            $result->attempts_used = $assignment->attempts_used ?? 0;
            
            // Determine assignment type based on the source:
            // - "Self Assessment" = From grant request (employee requested training)
            // - "Skill Gap Requirement" = From gap analysis (HR identified skill gap)
            if ($assignment) {
                $notes = $assignment->notes ?? '';
                
                // Check notes to determine the assignment source
                // Look for the source identifier or keywords
                if (stripos($notes, 'competency gap') !== false || 
                    stripos($notes, 'gap analysis') !== false ||
                    stripos($notes, 'skill gap requirement') !== false) {
                    // Assigned from skill gap analysis
                    $result->assignment_type = 'Skill Gap Requirement';
                    $result->assigned_by_name = $assignment->assigned_by_name ?? 'HR Admin';
                } else {
                    // Assigned from grant request or self-assessment (employee-initiated)
                    $result->assignment_type = 'Self Assessment';
                    $result->assigned_by_name = 'Self';
                }
            } else {
                $result->assignment_type = 'Unknown';
                $result->assigned_by_name = 'Unknown';
            }

            return $result;
        });

        // Group by employee AND assignment type - keep assessments of same assignment type together
        // This separates Self Assessment from Skill Gap Requirement assignments
        $groupedByEmployee = $enrichedResults->groupBy(function ($item) {
            return $item->employee_id . '_' . $item->employee_email . '_' . $item->assignment_type;
        })->map(function ($employeeResults) {
            $firstResult = $employeeResults->first();
            
            // Check evaluation statuses across all assessments for this group
            $hasPassed = $employeeResults->contains('evaluation_status', 'passed');
            $hasFailed = $employeeResults->contains('evaluation_status', 'failed');
            $hasPending = $employeeResults->contains('evaluation_status', 'pending') || 
                          $employeeResults->whereNull('evaluation_status')->count() > 0;
            
            // Determine overall group type based on all assessments in this assignment type
            if ($hasPending) {
                $groupType = 'Pending Evaluation';
                $isPassedGroup = false;
                $isFailedGroup = false;
            } elseif ($hasFailed) {
                $groupType = 'Has Rejected';
                $isPassedGroup = false;
                $isFailedGroup = true;
            } else {
                $groupType = 'All Passed';
                $isPassedGroup = true;
                $isFailedGroup = false;
            }
            
            // Count assessments by status
            $statusCounts = $employeeResults->groupBy('status')->map->count();
            $totalAssessments = $employeeResults->count();
            $completedAssessments = $employeeResults->where('status', '!=', 'in_progress')->count();
            
            // Get all unique categories
            $categories = $employeeResults->pluck('category_name')->unique()->implode(', ');
            
            // Get latest completion date
            $latestDate = $employeeResults->max('completed_at');
            
            // Get overall status (if any pending, show pending; if all approved, show approved)
            $overallStatus = 'completed';
            if ($employeeResults->contains('status', 'in_progress')) {
                $overallStatus = 'in_progress';
            } elseif ($employeeResults->contains('status', 'completed')) {
                $overallStatus = 'pending';
            } elseif ($employeeResults->every(function($result) { return $result->status === 'passed'; })) {
                $overallStatus = 'approved';
            } elseif ($employeeResults->contains('status', 'failed')) {
                $overallStatus = 'mixed'; // Some passed, some failed
            }
            
            return (object) [
                'employee_id' => $firstResult->employee_id,
                'employee_name' => $firstResult->employee_name,
                'employee_email' => $firstResult->employee_email,
                'categories' => $categories,
                'total_assessments' => $totalAssessments,
                'completed_assessments' => $completedAssessments,
                'latest_date' => $latestDate,
                'overall_status' => $overallStatus,
                'status_counts' => $statusCounts,
                'assessment_results' => $employeeResults, // Keep all individual results for evaluation
                'max_attempts' => $firstResult->max_attempts,
                'attempts_used' => $employeeResults->sum('attempt_number'),
                'is_passed_group' => $isPassedGroup,
                'is_failed_group' => $isFailedGroup,
                'group_type' => $groupType,
                'assignment_type' => $firstResult->assignment_type ?? 'Unknown',
                'assigned_by_name' => $firstResult->assigned_by_name ?? 'Unknown'
            ];
        })->values();

        // Paginate the grouped results
        $page = request()->get('page', 1);
        $perPage = 3; // Items per page
        $total = count($groupedByEmployee);
        $results = new \Illuminate\Pagination\LengthAwarePaginator(
            $groupedByEmployee->forPage($page, $perPage)->values(),
            $total,
            $perPage,
            $page,
            [
                'path' => route('assessment.results'),
                'query' => request()->query(),
            ]
        );

        return view('learning_management.results', ['results' => $results]);
    }

    /**
     * Show detailed evaluation page for an employee's assessments (Read-only summary)
     */
    public function evaluate(Request $request, $employeeId)
    {
        // Check if specific result IDs were passed (for filtered group evaluation)
        $specificResultIds = null;
        if ($request->has('result_ids') && !empty($request->result_ids)) {
            $specificResultIds = explode(',', $request->result_ids);
        }

        // Get all assessment results for this employee that have valid assignment linkages
        $query = DB::connection('ess')
            ->table('assessment_results as ar')
            ->join('user_answers as ua', 'ar.id', '=', 'ua.result_id')
            ->where('ar.employee_id', $employeeId)
            ->where('ar.status', '!=', 'in_progress') // Only include completed assessments
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('learning_management.assessment_assignments as aa')
                      ->whereRaw('aa.id = ar.assignment_id');
            });

        // If specific result IDs are provided, filter by them
        if ($specificResultIds) {
            $query->whereIn('ar.id', $specificResultIds);
        }

        $results = $query->select([
                'ar.id',
                'ar.assignment_id',
                'ar.employee_id',
                'ar.quiz_id',
                'ar.total_questions',
                'ar.attempt_number',
                'ar.score',
                'ar.status',
                'ar.evaluation_status',
                'ar.evaluated_by',
                'ar.evaluated_at',
                'ar.completed_at'
            ])
            ->groupBy([
                'ar.id', 'ar.assignment_id', 'ar.employee_id', 'ar.quiz_id', 
                'ar.total_questions', 'ar.attempt_number', 'ar.score', 
                'ar.status', 'ar.evaluation_status', 'ar.evaluated_by', 'ar.evaluated_at', 'ar.completed_at'
            ])
            ->orderBy('ar.completed_at', 'desc')
            ->get();

        if ($results->isEmpty()) {
            return redirect()->route('assessment.results')->with('error', 'No assessment results found for this employee.');
        }

        // Get employee details from the first result
        $firstResult = $results->first();
        $assignment = DB::connection('learning_management')
            ->table('assessment_assignments as aa')
            ->join('quizzes as q', 'aa.quiz_id', '=', 'q.id')
            ->join('assessment_categories as ac', 'aa.assessment_category_id', '=', 'ac.id')
            ->where('aa.id', $firstResult->assignment_id)
            ->select([
                'aa.employee_name',
                'aa.employee_email',
                'aa.max_attempts',
                'aa.attempts_used'
            ])
            ->first();

        // Get read-only assessment summary data for "Evaluate All"
        $assessmentData = [];
        foreach ($results as $result) {
            // Get quiz details
            $quizAssignment = DB::connection('learning_management')
                ->table('assessment_assignments as aa')
                ->join('quizzes as q', 'aa.quiz_id', '=', 'q.id')
                ->join('assessment_categories as ac', 'aa.assessment_category_id', '=', 'ac.id')
                ->where('aa.id', $result->assignment_id)
                ->select([
                    'q.quiz_title',
                    'ac.category_name',
                    'q.id as quiz_id'
                ])
                ->first();

            // Get summary statistics for this assessment
            $totalQuestions = DB::connection('ess')
                ->table('user_answers')
                ->where('result_id', $result->id)
                ->count();

            $correctAnswers = DB::connection('ess')
                ->table('user_answers')
                ->where('result_id', $result->id)
                ->where('is_correct', 1)
                ->count();

            $manuallyGraded = DB::connection('ess')
                ->table('user_answers')
                ->where('result_id', $result->id)
                ->where('manually_graded', 1)
                ->count();

            // Calculate final score percentage
            $scorePercentage = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 1) : 0;

            $assessmentData[] = (object) [
                'result_id' => $result->id,
                'quiz_title' => $quizAssignment->quiz_title ?? 'Unknown Quiz',
                'category_name' => $quizAssignment->category_name ?? 'Unknown Category',
                'completed_at' => $result->completed_at,
                'status' => $result->status,
                'attempt_number' => $result->attempt_number,
                'total_questions' => $totalQuestions,
                'correct_answers' => $correctAnswers,
                'manually_graded' => $manuallyGraded,
                'score_percentage' => $scorePercentage,
                'score' => $result->score ?? $scorePercentage
            ];
        }

        // Create employee data object for the view
        $totalAssessments = count($assessmentData);
        $completedAssessments = collect($assessmentData)->where('status', '!=', 'in_progress')->count();
        $overallStatus = $completedAssessments == $totalAssessments ? 'completed' : 'partial';

        $employeeData = (object) [
            'employee_id' => $employeeId,
            'employee_name' => $assignment->employee_name ?? 'Unknown Employee',
            'employee_email' => $assignment->employee_email ?? 'Unknown Email',
            'total_assessments' => $totalAssessments,
            'completed_assessments' => $completedAssessments,
            'overall_status' => $overallStatus
        ];

        return view('learning_management.evaluate', compact('assessmentData', 'assignment', 'employeeId', 'employeeData'))
            ->with('isReadOnlyMode', true)
            ->with('isEvaluateAll', true);
    }

    /**
     * Evaluate a single assessment result (not all assessments for an employee)
     */
    public function evaluateSingle($resultId)
    {
        // Get the specific assessment result
        $result = DB::connection('ess')
            ->table('assessment_results')
            ->where('id', $resultId)
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
                'ac.category_name',
                'q.id as quiz_id'
            ])
            ->first();

        if (!$assignment) {
            return redirect()->route('assessment.results')->with('error', 'Assessment assignment not found.');
        }

        // Get questions and answers for this specific result
        $questionsAndAnswers = DB::connection('ess')
            ->table('user_answers as ua')
            ->where('ua.result_id', $result->id)
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
                $answer->question_type = $question->question_type ?? 'multiple_choice';
                
                // Get correct answer from the question
                if ($question) {
                    $answer->correct_answer = $question->correct_answer ?? 'Not specified';
                }

                return $answer;
            })
            ->sortBy('question_order')
            ->values();

        // Create single assessment data structure
        $assessmentData = [(object) [
            'result_id' => $result->id,
            'quiz_title' => $assignment->quiz_title ?? 'Unknown Quiz',
            'category_name' => $assignment->category_name ?? 'Unknown Category',
            'completed_at' => $result->completed_at,
            'status' => $result->status,
            'attempt_number' => $result->attempt_number,
            'total_questions' => $result->total_questions,
            'questions_and_answers' => $questionsAndAnswers,
            'score' => $result->score ?? 0
        ]];

        // Create employee data object for the view
        $employeeData = (object) [
            'employee_id' => $result->employee_id,
            'employee_name' => $assignment->employee_name ?? 'Unknown Employee',
            'employee_email' => $assignment->employee_email ?? 'Unknown Email',
            'total_assessments' => 1, // Only this single assessment
            'completed_assessments' => 1,
            'overall_status' => 'single_assessment'
        ];

        return view('learning_management.evaluate', compact('assessmentData', 'assignment', 'employeeData'))
            ->with('isSingleAssessment', true)
            ->with('resultId', $resultId);
    }

    /**
     * Show step 2 of evaluation - hands-on assessment
     */
    public function evaluateStep2(Request $request, $id)
    {
        // Get the assessment result
        $result = DB::connection('ess')
            ->table('assessment_results')
            ->where('id', $id)
            ->first();

        if (!$result) {
            return redirect()->route('assessment.results')->with('error', 'Assessment result not found.');
        }

        // Get the result IDs being evaluated (for group-specific evaluation)
        $resultIds = null;
        if ($request->has('result_ids') && !empty($request->result_ids)) {
            $resultIds = $request->result_ids;
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

        return view('learning_management.evaluate_step2', compact('result', 'assignment', 'questionsAndAnswers', 'resultIds'));
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
            // Get the employee_id from the current assessment result
            $currentResult = DB::connection('ess')->table('assessment_results')->where('id', $id)->first();
            if (!$currentResult) {
                throw new \Exception('Assessment result not found.');
            }
            
            $employeeId = $currentResult->employee_id;
            
            // Check if specific result IDs were passed (for group-specific evaluation)
            $specificResultIds = null;
            if ($request->has('result_ids') && !empty($request->result_ids)) {
                $specificResultIds = explode(',', $request->result_ids);
                \Log::info('Step 2 Evaluation - Using specific result IDs', [
                    'result_ids_raw' => $request->result_ids,
                    'result_ids_parsed' => $specificResultIds
                ]);
            } else {
                \Log::warning('Step 2 Evaluation - NO result_ids provided, will update ALL employee results!', [
                    'employee_id' => $employeeId,
                    'request_all' => $request->all()
                ]);
            }
            
            // Build the query to update the results
            // For Step 2 (hands-on evaluation), we update ALL results in the group
            // including those already evaluated in Step 1
            $updateQuery = DB::connection('ess')->table('assessment_results')
                ->where('employee_id', $employeeId)
                ->where('status', '!=', 'in_progress') // Only completed assessments
                ->where('status', '!=', 'retried'); // Exclude retried attempts
            
            // If specific result IDs are provided, only update those
            if ($specificResultIds) {
                $updateQuery->whereIn('id', $specificResultIds);
            }
            
            // Update evaluation_data for all results (Step 2 completion)
            // Keep existing evaluation_status unless decision overrides it
            $updateQuery->update([
                    'evaluation_data' => json_encode($evaluationData),
                    'evaluated_by' => Auth::id(),
                    'evaluated_at' => now(),
                    'updated_at' => now()
                ]);

            // Also update the corresponding assignments in learning_management database
            // Get only the results that were just updated
            $resultsQuery = DB::connection('ess')->table('assessment_results')
                ->where('employee_id', $employeeId)
                ->where('evaluation_status', $request->decision) // Only get the ones we just updated
                ->where('evaluated_at', '>=', now()->subMinutes(1)); // Recently updated
            
            // If specific result IDs are provided, filter by them for accuracy
            if ($specificResultIds) {
                $resultsQuery->whereIn('id', $specificResultIds);
            }
            
            $allResults = $resultsQuery->get();
                
            foreach ($allResults as $result) {
                $finalScore = $request->decision === 'passed' ? $averageScore * 20 : 0; // Convert 5-point scale to 100-point scale
                
                DB::connection('learning_management')->table('assessment_assignments')
                    ->where('id', $result->assignment_id)
                    ->update([
                        'status' => 'completed',
                        'score' => $finalScore,
                        'updated_at' => now()
                    ]);
            }

            // If decision is passed, update any related skill_gap_assignments to 'completed'
            if ($request->decision === 'passed') {
                // Get all competency keys that should be marked as completed
                // Map competency ratings to their keys
                $competencyKeys = [
                    'competency_1' => 'assignment_skills',
                    'competency_2' => 'job_knowledge',
                    'competency_3' => 'planning_organizing',
                    'competency_4' => 'accountability',
                    'competency_5' => 'efficiency_improvement'
                ];
                
                // Update all pending skill_gap_assignments for this employee to completed
                DB::connection('competency_management')
                    ->table('skill_gap_assignments')
                    ->where('employee_id', $employeeId)
                    ->whereIn('status', ['pending', 'in_progress'])
                    ->update([
                        'status' => 'completed',
                        'completed_at' => now(),
                        'updated_at' => now()
                    ]);
                    
                \Log::info('Skill gap assignments marked as completed', [
                    'employee_id' => $employeeId,
                    'decision' => $request->decision
                ]);
            }

            $assessmentCount = count($allResults);
            $message = $request->decision === 'passed' 
                ? "All {$assessmentCount} assessments approved successfully with hands-on evaluation completed." 
                : "All {$assessmentCount} assessments rejected with detailed evaluation feedback provided.";

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
            ->join('hr2_opvenio_hr2.users as u', 'ar.employee_id', '=', 'u.employee_id')
            ->leftJoin('hr2_learning_management.assessment_assignments as aa', 'ar.assignment_id', '=', 'aa.id')
            ->leftJoin('hr2_learning_management.quizzes as q', 'aa.quiz_id', '=', 'q.id')
            ->leftJoin('hr2_learning_management.assessment_categories as ac', 'aa.assessment_category_id', '=', 'ac.id')
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

    /**
     * Update manual scoring for questions
     */
    public function updateQuestionScoring(Request $request)
    {
        try {
            $resultIds = $request->input('result_ids', []);
            $questionScores = $request->input('question_scores', []);
            $passingThreshold = 70; // 70% to pass
            
            // Process question scores
            foreach ($questionScores as $answerId => $scoreData) {
                $userAnswer = DB::connection('ess')
                    ->table('user_answers')
                    ->where('id', $answerId)
                    ->first();
                
                if (!$userAnswer) continue;
                
                $pointsPossible = $userAnswer->points_possible ?? 1;
                $isCorrect = isset($scoreData['is_correct']) && $scoreData['is_correct'] ? 1 : 0;
                $manualScore = $isCorrect ? $pointsPossible : 0;
                
                DB::connection('ess')
                    ->table('user_answers')
                    ->where('id', $answerId)
                    ->update([
                        'is_correct' => $isCorrect,
                        'manual_score' => $manualScore,
                        'evaluator_comments' => $scoreData['comments'] ?? null,
                        'manually_graded' => 1,
                        'graded_by' => Auth::id(),
                        'graded_at' => now(),
                        'updated_at' => now()
                    ]);
            }

            $passedCount = 0;
            $failedCount = 0;

            // Process each assessment result
            foreach ($resultIds as $resultId) {
                // Recalculate score
                $score = $this->recalculateAssessmentScore($resultId);
                
                // Auto-determine pass/fail based on threshold
                $status = $score >= $passingThreshold ? 'passed' : 'failed';
                $evaluationStatus = $score >= $passingThreshold ? 'passed' : 'failed';
                
                if ($status === 'passed') {
                    $passedCount++;
                } else {
                    $failedCount++;
                }
                
                // Update result status
                DB::connection('ess')
                    ->table('assessment_results')
                    ->where('id', $resultId)
                    ->update([
                        'status' => $status,
                        'evaluation_status' => $evaluationStatus,
                        'score' => round($score, 2),
                        'evaluated_by' => Auth::id(),
                        'evaluated_at' => now(),
                        'updated_at' => now()
                    ]);
                
                // Update the assignment in learning_management database
                $result = DB::connection('ess')->table('assessment_results')->where('id', $resultId)->first();
                if ($result) {
                    DB::connection('learning_management')->table('assessment_assignments')
                        ->where('id', $result->assignment_id)
                        ->update([
                            'status' => 'completed',
                            'score' => round($score, 2),
                            'updated_at' => now()
                        ]);
                }
            }

            // Build response message
            $totalCount = $passedCount + $failedCount;
            if ($totalCount === 1) {
                $score = $this->recalculateAssessmentScore($resultIds[0]);
                $message = $score >= $passingThreshold 
                    ? "Assessment PASSED with score of {$score}%." 
                    : "Assessment FAILED with score of {$score}%. (Passing threshold: {$passingThreshold}%)";
            } else {
                $message = "Evaluated {$totalCount} assessments: {$passedCount} passed, {$failedCount} failed.";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'passed_count' => $passedCount,
                'failed_count' => $failedCount
            ]);

        } catch (\Exception $e) {
            \Log::error('Assessment scoring update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update scoring: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Recalculate assessment score based on manual grading
     */
    private function recalculateAssessmentScore($resultId)
    {
        // Get all answers for this result
        $answers = DB::connection('ess')
            ->table('user_answers')
            ->where('result_id', $resultId)
            ->get();

        $totalPoints = 0;
        $earnedPoints = 0;

        foreach ($answers as $answer) {
            $questionPoints = $answer->points_possible ?? 1;
            $totalPoints += $questionPoints;
            
            if ($answer->manually_graded) {
                // Use manual score if manually graded
                $earnedPoints += $answer->manual_score ?? 0;
            } else {
                // Use automatic scoring
                $earnedPoints += $answer->is_correct ? $questionPoints : 0;
            }
        }

        // Calculate percentage score
        $score = $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0;

        // Update the assessment result
        DB::connection('ess')
            ->table('assessment_results')
            ->where('id', $resultId)
            ->update([
                'score' => round($score, 2),
                'updated_at' => now()
            ]);

        return $score;
    }

    /**
     * Handle employee-level Step 2 evaluation navigation
     * This redirects to the first available assessment for Step 2 evaluation
     */
    public function employeeStep2Evaluation($employeeId)
    {
        // Get the first completed assessment result for this employee
        $result = DB::connection('ess')
            ->table('assessment_results')
            ->where('employee_id', $employeeId)
            ->where('status', 'completed')
            ->orderBy('completed_at', 'desc')
            ->first();

        if (!$result) {
            return redirect()->route('assessment.results')->with('error', 'No completed assessments found for this employee.');
        }

        // Redirect to the specific assessment Step 2 evaluation
        return redirect()->route('assessment.results.evaluate.step2', $result->id);
    }
}