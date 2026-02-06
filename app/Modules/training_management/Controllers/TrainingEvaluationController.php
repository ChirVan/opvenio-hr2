<?php

namespace App\Modules\training_management\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\AIService;
use App\Services\EmployeeApiService;

class TrainingEvaluationController extends Controller
{
    protected $employeeApiService;

    public function __construct(EmployeeApiService $employeeApiService)
    {
        $this->employeeApiService = $employeeApiService;
    }

    /**
     * Display the training evaluation index.
     */
    public function index(Request $request)
    {
        $resultsQuery = DB::connection('ess')
            ->table('assessment_results')
            ->whereIn('status', ['completed', 'passed'])
            ->where('status', '!=', 'retried')
            ->where('evaluation_status', 'passed')
            ->orderBy('completed_at', 'desc');

        if ($request->has('filter') && $request->filter) {
            if ($request->filter == 'pending') {
                $resultsQuery->where(function($q) {
                    $q->whereNull('evaluation_data')
                      ->orWhere('evaluation_data', '');
                });
            } elseif ($request->filter == 'evaluated') {
                $resultsQuery->whereNotNull('evaluation_data')
                             ->where('evaluation_data', '!=', '');
            }
        }

        $assessmentResults = $resultsQuery->get();

        $enrichedResults = $assessmentResults->map(function ($result) {
            $assignment = DB::connection('learning_management')
                ->table('assessment_assignments as aa')
                ->join('quizzes as q', 'aa.quiz_id', '=', 'q.id')
                ->join('assessment_categories as ac', 'aa.assessment_category_id', '=', 'ac.id')
                ->where('aa.id', $result->assignment_id)
                ->select([
                    'aa.employee_name',
                    'aa.quiz_id',
                    'q.quiz_title',
                    'ac.category_name',
                    'aa.max_attempts',
                ])
                ->first();

            if ($assignment) {
                $result->employee_name = $assignment->employee_name;
                $result->quiz_id = $assignment->quiz_id;
                $result->quiz_title = $assignment->quiz_title;
                $result->category_name = $assignment->category_name;
                $result->max_attempts = $assignment->max_attempts;
            }

            return $result;
        })->filter(function ($result) {
            return isset($result->employee_id);
        });

        if ($request->has('search') && $request->search) {
            $searchTerm = strtolower($request->search);
            $enrichedResults = $enrichedResults->filter(function ($result) use ($searchTerm) {
                return str_contains(strtolower($result->employee_name ?? ''), $searchTerm);
            });
        }

        $groupedResults = $enrichedResults->groupBy('employee_id')->map(function($employeeResults) {
            $first = $employeeResults->first();
            
            $evaluatedCount = $employeeResults->filter(function($r) {
                return !empty($r->evaluation_data) && $r->evaluation_data !== '' && $r->evaluation_data !== null;
            })->count();
            
            $allEvaluated = $evaluatedCount === $employeeResults->count();
            
            return (object)[
                'employee_id' => $first->employee_id,
                'employee_name' => $first->employee_name,
                'assessments' => $employeeResults,
                'total_assessments' => $employeeResults->count(),
                'evaluated_count' => $evaluatedCount,
                'pending_count' => $employeeResults->count() - $evaluatedCount,
                'all_evaluated' => $allEvaluated,
                'latest_completed' => $employeeResults->max('completed_at'),
            ];
        })->values();

        $stats = [
            'total_pending' => $groupedResults->filter(fn($e) => !$e->all_evaluated)->count(),
            'total_evaluated' => $groupedResults->filter(fn($e) => $e->all_evaluated)->count(),
            'total_employees' => $groupedResults->count(),
        ];

        return view('training_management.evaluation', compact('groupedResults', 'stats'));
    }

