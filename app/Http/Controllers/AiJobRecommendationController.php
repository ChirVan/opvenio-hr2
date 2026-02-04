<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\AIService;
use App\Services\EmployeeApiService;

class AiJobRecommendationController extends Controller
{
    protected $employeeApiService;

    public function __construct(EmployeeApiService $employeeApiService)
    {
        $this->employeeApiService = $employeeApiService;
    }

    /**
     * Generate AI-powered job recommendations based on gap analysis performance
     */
    public function recommend(Request $request, AIService $ai)
    {
        $request->validate([
            'employee_id' => 'required|string'
        ]);

        $employeeId = $request->input('employee_id');
        
        Log::info("AI Job Recommendation requested for employee: {$employeeId}");

        try {
            // Gather employee data from gap analysis
            $employeeData = $this->getEmployeeGapAnalysisData($employeeId);
            
            if (!$employeeData) {
                return response()->json([
                    'success' => false,
                    'message' => 'No gap analysis data found for this employee. Employee must have completed assessments.'
                ], 404);
            }

            // Build AI payload with performance data
            $payload = [
                'employee_id' => $employeeId,
                'employee_name' => $employeeData['name'],
                'current_job_title' => $employeeData['job_title'],
                'department' => $employeeData['department'],
                'competencies' => $employeeData['competencies'],
                'assessment_scores' => $employeeData['assessment_scores'],
                'skill_gaps' => $employeeData['skill_gaps'],
                'strengths' => $employeeData['strengths'],
                'completed_trainings' => $employeeData['completed_trainings'],
                'overall_performance_score' => $employeeData['overall_score'],
            ];

            // Use custom prompt template for job recommendations
            $template = resource_path('prompts/job_recommendation_template.txt');
            
            if (!file_exists($template)) {
                Log::error("Job recommendation template not found at: {$template}");
                return response()->json([
                    'success' => false,
                    'message' => 'AI template configuration error'
                ], 500);
            }

            $result = $ai->recommendFromPayload($payload, $template);
            
            Log::info("AI Job Recommendation generated successfully for employee: {$employeeId}");

            return response()->json([
                'success' => true,
                'employee' => $employeeData['name'],
                'employee_id' => $employeeId,
                'current_position' => $employeeData['job_title'],
                'department' => $employeeData['department'],
                'overall_score' => $employeeData['overall_score'],
                'recommendations' => $result['recommendations'] ?? [],
                'summary' => $result['summary'] ?? 'Analysis complete.',
            ]);

        } catch (\Throwable $e) {
            Log::error("AI Job Recommendation failed for employee {$employeeId}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate AI recommendation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get employee's gap analysis and performance data
     */
    private function getEmployeeGapAnalysisData(string $employeeId): ?array
    {
        // Get assessment results with scores
        $assessmentResults = DB::connection('ess')
            ->table('assessment_results as ar')
            ->join('hr2_opvenio_hr2.users as u', 'ar.employee_id', '=', 'u.employee_id')
            ->leftJoin('hr2_learning_management.assessment_assignments as aa', 'ar.assignment_id', '=', 'aa.id')
            ->leftJoin('hr2_learning_management.quizzes as q', 'aa.quiz_id', '=', 'q.id')
            ->leftJoin('hr2_learning_management.assessment_categories as ac', 'aa.assessment_category_id', '=', 'ac.id')
            ->leftJoin('hr2_competency_management.competencies as c', 'q.competency_id', '=', 'c.id')
            ->where('ar.employee_id', $employeeId)
            ->whereIn('ar.status', ['passed', 'completed'])
            ->select([
                'u.name',
                'u.email',
                'q.quiz_title',
                'ac.category_name',
                'c.name as competency_name',
                'c.description as competency_description',
                'ar.score',
                'ar.status',
                'ar.evaluation_data',
            ])
            ->get();

        if ($assessmentResults->isEmpty()) {
            Log::info("No assessment results found for employee: {$employeeId}");
            return null;
        }

        // Get employee info from external API
        $employee = $this->employeeApiService->getEmployeeById($employeeId);

        // Get assigned competencies with gaps
        $assignedCompetencies = [];
        try {
            $assignedCompetencies = DB::connection('competency_management')
                ->table('assigned_competencies')
                ->where('employee_id', $employeeId)
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            Log::warning("Could not fetch assigned competencies for {$employeeId}: " . $e->getMessage());
        }

        // Calculate competency scores and identify gaps/strengths
        $competencyScores = [];
        $strengths = [];
        $skillGaps = [];

        foreach ($assessmentResults as $result) {
            $score = floatval($result->score);
            $competencyName = $result->competency_name ?? $result->category_name ?? 'General Assessment';
            
            // Store the highest score if multiple assessments for same competency
            if (!isset($competencyScores[$competencyName]) || $score > $competencyScores[$competencyName]) {
                $competencyScores[$competencyName] = $score;
            }
        }

        // Analyze scores to identify strengths and gaps
        foreach ($competencyScores as $competencyName => $score) {
            if ($score >= 80) {
                $strengths[] = $competencyName . " ({$score}%)";
            } elseif ($score < 60) {
                $skillGaps[] = [
                    'competency' => $competencyName,
                    'score' => $score,
                    'gap' => round(60 - $score, 1),
                    'priority' => $score < 40 ? 'High' : 'Medium'
                ];
            }
        }

        // Sort skill gaps by priority (lowest score first)
        usort($skillGaps, function($a, $b) {
            return $a['score'] <=> $b['score'];
        });

        // Calculate overall performance score
        $overallScore = count($competencyScores) > 0 
            ? round(array_sum($competencyScores) / count($competencyScores), 1)
            : 0;

        // Get completed trainings
        $completedTrainings = [];
        try {
            $completedTrainings = DB::connection('training_management')
                ->table('training_assignment_employees as tae')
                ->join('training_assignments as ta', 'tae.training_assignment_id', '=', 'ta.id')
                ->where('tae.employee_id', $employeeId)
                ->where('tae.status', 'completed')
                ->pluck('ta.training_title')
                ->toArray();
        } catch (\Exception $e) {
            Log::warning("Could not fetch trainings for {$employeeId}: " . $e->getMessage());
        }

        // Build the response data
        $firstName = $assessmentResults->first();
        
        return [
            'name' => $employee['full_name'] ?? $firstName->name ?? 'Unknown',
            'email' => $employee['email'] ?? $firstName->email ?? '',
            'job_title' => $employee['job_title'] ?? 'Not Specified',
            'department' => $employee['department'] ?? 'Not Specified',
            'competencies' => $competencyScores,
            'assessment_scores' => $assessmentResults->pluck('score', 'quiz_title')->toArray(),
            'strengths' => $strengths,
            'skill_gaps' => $skillGaps,
            'completed_trainings' => $completedTrainings,
            'overall_score' => $overallScore,
            'total_assessments' => $assessmentResults->count(),
        ];
    }
}