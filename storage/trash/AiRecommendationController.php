<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\AIService;

class AiJobRecommendationController extends Controller
{
    /**
     * Generate AI-powered job recommendations based on gap analysis performance
     */
    public function recommend(Request $request, AIService $ai)
    {
        $employeeId = $request->input('employee_id');
        
        // Gather employee data from gap analysis
        $employeeData = $this->getEmployeeGapAnalysisData($employeeId);
        
        if (!$employeeData) {
            return response()->json([
                'success' => false,
                'message' => 'No gap analysis data found for this employee'
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

        try {
            // Use custom prompt template for job recommendations
            $template = resource_path('prompts/job_recommendation_template.txt');
            $result = $ai->recommendFromPayload($payload, $template);
            
            return response()->json([
                'success' => true,
                'employee' => $employeeData['name'],
                'current_position' => $employeeData['job_title'],
                'recommendations' => $result,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get employee's gap analysis and performance data
     */
    private function getEmployeeGapAnalysisData($employeeId)
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
            return null;
        }

        // Get employee info from external API
        $employeeApiService = app(\App\Services\EmployeeApiService::class);
        $employee = $employeeApiService->getEmployeeById($employeeId);

        // Get assigned competencies with gaps
        $assignedCompetencies = DB::connection('competency_management')
            ->table('assigned_competencies')
            ->where('employee_id', $employeeId)
            ->get();

        // Calculate competency scores and identify gaps/strengths
        $competencyScores = [];
        $strengths = [];
        $skillGaps = [];

        foreach ($assessmentResults as $result) {
            $score = floatval($result->score);
            $competencyName = $result->competency_name ?? $result->category_name ?? 'General';
            
            $competencyScores[$competencyName] = $score;
            
            if ($score >= 80) {
                $strengths[] = $competencyName;
            } elseif ($score < 60) {
                $skillGaps[] = [
                    'competency' => $competencyName,
                    'score' => $score,
                    'gap' => 60 - $score
                ];
            }
        }

        // Calculate overall performance score
        $overallScore = count($competencyScores) > 0 
            ? array_sum($competencyScores) / count($competencyScores) 
            : 0;

        // Get completed trainings
        $completedTrainings = DB::connection('training_management')
            ->table('training_assignment_employees as tae')
            ->join('training_assignments as ta', 'tae.training_assignment_id', '=', 'ta.id')
            ->where('tae.employee_id', $employeeId)
            ->where('tae.status', 'completed')
            ->pluck('ta.training_title')
            ->toArray();

        return [
            'name' => $employee['full_name'] ?? $assessmentResults->first()->name,
            'email' => $employee['email'] ?? $assessmentResults->first()->email,
            'job_title' => $employee['job_title'] ?? 'Unknown',
            'department' => $employee['department'] ?? 'Unknown',
            'competencies' => $competencyScores,
            'assessment_scores' => $assessmentResults->pluck('score', 'quiz_title')->toArray(),
            'strengths' => $strengths,
            'skill_gaps' => $skillGaps,
            'completed_trainings' => $completedTrainings,
            'overall_score' => round($overallScore, 2),
        ];
    }
}