    /**
     * Show the hands-on evaluation form for a specific employee.
     */
    public function evaluate($employeeId, Request $request)
    {
        $assessmentResults = DB::connection('ess')
            ->table('assessment_results')
            ->whereIn('status', ['completed', 'passed'])
            ->where('status', '!=', 'retried')
            ->where('evaluation_status', 'passed')
            ->orderBy('completed_at', 'desc')
            ->get();

        $results = $assessmentResults->map(function ($result) {
            $assignment = DB::connection('learning_management')
                ->table('assessment_assignments as aa')
                ->join('quizzes as q', 'aa.quiz_id', '=', 'q.id')
                ->join('assessment_categories as ac', 'aa.assessment_category_id', '=', 'ac.id')
                ->where('aa.id', $result->assignment_id)
                ->select([
                    'aa.employee_name',
                    'aa.quiz_id',
                    'q.quiz_title',
                    'ac.category_name',
                    'aa.max_attempts',
                ])
                ->first();

            if ($assignment) {
                $result->result_id = $result->id;
                $result->employee_name = $assignment->employee_name;
                $result->quiz_id = $assignment->quiz_id;
                $result->quiz_title = $assignment->quiz_title;
                $result->category_name = $assignment->category_name;
                $result->max_attempts = $assignment->max_attempts;
            }

            return $result;
        })->filter(function ($result) use ($employeeId) {
            return isset($result->employee_id) && $result->employee_id == $employeeId;
        })->values();

        if ($results->isEmpty()) {
            return redirect()->route('training.evaluation.index')
                ->with('error', 'No completed assessments found for this employee.');
        }

        $employee = (object)[
            'employee_id' => $results->first()->employee_id,
            'employee_name' => $results->first()->employee_name,
            'assessments' => $results,
        ];

        return view('training_management.evaluation-form', compact('employee', 'results'));
    }

    /**
     * Submit the hands-on evaluation.
     */
    public function submitEvaluation(Request $request, $employeeId)
    {
        Log::info("=== SUBMIT EVALUATION STARTED ===", [
            'employee_id' => $employeeId,
            'decision' => $request->decision,
        ]);

        $request->validate([
            'competency_1' => 'required|string',
            'competency_2' => 'required|string',
            'competency_3' => 'required|string',
            'competency_4' => 'required|string',
            'competency_5' => 'required|string',
            'decision' => 'required|in:passed,failed',
        ]);

        $evaluationData = [
            'competencies' => [
                'skill_proficiency' => $request->competency_1,
                'job_knowledge' => $request->competency_2,
                'planning_organizing' => $request->competency_3,
                'accountability' => $request->competency_4,
                'work_improvement' => $request->competency_5,
            ],
            'strengths' => $request->strengths,
            'areas_for_improvement' => $request->areas_for_improvement,
            'decision' => $request->decision,
            'evaluated_at' => now()->toDateTimeString(),
            'evaluated_by' => auth()->id(),
        ];

        if ($request->result_ids) {
            $resultIds = explode(',', $request->result_ids);
        } else {
            $assignmentIds = DB::connection('learning_management')
                ->table('assessment_assignments')
                ->where('employee_id', $employeeId)
                ->pluck('id')
                ->toArray();

            $resultIds = DB::connection('ess')
                ->table('assessment_results')
                ->whereIn('assignment_id', $assignmentIds)
                ->where('status', 'completed')
                ->pluck('id')
                ->toArray();
        }

        // Update assessment results with evaluation data
        DB::connection('ess')
            ->table('assessment_results')
            ->whereIn('id', $resultIds)
            ->update([
                'evaluation_data' => json_encode($evaluationData),
                'evaluation_status' => $request->decision,
                'updated_at' => now(),
            ]);

        Log::info("Assessment results updated", ['result_ids' => $resultIds]);

        // Route based on decision
        if ($request->decision === 'passed') {
            Log::info("Decision is PASSED - calling handlePassedEvaluation");
            $this->handlePassedEvaluation($employeeId, $evaluationData);
            $message = 'Employee has been approved successfully! Added to Talent Pool with AI job recommendation.';
        } else {
            Log::info("Decision is FAILED - calling handleFailedEvaluation");
            $this->handleFailedEvaluation($employeeId, $evaluationData);
            $message = 'Employee evaluation marked as failed. Added to Gap Analysis for development planning.';
        }

        Log::info("=== SUBMIT EVALUATION COMPLETED ===");

        return redirect()->route('training.evaluation.index')
            ->with('success', $message);
    }

