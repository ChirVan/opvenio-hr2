<?php

namespace App\Modules\succession_planning\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TalentPoolController extends Controller
{
    /**
     * Display the talent pool with approved employees from promotions table
     */
    public function index()
    {
        // Get employees from promotions table (written by Training Evaluation when passed)
        $talentPool = DB::connection('succession_planning')
            ->table('promotions')
            ->whereIn('status', ['pending', 'approved']) // Show pending and approved (not yet promoted)
            ->orderBy('created_at', 'desc')
            ->get();

        // Enrich with evaluation data from assessment_results
        $employeeIds = $talentPool->pluck('employee_id')->unique()->toArray();
        
        // Get latest evaluation data for each employee
        $evaluationDataMap = [];
        $assessmentInfoMap = [];
        if (!empty($employeeIds)) {
            // Get latest assessment result with evaluation data per employee
            $latestResults = DB::connection('ess')
                ->table('assessment_results as ar')
                ->leftJoin('hr2_learning_management.assessment_assignments as aa', 'ar.assignment_id', '=', 'aa.id')
                ->leftJoin('hr2_learning_management.quizzes as q', 'aa.quiz_id', '=', 'q.id')
                ->leftJoin('hr2_learning_management.assessment_categories as ac', 'aa.assessment_category_id', '=', 'ac.id')
                ->whereIn('ar.employee_id', $employeeIds)
                ->whereNotNull('ar.evaluation_data')
                ->select([
                    'ar.employee_id',
                    'ar.score',
                    'ar.evaluation_data',
                    'ar.evaluation_status',
                    'ar.evaluated_at',
                    'ar.completed_at',
                    'q.quiz_title',
                    'ac.category_name',
                ])
                ->orderBy('ar.completed_at', 'desc')
                ->get();

            foreach ($latestResults as $result) {
                $eid = $result->employee_id;
                if (!isset($evaluationDataMap[$eid])) {
                    $evalData = json_decode($result->evaluation_data, true) ?? [];
                    
                    // Normalize: extract competency ratings (support both nested and flat format)
                    $competencyRatings = $evalData['competencies'] ?? [];
                    $normalizedEvalData = $evalData;
                    
                    // If nested format, also provide flat keys for the view's profile modal
                    if (!empty($competencyRatings) && !isset($evalData['competency_1'])) {
                        $normalizedEvalData['competency_1'] = $competencyRatings['skill_proficiency'] ?? null;
                        $normalizedEvalData['competency_2'] = $competencyRatings['job_knowledge'] ?? null;
                        $normalizedEvalData['competency_3'] = $competencyRatings['planning_organizing'] ?? null;
                        $normalizedEvalData['competency_4'] = $competencyRatings['accountability'] ?? null;
                        $normalizedEvalData['competency_5'] = $competencyRatings['work_improvement'] ?? null;
                    }
                    
                    // Calculate average score from competencies (1-5 scale)
                    $scoreMap = [
                        'exceptional' => 5.0, 'highly_effective' => 4.0,
                        'proficient' => 3.0, 'inconsistent' => 2.0, 'unsatisfactory' => 1.0,
                    ];
                    $ratings = !empty($competencyRatings) ? $competencyRatings : [];
                    // Fallback to flat format
                    if (empty($ratings)) {
                        for ($i = 1; $i <= 5; $i++) {
                            $key = "competency_{$i}";
                            if (isset($evalData[$key])) {
                                $ratings[$key] = $evalData[$key];
                            }
                        }
                    }
                    $total = 0; $count = 0;
                    foreach ($ratings as $rating) {
                        if (isset($scoreMap[strtolower($rating)])) {
                            $total += $scoreMap[strtolower($rating)];
                            $count++;
                        }
                    }
                    $avgScore = $count > 0 ? round($total / $count, 2) : 0;

                    $evaluationDataMap[$eid] = [
                        'evaluation_data' => $normalizedEvalData,
                        'average_score' => $avgScore,
                    ];
                    $assessmentInfoMap[$eid] = [
                        'score' => $result->score,
                        'quiz_title' => $result->quiz_title ?? 'Assessment',
                        'category_name' => $result->category_name ?? 'General',
                        'evaluated_at' => $result->evaluated_at ?? $result->completed_at,
                    ];
                }
            }
        }

        // Process for display - map fields to match what the view expects
        $processedTalentPool = $talentPool->map(function ($employee) use ($evaluationDataMap, $assessmentInfoMap) {
            $recommendations = json_decode($employee->recommendations, true) ?? [];
            $eid = $employee->employee_id;
            $evalInfo = $evaluationDataMap[$eid] ?? [];
            $assessInfo = $assessmentInfoMap[$eid] ?? [];
            
            // average_score: prefer computed from evaluation data, fallback to promotion record
            $averageScore = $evalInfo['average_score'] ?? $employee->assessment_score ?? 0;
            $quizScore = $assessInfo['score'] ?? 0;
            
            // Calculate succession readiness for header counts
            $competencyScore = $averageScore;
            $performanceScore = min($quizScore / 20, 5);
            $successionReadiness = ($competencyScore * 0.7) + ($performanceScore * 0.3);
            
            return (object) [
                'id' => $employee->id,
                'employee_id' => $eid,
                'employee_name' => $employee->employee_name,
                'employee_email' => $employee->employee_email,
                'job_title' => $employee->job_title,
                'current_job' => $employee->job_title,
                'potential_job' => $employee->potential_job,
                'assessment_score' => $employee->assessment_score,
                'average_score' => $averageScore,
                'score' => $quizScore,
                'category' => $employee->category,
                'category_name' => $assessInfo['category_name'] ?? $employee->category ?? 'General',
                'quiz_title' => $assessInfo['quiz_title'] ?? 'Assessment',
                'evaluated_at' => $assessInfo['evaluated_at'] ?? $employee->updated_at,
                'evaluation_data' => $evalInfo['evaluation_data'] ?? null,
                'strengths' => $employee->strengths,
                'recommendations' => $recommendations,
                'ai_reasoning' => $recommendations['ai_reasoning'] ?? '',
                'match_score' => $recommendations['match_score'] ?? 0,
                'readiness' => $recommendations['readiness'] ?? 'Unknown',
                'succession_readiness' => $successionReadiness,
                'status' => $employee->status,
                'created_at' => $employee->created_at,
                'updated_at' => $employee->updated_at,
            ];
        });

        // Get employee IDs that are already promoted (status = 'promoted')
        $promotedEmployeeIds = DB::connection('succession_planning')
            ->table('promotions')
            ->where('status', 'promoted')
            ->pluck('employee_id')
            ->toArray();

        return view('succession_planning.talent', compact('processedTalentPool', 'promotedEmployeeIds'));
    }

