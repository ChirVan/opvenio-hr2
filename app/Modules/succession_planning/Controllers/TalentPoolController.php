<?php

namespace App\Modules\succession_planning\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TalentPoolController extends Controller
{
    /**
     * Display the talent pool with approved employees
     */
    public function index()
    {
        // Get approved employees from assessment results
        $talentPool = DB::connection('ess')
            ->table('assessment_results as ar')
            ->join('opvenio_hr2.users as u', 'ar.employee_id', '=', 'u.employee_id')
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
                'ar.evaluation_data',
                'ar.evaluated_at',
                'ar.completed_at',
                'ar.score'
            ])
            ->orderBy('ar.evaluated_at', 'desc')
            ->get();

        // Get all employee_ids already sent to promotion
        $promotedEmployeeIds = \App\Modules\succession_planning\Models\Promotion::pluck('employee_id')->toArray();

        // Process evaluation data for display
        $processedTalentPool = $talentPool->map(function ($employee) {
            $evaluationData = json_decode($employee->evaluation_data, true);
            return (object) [
                'id' => $employee->id,
                'employee_id' => $employee->employee_id,
                'employee_name' => $employee->employee_name,
                'employee_email' => $employee->employee_email,
                'quiz_title' => $employee->quiz_title,
                'category_name' => $employee->category_name,
                'evaluated_at' => $employee->evaluated_at,
                'completed_at' => $employee->completed_at,
                'average_score' => $evaluationData['average_score'] ?? 0,
                'evaluation_data' => $evaluationData
            ];
        });

        return view('succession_planning.talent', compact('processedTalentPool', 'promotedEmployeeIds'));
    }

    /**
     * Show promotion form for a specific employee
     */
    public function showPotential($employee_id)
    {
        // Fetch the talent data using the same logic as index, but filter by employee_id
        $talent = DB::connection('ess')
            ->table('assessment_results as ar')
            ->join('opvenio_hr2.users as u', 'ar.employee_id', '=', 'u.employee_id')
            ->leftJoin('learning_management.assessment_assignments as aa', 'ar.assignment_id', '=', 'aa.id')
            ->leftJoin('learning_management.quizzes as q', 'aa.quiz_id', '=', 'q.id')
            ->leftJoin('learning_management.assessment_categories as ac', 'aa.assessment_category_id', '=', 'ac.id')
            ->where('ar.status', 'passed')
            ->where('ar.employee_id', $employee_id)
            ->select([
                'ar.id',
                'ar.employee_id',
                'u.name as employee_name',
                'u.email as employee_email',
                'q.quiz_title',
                'ac.category_name',
                'ar.evaluation_data',
                'ar.evaluated_at',
                'ar.completed_at',
                'ar.score'
            ])
            ->orderBy('ar.evaluated_at', 'desc')
            ->first();

        // Fetch job_title from EmployeeApiService
        $jobTitle = null;
        try {
            $employeeApiService = new \App\Services\EmployeeApiService();
            $employees = $employeeApiService->getEmployees();
            if ($employees && is_array($employees)) {
                foreach ($employees as $emp) {
                    if (isset($emp['employee_id']) && $emp['employee_id'] == $employee_id) {
                        $jobTitle = $emp['job_title'] ?? null;
                        break;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch job title from API', ['error' => $e->getMessage()]);
        }

        // Process evaluation data for display
        if ($talent) {
            $evaluationData = json_decode($talent->evaluation_data, true);
            $talent->average_score = $evaluationData['average_score'] ?? 0;
            $talent->evaluation_data = $evaluationData;
            $talent->job_title = $jobTitle;
        }

        return view('succession_planning.potential', compact('talent'));
    }

    /**
     * Send employee to promotion process
     */
    public function promoteEmployee(Request $request)
    {
        Log::info('Promotion form submitted', ['request_data' => $request->all()]);

        // Validate the request
        $validated = $request->validate([
            'employee_id' => 'required|string',
            'job_title' => 'required|string',
            'potential_job' => 'required|string',
            'strengths' => 'nullable|string',
            'recommendations' => 'nullable|string',
        ]);

        Log::info('Promotion form validated', ['validated' => $validated]);

        try {
            // Get additional employee data
            $employeeData = DB::connection('ess')
                ->table('assessment_results as ar')
                ->join('opvenio_hr2.users as u', 'ar.employee_id', '=', 'u.employee_id')
                ->leftJoin('learning_management.assessment_assignments as aa', 'ar.assignment_id', '=', 'aa.id')
                ->leftJoin('learning_management.assessment_categories as ac', 'aa.assessment_category_id', '=', 'ac.id')
                ->where('ar.employee_id', $validated['employee_id'])
                ->where('ar.status', 'passed')
                ->select([
                    'u.name as employee_name',
                    'u.email as employee_email',
                    'ar.score',
                    'ac.category_name'
                ])
                ->first();

            if (!$employeeData) {
                Log::warning('Employee data not found for promotion', ['employee_id' => $validated['employee_id']]);
                return redirect()->back()->with('error', 'Employee data not found.');
            }

            // Create promotion record
            $promotion = \App\Modules\succession_planning\Models\Promotion::create([
                'employee_id' => $validated['employee_id'],
                'employee_name' => $employeeData->employee_name,
                'employee_email' => $employeeData->employee_email,
                'job_title' => $validated['job_title'],
                'potential_job' => $validated['potential_job'],
                'assessment_score' => $employeeData->score ?? null,
                'category' => $employeeData->category_name ?? null,
                'strengths' => $validated['strengths'] ?? null,
                'recommendations' => $validated['recommendations'] ?? null,
                'status' => 'pending',
            ]);

            Log::info('Promotion record created successfully', ['promotion_id' => $promotion->id]);

            return redirect()->route('succession.talent-pool')
                ->with('success', 'Employee successfully sent for promotion consideration!');

        } catch (\Exception $e) {
            Log::error('Promotion save failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to save promotion: ' . $e->getMessage());
        }
    }
}