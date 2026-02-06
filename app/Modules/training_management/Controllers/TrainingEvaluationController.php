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

        DB::connection('ess')
            ->table('assessment_results')
            ->whereIn('id', $resultIds)
            ->update([
                'evaluation_data' => json_encode($evaluationData),
                'evaluation_status' => $request->decision,
                'updated_at' => now(),
            ]);

        // If PASSED, trigger automatic AI job recommendation
        if ($request->decision === 'passed') {
            $this->generateAiJobRecommendation($employeeId, $evaluationData);
        }

        $message = $request->decision === 'passed' 
            ? 'Employee has been approved successfully! AI job recommendation generated.' 
            : 'Employee evaluation marked as failed.';

        return redirect()->route('training.evaluation.index')
            ->with('success', $message);
    }

    /**
     * Generate AI job recommendation and store in promotions table
     */
    private function generateAiJobRecommendation(string $employeeId, array $evaluationData): void
    {
        try {
            Log::info("Generating AI job recommendation for employee: {$employeeId}");

            // 1. Fetch available jobs from the Jobs API
            $vacantJobs = $this->fetchVacantJobs();
            
            if (empty($vacantJobs)) {
                Log::warning("No vacant jobs available for AI recommendation for employee: {$employeeId}");
                return;
            }

            // 2. Get employee data from API
            $employee = $this->employeeApiService->getEmployeeById($employeeId);
            
            // 3. Get additional assessment data
            $assessmentData = $this->getEmployeeAssessmentData($employeeId);

            // 4. Build employee profile for AI
            $employeeProfile = [
                'employee_id' => $employeeId,
                'name' => $employee['full_name'] ?? $assessmentData['employee_name'] ?? 'Unknown',
                'email' => $employee['email'] ?? '',
                'current_job_title' => $employee['job_title'] ?? 'Not Specified',
                'department' => $employee['department'] ?? 'Not Specified',
                'evaluation' => [
                    'competencies' => $evaluationData['competencies'],
                    'competency_score' => $this->calculateEvaluationScore($evaluationData['competencies']),
                    'strengths' => $evaluationData['strengths'] ?? '',
                    'areas_for_improvement' => $evaluationData['areas_for_improvement'] ?? '',
                    'decision' => $evaluationData['decision'],
                ],
                'assessment_scores' => $assessmentData['scores'] ?? [],
                'average_assessment_score' => $assessmentData['average'] ?? 0,
                'completed_assessments' => $assessmentData['count'] ?? 0,
            ];

            // 5. Call AI service for job recommendation
            $aiService = app(AIService::class);
            $recommendation = $this->getAiJobRecommendation($aiService, $employeeProfile, $vacantJobs);

            if (!$recommendation || empty($recommendation['selected_job'])) {
                Log::warning("AI could not recommend a job for employee: {$employeeId}");
                return;
            }

            // 6. Store recommendation in succession_planning.promotions table
            $this->storeAiRecommendation($employeeId, $employeeProfile, $recommendation);

            Log::info("AI job recommendation stored successfully for employee: {$employeeId}", [
                'recommended_job' => $recommendation['selected_job']
            ]);

        } catch (\Throwable $e) {
            Log::error("Failed to generate AI job recommendation for employee {$employeeId}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Fetch vacant jobs from the Jobs API
     */
    private function fetchVacantJobs(): array
    {
        try {
            // Use internal API route
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->get(url('/api/jobs'));

            if (!$response->successful()) {
                Log::warning("Jobs API failed in TrainingEvaluationController: " . $response->status());
                return [];
            }

            $result = $response->json();
            $jobs = $result['data'] ?? $result ?? [];

            // Filter only vacant jobs
            $vacantJobs = array_filter($jobs, function($job) {
                return strtolower($job['status'] ?? '') === 'vacant';
            });

            return array_values($vacantJobs);

        } catch (\Exception $e) {
            Log::error("Failed to fetch jobs: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get employee assessment data
     */
    private function getEmployeeAssessmentData(string $employeeId): array
    {
        $results = DB::connection('ess')
            ->table('assessment_results as ar')
            ->leftJoin('hr2_learning_management.assessment_assignments as aa', 'ar.assignment_id', '=', 'aa.id')
            ->where('ar.employee_id', $employeeId)
            ->whereIn('ar.status', ['passed', 'completed'])
            ->select(['ar.score', 'aa.employee_name'])
            ->get();

        $scores = [];
        $employeeName = null;
        foreach ($results as $r) {
            if ($r->score !== null) {
                $scores[] = floatval($r->score);
            }
            if (!$employeeName && $r->employee_name) {
                $employeeName = $r->employee_name;
            }
        }

        return [
            'employee_name' => $employeeName,
            'scores' => $scores,
            'count' => count($scores),
            'average' => count($scores) > 0 ? round(array_sum($scores) / count($scores), 1) : 0,
        ];
    }

    /**
     * Call AI service to get job recommendation
     */
    private function getAiJobRecommendation(AIService $aiService, array $employeeProfile, array $vacantJobs): ?array
    {
        $templatePath = resource_path('prompts/job_selection_template.txt');
        
        if (!file_exists($templatePath)) {
            Log::error("Job selection template not found at: {$templatePath}");
            return null;
        }

        $template = file_get_contents($templatePath);
        
        // Build the prompt with both employee and jobs data
        $prompt = str_replace('{{employee_json}}', json_encode($employeeProfile, JSON_UNESCAPED_UNICODE), $template);
        $prompt = str_replace('{{jobs_json}}', json_encode($vacantJobs, JSON_UNESCAPED_UNICODE), $prompt);

        $model = env('OPENAI_MODEL', 'gpt-3.5-turbo');

        try {
            // Call Prism AI
            $raw = app('prism')
                ->text()
                ->using(env('PRISM_PROVIDER', 'openai'), $model)
                ->withPrompt($prompt)
                ->asText();

            // Parse response
            $rawText = $this->extractTextFromPrismResponse($raw);
            $decoded = json_decode($rawText, true);

            // Fallback parsing
            if (!is_array($decoded) && preg_match('/\{.*\}/s', $rawText, $m)) {
                $decoded = json_decode($m[0], true);
            }

            return $decoded;

        } catch (\Throwable $e) {
            Log::error("AI job recommendation call failed: " . $e->getMessage());
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
     * Store AI recommendation in promotions table
     */
    private function storeAiRecommendation(string $employeeId, array $employeeProfile, array $recommendation): void
    {
        // Check if employee already has a record in promotions
        $existing = DB::connection('succession_planning')
            ->table('promotions')
            ->where('employee_id', $employeeId)
            ->first();

        $data = [
            'employee_id' => $employeeId,
            'employee_name' => $employeeProfile['name'],
            'employee_email' => $employeeProfile['email'],
            'job_title' => $employeeProfile['current_job_title'],
            'potential_job' => $recommendation['selected_job'],
            'assessment_score' => $employeeProfile['average_assessment_score'] ?? null,
            'category' => 'AI Recommended',
            'strengths' => is_array($employeeProfile['evaluation']['strengths'] ?? null) 
                ? json_encode($employeeProfile['evaluation']['strengths']) 
                : ($employeeProfile['evaluation']['strengths'] ?? ''),
            'recommendations' => json_encode([
                'ai_reasoning' => $recommendation['reasoning'] ?? '',
                'match_score' => $recommendation['match_score'] ?? 0,
                'readiness' => $recommendation['readiness'] ?? 'Unknown',
                'development_areas' => $recommendation['development_areas'] ?? [],
                'department' => $recommendation['department'] ?? '',
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