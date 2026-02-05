<?php

namespace App\Modules\training_management\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TrainingEvaluationController extends Controller
{
    /**
     * Display the training evaluation index.
     * Shows employees who have PASSED the Step 1 evaluation (quiz review) and are ready for hands-on evaluation.
     */
    public function index(Request $request)
    {
        // Get assessment results from ESS database that have been PASSED in Step 1
        // Only show employees who passed the quiz evaluation and need hands-on evaluation
        $resultsQuery = DB::connection('ess')
            ->table('assessment_results')
            ->whereIn('status', ['completed', 'passed']) // Handle both status values
            ->where('status', '!=', 'retried')
            ->where('evaluation_status', 'passed') // Only passed Step 1 evaluations
            ->orderBy('completed_at', 'desc');

        // Filter by hands-on evaluation status
        if ($request->has('filter') && $request->filter) {
            if ($request->filter == 'pending') {
                // Pending hands-on evaluation (no evaluation_data yet)
                $resultsQuery->where(function($q) {
                    $q->whereNull('evaluation_data')
                      ->orWhere('evaluation_data', '');
                });
            } elseif ($request->filter == 'evaluated') {
                // Already completed hands-on evaluation
                $resultsQuery->whereNotNull('evaluation_data')
                             ->where('evaluation_data', '!=', '');
            }
        }

        $assessmentResults = $resultsQuery->get();

        // Enrich with assignment data from learning_management
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
                // Keep the original employee_id from assessment_results (EMP format)
                // Only get employee_name and other details from assignments
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

        // Search by employee name
        if ($request->has('search') && $request->search) {
            $searchTerm = strtolower($request->search);
            $enrichedResults = $enrichedResults->filter(function ($result) use ($searchTerm) {
                return str_contains(strtolower($result->employee_name ?? ''), $searchTerm);
            });
        }

        // Group by employee
        $groupedResults = $enrichedResults->groupBy('employee_id')->map(function($employeeResults) {
            $first = $employeeResults->first();
            
            // Count how many have completed hands-on evaluation (have evaluation_data filled)
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

        // Stats
        $stats = [
            'total_pending' => $groupedResults->filter(fn($e) => !$e->all_evaluated)->count(),
            'total_evaluated' => $groupedResults->filter(fn($e) => $e->all_evaluated)->count(),
            'total_employees' => $groupedResults->count(),
        ];

        return view('training_management.evaluation', compact('groupedResults', 'stats'));
    }

    /**
     * Show the hands-on evaluation form for a specific employee.
     * Only shows employees who passed the Step 1 evaluation.
     */
    public function evaluate($employeeId, Request $request)
    {
        // Get all completed and PASSED assessments for this employee from ESS
        $assessmentResults = DB::connection('ess')
            ->table('assessment_results')
            ->whereIn('status', ['completed', 'passed']) // Handle both status values
            ->where('status', '!=', 'retried')
            ->where('evaluation_status', 'passed') // Only passed Step 1 evaluations
            ->orderBy('completed_at', 'desc')
            ->get();

        // Enrich with assignment data and filter by employee
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
                // Keep the original employee_id from assessment_results (EMP format)
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

        // Build evaluation data
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

        // Get all result IDs for this employee
        if ($request->result_ids) {
            $resultIds = explode(',', $request->result_ids);
        } else {
            // Get result IDs by looking up assignments first
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

        // Update all results with evaluation data in ESS database
        DB::connection('ess')
            ->table('assessment_results')
            ->whereIn('id', $resultIds)
            ->update([
                'evaluation_data' => json_encode($evaluationData),
                'evaluation_status' => $request->decision,
                'updated_at' => now(),
            ]);

        $message = $request->decision === 'passed' 
            ? 'Employee has been approved successfully!' 
            : 'Employee evaluation marked as failed.';

        return redirect()->route('training.evaluation.index')
            ->with('success', $message);
    }
}