    /**
     * Handle PASSED evaluation: Write to promotions + AI job recommendation
     */
    private function handlePassedEvaluation(string $employeeId, array $evaluationData): void
    {
        Log::info("=== HANDLE PASSED EVALUATION START ===", ['employee_id' => $employeeId]);

        try {
            // 1. Get employee data - with fallback
            $employee = [];
            try {
                $employee = $this->employeeApiService->getEmployee($employeeId) ?? [];
                Log::info("Employee API data fetched", ['employee' => $employee]);
            } catch (\Throwable $e) {
                Log::warning("Employee API failed, using fallback", ['error' => $e->getMessage()]);
            }

            // 2. Get LMS assessment data
            $assessmentData = $this->getEmployeeAssessmentData($employeeId);
            Log::info("Assessment data fetched", ['assessment_data' => $assessmentData]);

            // 3. Build employee profile
            $employeeName = $employee['full_name'] ?? $assessmentData['employee_name'] ?? 'Employee ' . $employeeId;
            $employeeEmail = $employee['email'] ?? '';
            // job_title may be nested under 'job' key from raw API or flattened by EmployeeApiService
            $jobTitle = $employee['job_title'] ?? $employee['job']['job_title'] ?? 'Not Specified';
            $department = $employee['department'] ?? $employee['position']['department'] ?? 'Not Specified';

            Log::info("Building employee profile", [
                'name' => $employeeName,
                'email' => $employeeEmail,
                'job_title' => $jobTitle,
            ]);

            // 4. Insert into promotions table DIRECTLY with try-catch
            try {
                $existing = DB::connection('succession_planning')
                    ->table('promotions')
                    ->where('employee_id', $employeeId)
                    ->first();

                $data = [
                    'employee_id' => $employeeId,
                    'employee_name' => $employeeName,
                    'employee_email' => $employeeEmail,
                    'job_title' => $jobTitle,
                    'potential_job' => 'Pending AI Recommendation',
                    'assessment_score' => $assessmentData['average'] ?? 0,
                    'category' => 'Training Evaluation Passed',
                    'strengths' => $evaluationData['strengths'] ?? '',
                    'recommendations' => json_encode([
                        'competencies' => $evaluationData['competencies'],
                        'areas_for_improvement' => $evaluationData['areas_for_improvement'] ?? '',
                    ]),
                    'status' => 'pending',
                    'updated_at' => now(),
                ];

                Log::info("Promotion data prepared", ['data' => $data]);

                if ($existing) {
                    DB::connection('succession_planning')
                        ->table('promotions')
                        ->where('employee_id', $employeeId)
                        ->update($data);
                    Log::info("Promotion record UPDATED for employee: {$employeeId}");
                } else {
                    $data['created_at'] = now();
                    DB::connection('succession_planning')
                        ->table('promotions')
                        ->insert($data);
                    Log::info("Promotion record INSERTED for employee: {$employeeId}");
                }

            } catch (\Throwable $dbError) {
                Log::error("DATABASE ERROR inserting promotion", [
                    'error' => $dbError->getMessage(),
                    'trace' => $dbError->getTraceAsString()
                ]);
                throw $dbError;
            }

            // 5. Now try AI recommendation (separate try-catch so DB insert is not affected)
            try {
                Log::info("=== STARTING AI JOB RECOMMENDATION ===");
                
                $vacantJobs = $this->fetchVacantJobs();
                Log::info("Vacant jobs fetched", [
                    'total_count' => count($vacantJobs),
                    'jobs' => array_map(fn($j) => $j['job']['job_title'] ?? $j['job_title'] ?? 'Unknown', array_slice($vacantJobs, 0, 5))
                ]);

                if (empty($vacantJobs)) {
                    Log::warning("NO VACANT JOBS AVAILABLE - AI recommendation skipped");
                    return;
                }

                $employeeProfile = [
                    'employee_id' => $employeeId,
                    'name' => $employeeName,
                    'current_job_title' => $jobTitle,
                    'department' => $department,
                    'training_evaluation' => [
                        'decision' => 'passed',
                        'competencies' => $evaluationData['competencies'],
                        'competency_score' => $this->calculateEvaluationScore($evaluationData['competencies']),
                        'strengths' => $evaluationData['strengths'] ?? '',
                        'areas_for_improvement' => $evaluationData['areas_for_improvement'] ?? '',
                    ],
                    'lms_assessments' => [
                        'average_score' => $assessmentData['average'] ?? 0,
                        'total_completed' => $assessmentData['count'] ?? 0,
                        'categories' => $assessmentData['categories'] ?? [],
                        'assessments' => $assessmentData['assessments'] ?? [],
                    ],
                ];

                Log::info("Employee profile for AI", ['profile' => $employeeProfile]);

                $recommendation = $this->getAiJobRecommendation($employeeProfile, $vacantJobs);

                Log::info("AI recommendation result", ['recommendation' => $recommendation]);

                if ($recommendation && !empty($recommendation['selected_job'])) {
                    $this->updatePromotionWithAiRecommendation($employeeId, $recommendation);
                    Log::info("AI recommendation SAVED", ['job' => $recommendation['selected_job']]);
                } else {
                    Log::warning("AI returned no valid job recommendation", ['response' => $recommendation]);
                }
                
            } catch (\Throwable $aiError) {
                Log::error("AI recommendation FAILED", [
                    'error' => $aiError->getMessage(),
                    'trace' => $aiError->getTraceAsString()
                ]);
            }

            Log::info("=== HANDLE PASSED EVALUATION COMPLETE ===");

        } catch (\Throwable $e) {
            Log::error("CRITICAL ERROR in handlePassedEvaluation", [
                'employee_id' => $employeeId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Handle FAILED evaluation: Write to Gap Analysis (skill_gap_assignments)
     */
    private function handleFailedEvaluation(string $employeeId, array $evaluationData): void
    {
        try {
            Log::info("Processing FAILED evaluation for employee: {$employeeId}");

            // Get employee data
            $employee = $this->employeeApiService->getEmployee($employeeId) ?? [];
            $assessmentData = $this->getEmployeeAssessmentData($employeeId);

            $employeeName = $employee['full_name'] ?? $assessmentData['employee_name'] ?? 'Unknown';
            $jobTitle = $employee['job_title'] ?? $employee['job']['job_title'] ?? 'Not Specified';

            // Identify weak competencies (unsatisfactory or inconsistent ratings)
            $weakCompetencies = $this->identifyWeakCompetencies($evaluationData['competencies']);

            // Create skill gap assignments for each weak competency
            foreach ($weakCompetencies as $competency) {
                // Check if assignment already exists
                $existing = DB::connection('competency_management')
                    ->table('skill_gap_assignments')
                    ->where('employee_id', $employeeId)
                    ->where('competency_key', $competency['key'])
                    ->whereIn('status', ['pending', 'in_progress'])
                    ->first();

                if (!$existing) {
                    DB::connection('competency_management')
                        ->table('skill_gap_assignments')
                        ->insert([
                            'employee_id' => $employeeId,
                            'employee_name' => $employeeName,
                            'job_title' => $jobTitle,
                            'competency_key' => $competency['key'],
                            'action_type' => $competency['action_type'],
                            'notes' => "Failed Training Evaluation - {$competency['label']}: {$competency['rating']}. Areas for improvement: " . ($evaluationData['areas_for_improvement'] ?? 'N/A'),
                            'status' => 'pending',
                            'assigned_by' => auth()->id(),
                            'assigned_at' => now(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                    Log::info("Created skill gap assignment for employee {$employeeId}: {$competency['key']}");
                }
            }

            // Also create a development plan if areas for improvement were specified
            if (!empty($evaluationData['areas_for_improvement'])) {
                $this->createDevelopmentPlan($employeeId, $employeeName, $jobTitle, $evaluationData);
            }

            Log::info("Gap Analysis entries created for employee: {$employeeId}", [
                'weak_competencies_count' => count($weakCompetencies)
            ]);

        } catch (\Throwable $e) {
            Log::error("Failed to process failed evaluation for employee {$employeeId}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Identify weak competencies from evaluation ratings
     */
    private function identifyWeakCompetencies(array $competencies): array
    {
        $weakCompetencies = [];
        
        $competencyLabels = [
            'skill_proficiency' => 'Skill and proficiency in carrying out assignment',
            'job_knowledge' => 'Job knowledge and skills',
            'planning_organizing' => 'Planning, organizing and prioritizing',
            'accountability' => 'Accountability and responsibility',
            'work_improvement' => 'Work improvement and efficiency',
        ];

        foreach ($competencies as $key => $rating) {
            $ratingLower = strtolower($rating);
            
            // Identify weak ratings
            if (in_array($ratingLower, ['unsatisfactory', 'inconsistent'])) {
                $actionType = $ratingLower === 'unsatisfactory' ? 'critical' : 'training';
                
                $weakCompetencies[] = [
                    'key' => $key,
                    'label' => $competencyLabels[$key] ?? $key,
                    'rating' => $rating,
                    'action_type' => $actionType,
                ];
            }
        }

        return $weakCompetencies;
    }

    /**
     * Create a development plan for failed employee
     */
    private function createDevelopmentPlan(string $employeeId, string $employeeName, string $jobTitle, array $evaluationData): void
    {
        // Check if a development plan already exists
        $existing = DB::connection('competency_management')
            ->table('development_plans')
            ->where('employee_id', $employeeId)
            ->where('status', 'active')
            ->first();

        if ($existing) {
            // Update existing plan with new notes
            DB::connection('competency_management')
                ->table('development_plans')
                ->where('id', $existing->id)
                ->update([
                    'notes' => ($existing->notes ?? '') . "\n\n[" . now()->format('Y-m-d') . "] Failed Training Evaluation. Areas for improvement: " . $evaluationData['areas_for_improvement'],
                    'updated_at' => now(),
                ]);
            return;
        }

        // Create new development plan
        $objectives = [];
        foreach ($evaluationData['competencies'] as $key => $rating) {
            if (in_array(strtolower($rating), ['unsatisfactory', 'inconsistent', 'proficient'])) {
                $objectives[] = "Improve {$key} from '{$rating}' to 'Highly Effective' or better";
            }
        }

        if (empty($objectives)) {
            $objectives = ['Complete additional training and reassessment'];
        }

        DB::connection('competency_management')
            ->table('development_plans')
            ->insert([
                'employee_id' => $employeeId,
                'employee_name' => $employeeName,
                'job_title' => $jobTitle,
                'plan_title' => 'Post-Evaluation Development Plan',
                'objectives' => json_encode($objectives),
                'timeline' => '30-90 days',
                'resources' => json_encode(['Training modules', 'Mentoring sessions', 'Practical exercises']),
                'status' => 'active',
                'notes' => "Created after failed Training Evaluation. Areas for improvement: " . ($evaluationData['areas_for_improvement'] ?? 'N/A'),
                'created_by' => auth()->id(),
                'start_date' => now(),
                'target_date' => now()->addDays(90),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    }

    /**
     * Create promotion record for passed employee
     */
    private function createPromotionRecord(string $employeeId, array $employeeProfile): void
    {
        $existing = DB::connection('succession_planning')
            ->table('promotions')
            ->where('employee_id', $employeeId)
            ->first();

        $data = [
            'employee_id' => $employeeId,
            'employee_name' => $employeeProfile['name'],
            'employee_email' => $employeeProfile['email'] ?? '',
            'job_title' => $employeeProfile['current_job_title'],
            'potential_job' => 'Pending AI Recommendation',
            'assessment_score' => $employeeProfile['lms_assessments']['average_score'] ?? null,
            'category' => 'Training Evaluation Passed',
            'strengths' => is_array($employeeProfile['training_evaluation']['strengths'] ?? null) 
                ? json_encode($employeeProfile['training_evaluation']['strengths']) 
                : ($employeeProfile['training_evaluation']['strengths'] ?? ''),
            'recommendations' => json_encode([
                'training_evaluation' => $employeeProfile['training_evaluation'] ?? [],
                'lms_assessments' => $employeeProfile['lms_assessments'] ?? [],
                'overall_performance' => $employeeProfile['overall_performance'] ?? [],
            ]),
            'status' => 'pending',
            'updated_at' => now(),
        ];

        if ($existing) {
            DB::connection('succession_planning')
                ->table('promotions')
                ->where('employee_id', $employeeId)
                ->update($data);
        } else {
            $data['created_at'] = now();
            DB::connection('succession_planning')
                ->table('promotions')
                ->insert($data);
        }

        Log::info("Promotion record created/updated for employee: {$employeeId}");
    }

    /**
     * Update promotion record with AI recommendation
     */
    private function updatePromotionWithAiRecommendation(string $employeeId, array $recommendation): void
    {
        $existingRec = DB::connection('succession_planning')
            ->table('promotions')
            ->where('employee_id', $employeeId)
            ->first();

        $existingRecommendations = [];
        if ($existingRec && $existingRec->recommendations) {
            $existingRecommendations = json_decode($existingRec->recommendations, true) ?? [];
        }

        // Merge AI recommendation with existing data
        $mergedRecommendations = array_merge($existingRecommendations, [
            'ai_reasoning' => $recommendation['reasoning'] ?? '',
            'match_score' => $recommendation['match_score'] ?? 0,
            'readiness' => $recommendation['readiness'] ?? 'Unknown',
            'development_areas' => $recommendation['development_areas'] ?? [],
            'recommended_department' => $recommendation['department'] ?? '',
        ]);

        DB::connection('succession_planning')
            ->table('promotions')
            ->where('employee_id', $employeeId)
            ->update([
                'potential_job' => $recommendation['selected_job'],
                'category' => 'AI Recommended',
                'recommendations' => json_encode($mergedRecommendations),
                'updated_at' => now(),
            ]);
    }

    /**
     * Fetch vacant jobs from the Jobs API
     */
    private function fetchVacantJobs(): array
    {
        try {
            Log::info("Fetching jobs from external HR4 API...");
            
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->withHeaders([
                    'Accept' => 'application/json',
                ])
                ->get('https://hr4.microfinancial-1.com/api/employees/job');

            if (!$response->successful()) {
                Log::warning("External Jobs API failed", [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return [];
            }

            $result = $response->json();
            Log::info("Jobs API raw response", ['keys' => array_keys($result ?? [])]);
            
            $jobs = $result['data'] ?? $result ?? [];
            
            if (empty($jobs)) {
                Log::warning("Jobs API returned empty data");
                return [];
            }

            Log::info("Total jobs from API: " . count($jobs));

            // Filter for vacant jobs - check both possible structures
            $vacantJobs = array_filter($jobs, function($job) {
                $status = $job['status'] ?? $job['job_status'] ?? '';
                $isVacant = strtolower($status) === 'vacant';
                return $isVacant;
            });

            Log::info("Vacant jobs after filter: " . count($vacantJobs));

            return array_values($vacantJobs);

        } catch (\Exception $e) {
            Log::error("Failed to fetch jobs from external API", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [];
        }
    }

    /**
     * Get employee assessment data
     */
    private function getEmployeeAssessmentData(string $employeeId): array
    {
        // Get all passed/completed assessments with quiz details
        $results = DB::connection('ess')
            ->table('assessment_results as ar')
            ->leftJoin('hr2_learning_management.assessment_assignments as aa', 'ar.assignment_id', '=', 'aa.id')
            ->leftJoin('hr2_learning_management.quizzes as q', 'aa.quiz_id', '=', 'q.id')
            ->leftJoin('hr2_learning_management.assessment_categories as ac', 'aa.assessment_category_id', '=', 'ac.id')
            ->where('ar.employee_id', $employeeId)
            ->whereIn('ar.status', ['passed', 'completed'])
            ->select([
                'ar.score',
                'ar.status',
                'ar.completed_at',
                'aa.employee_name',
                'q.quiz_title',
                'ac.category_name',
            ])
            ->get();

        $assessments = [];
        $scores = [];
        $categories = [];
        $employeeName = null;

        foreach ($results as $r) {
            if (!$employeeName && $r->employee_name) {
                $employeeName = $r->employee_name;
            }
            
            if ($r->score !== null) {
                $scores[] = floatval($r->score);
            }
            
            if ($r->category_name && !in_array($r->category_name, $categories)) {
                $categories[] = $r->category_name;
            }
            
            // Build detailed assessment list for AI
            $assessments[] = [
                'quiz_title' => $r->quiz_title ?? 'Unknown Quiz',
                'category' => $r->category_name ?? 'General',
                'score' => $r->score,
                'status' => $r->status,
                'completed_at' => $r->completed_at,
            ];
        }

        return [
            'employee_name' => $employeeName,
            'assessments' => $assessments,
            'scores' => $scores,
            'categories' => $categories,
            'count' => count($scores),
            'average' => count($scores) > 0 ? round(array_sum($scores) / count($scores), 1) : 0,
        ];
    }

/**
 * Calculate combined score from LMS and Evaluation
 */
    private function calculateCombinedScore(float $lmsAverage, float $evaluationScore): float
    {
        // Weight: 40% LMS, 60% Hands-on Evaluation
        $lmsWeight = 0.4;
        $evalWeight = 0.6;
        
        // Normalize evaluation score (1-5 scale) to 0-100
        $normalizedEvalScore = ($evaluationScore / 5) * 100;
        
        return round(($lmsAverage * $lmsWeight) + ($normalizedEvalScore * $evalWeight), 1);
    }

    /**
     * Call AI service to get job recommendation
     */
    private function getAiJobRecommendation(array $employeeProfile, array $vacantJobs): ?array
    {
        $templatePath = resource_path('prompts/job_selection_template.txt');
        
        if (!file_exists($templatePath)) {
            Log::error("Job selection template not found at: {$templatePath}");
            return null;
        }

        $template = file_get_contents($templatePath);
        
        // Build the prompt with employee and jobs data
        $prompt = str_replace('{{employee_json}}', json_encode($employeeProfile, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), $template);
        $prompt = str_replace('{{jobs_json}}', json_encode($vacantJobs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), $prompt);

        Log::info("AI Prompt prepared", [
            'template_length' => strlen($template),
            'prompt_length' => strlen($prompt),
            'employee_name' => $employeeProfile['name'] ?? 'Unknown',
            'jobs_count' => count($vacantJobs)
        ]);

        $model = env('OPENAI_MODEL', 'gpt-4o-mini');
        $provider = env('PRISM_PROVIDER', 'openai');

        Log::info("Calling AI", ['provider' => $provider, 'model' => $model]);

        try {
            $raw = app('prism')
                ->text()
                ->using($provider, $model)
                ->withPrompt($prompt)
                ->asText();

            Log::info("AI raw response type: " . gettype($raw));

            $rawText = $this->extractTextFromPrismResponse($raw);
            
            Log::info("AI response text", ['text' => substr($rawText, 0, 500)]);

            // Try to parse JSON
            $decoded = json_decode($rawText, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning("JSON decode failed, trying regex extraction", ['error' => json_last_error_msg()]);
                
                // Try to extract JSON from markdown code blocks
                if (preg_match('/```(?:json)?\s*(\{.*?\})\s*```/s', $rawText, $m)) {
                    $decoded = json_decode($m[1], true);
                } elseif (preg_match('/\{[^{}]*"selected_job"[^{}]*\}/s', $rawText, $m)) {
                    $decoded = json_decode($m[0], true);
                }
            }

            if (!is_array($decoded)) {
                Log::error("Could not parse AI response as JSON", ['raw' => $rawText]);
                return null;
            }

            Log::info("AI recommendation parsed successfully", ['decoded' => $decoded]);

            return $decoded;

        } catch (\Throwable $e) {
            Log::error("AI API call failed", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Extract text from Prism response object
     */
    private function extractTextFromPrismResponse($raw): string
    {
        if (is_string($raw)) {
            return $raw;
        }

        if (is_object($raw)) {
            if (property_exists($raw, 'text') && is_string($raw->text)) {
                return $raw->text;
            }
            if (method_exists($raw, 'getText')) {
                return $raw->getText();
            }
            if (method_exists($raw, '__toString')) {
                return (string) $raw;
            }
        }

        return (string) $raw;
    }

    /**
     * Calculate numeric score from competency ratings
     */
    private function calculateEvaluationScore(array $competencies): float
    {
        $scoreMap = [
            'exceptional' => 5.0,
            'highly_effective' => 4.0,
            'proficient' => 3.0,
            'inconsistent' => 2.0,
            'unsatisfactory' => 1.0,
        ];

        $total = 0;
        $count = 0;

        foreach ($competencies as $rating) {
            if (isset($scoreMap[strtolower($rating)])) {
                $total += $scoreMap[strtolower($rating)];
                $count++;
            }
        }

        return $count > 0 ? round($total / $count, 2) : 0;
    }
}