    /**
     * Show promotion form for a specific employee
     */
    public function showPotential($employee_id)
    {
        // Fetch from promotions table
        $talent = DB::connection('succession_planning')
            ->table('promotions')
            ->where('employee_id', $employee_id)
            ->first();

        if (!$talent) {
            return redirect()->route('succession.talent-pool')
                ->with('error', 'Employee not found in talent pool.');
        }

        $recommendations = json_decode($talent->recommendations, true) ?? [];

        $talent = (object) [
            'id' => $talent->id,
            'employee_id' => $talent->employee_id,
            'employee_name' => $talent->employee_name,
            'employee_email' => $talent->employee_email,
            'job_title' => $talent->job_title,
            'potential_job' => $talent->potential_job,
            'assessment_score' => $talent->assessment_score,
            'category' => $talent->category,
            'strengths' => $talent->strengths,
            'recommendations' => $recommendations,
            'ai_reasoning' => $recommendations['ai_reasoning'] ?? '',
            'match_score' => $recommendations['match_score'] ?? 0,
            'readiness' => $recommendations['readiness'] ?? 'Unknown',
            'status' => $talent->status,
        ];

        return view('succession_planning.potential', compact('talent'));
    }

    /**
     * Send employee to promotion process
     */
    public function promoteEmployee(Request $request)
    {
        Log::info('Promotion form submitted', ['request_data' => $request->all()]);

        $validated = $request->validate([
            'employee_id' => 'required|string',
            'potential_job' => 'required|string',
        ]);

        try {
            // Update the existing promotion record
            $updated = DB::connection('succession_planning')
                ->table('promotions')
                ->where('employee_id', $validated['employee_id'])
                ->update([
                    'potential_job' => $validated['potential_job'],
                    'status' => 'approved', // Mark as approved for promotion
                    'updated_at' => now(),
                ]);

            if ($updated) {
                Log::info('Promotion updated successfully', ['employee_id' => $validated['employee_id']]);
                return redirect()->route('succession.talent-pool')
                    ->with('success', 'Employee promotion updated successfully!');
            } else {
                return redirect()->back()
                    ->with('error', 'Employee not found in promotions.');
            }

        } catch (\Exception $e) {
            Log::error('Promotion update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update promotion: ' . $e->getMessage());
        }
    }
}