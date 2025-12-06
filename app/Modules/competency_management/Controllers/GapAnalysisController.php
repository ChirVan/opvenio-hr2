<?php

namespace App\Modules\competency_management\Controllers;

use App\Modules\competency_management\Models\GapAnalysis;
use App\Modules\competency_management\Requests\StoreGapAnalysisRequest;
use App\Services\EmployeeApiService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class GapAnalysisController extends Controller
{
    protected $employeeApiService;

    public function __construct(EmployeeApiService $employeeApiService)
    {
        $this->employeeApiService = $employeeApiService;
    }

    public function index(Request $request)
    {
        // Clear cache if refresh is requested
        if ($request->has('refresh')) {
            $this->employeeApiService->clearCache();
        }

        // Get gap analyses from local database
        $gapAnalyses = DB::connection('competency_management')
            ->table('gap_analyses')
            ->join('competencies', 'gap_analyses.competency_id', '=', 'competencies.id')
            ->select(
                'gap_analyses.*',
                'competencies.competency_name'
            )
            ->get();

        // Get employees from external API
        $externalEmployees = $this->employeeApiService->getEmployees();
        
        // Create a lookup array for employees
        $employeeLookup = [];
        if ($externalEmployees) {
            foreach ($externalEmployees as $employee) {
                $employeeLookup[$employee['id']] = $employee;
            }
        }

        // Merge gap analysis data with employee data
        $gapAnalyses = $gapAnalyses->map(function ($gap) use ($employeeLookup) {
            $employee = $employeeLookup[$gap->employee_id] ?? null;
            
            if ($employee) {
                $gap->employee_full_name = $employee['full_name'];
                $gap->employee_email = $employee['email'];
                $gap->employment_status = $employee['employment_status'];
                $gap->job_title = $employee['job_title'];
                $gap->employee_id_display = $employee['employee_id'];
            } else {
                $gap->employee_full_name = 'Employee not found';
                $gap->employee_email = '';
                $gap->employment_status = '';
                $gap->job_title = '';
                $gap->employee_id_display = $gap->employee_id;
            }
            
            return $gap;
        });

        return view('competency_management.gap_analysis', compact('gapAnalyses'));
    }

    public function create(Request $request)
    {
        // Clear cache if refresh is requested
        if ($request->has('refresh')) {
            $this->employeeApiService->clearCache();
        }

        // Get employees from external API instead of local database
        $externalEmployees = $this->employeeApiService->getEmployees();
        $employees = collect($externalEmployees ?? [])->map(function ($employee) {
            return (object) $employee;
        });
        
        $roleMappings = \App\Modules\competency_management\Models\RoleMapping::with(['framework', 'competency'])->get();
        $competencies = \App\Modules\competency_management\Models\Competency::all();
        return view('competency_management.GapCRUD.create', compact('employees', 'roleMappings', 'competencies'));
    }

    public function store(StoreGapAnalysisRequest $request)
    {
        $validatedData = $request->validated();
        
        // Note: employee_id now stores the external API employee ID (not local database ID)
        // Get all employees to find the selected one (more reliable than single employee API call)
        $allEmployees = $this->employeeApiService->getEmployees();
        $employee = collect($allEmployees)->firstWhere('id', $validatedData['employee_id']);
        
        if (!$employee) {
            return back()->withErrors(['employee_id' => 'Selected employee not found in the external system.'])->withInput();
        }
        
        // Create the gap analysis record with external employee ID
        $gapAnalysis = GapAnalysis::create($validatedData);
        
        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user() ? Auth::user()->name : 'System',
            'activity' => 'create_gap_analysis',
            'details' => 'Created gap analysis for employee: ' . $employee['full_name'] . ' (' . $employee['employee_id'] . ')',
            'status' => 'Success',
        ]);
        
        return redirect()->route('competency.gapanalysis')->with('success', 
            "Gap analysis record created successfully for employee: {$employee['full_name']} ({$employee['employee_id']})");
    }

    public function show($id)
    {
        $gapAnalysis = GapAnalysis::findOrFail($id);
        return view('competency_management.GapCRUD.show', compact('gapAnalysis'));
    }

    public function edit($id)
    {
        $gapAnalysis = GapAnalysis::findOrFail($id);
        return view('competency_management.GapCRUD.edit', compact('gapAnalysis'));
    }

    public function update(StoreGapAnalysisRequest $request, $id)
    {
        $gapAnalysis = GapAnalysis::findOrFail($id);
        $gapAnalysis->update($request->validated());
        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user() ? Auth::user()->name : 'System',
            'activity' => 'update_gap_analysis',
            'details' => 'Updated gap analysis for employee ID: ' . $gapAnalysis->employee_id,
            'status' => 'Success',
        ]);
        return redirect()->route('competency.gapanalysis')->with('success', 'Gap analysis record updated.');
    }

    public function destroy($id)
    {
        $gapAnalysis = GapAnalysis::findOrFail($id);
        $employeeId = $gapAnalysis->employee_id;
        $gapAnalysis->delete();
        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user() ? Auth::user()->name : 'System',
            'activity' => 'delete_gap_analysis',
            'details' => 'Deleted gap analysis for employee ID: ' . $employeeId,
            'status' => 'Success',
        ]);
        return redirect()->route('competency.gapanalysis')->with('success', 'Gap analysis record deleted.');
    }

    /**
     * Show skill gap analysis with real assessment data
     * 
     * DATA BASIS EXPLANATION:
     * 1. ONLY fetches employees whose LATEST assessment has status = 'passed' (fully evaluated)
     * 2. Prevents showing employees with older "passed" assessments but newer "pending" ones
     * 3. Must have evaluation_data (JSON containing competency ratings from Step 2 evaluation)
     * 4. Must have evaluated_by (confirmed by HR/Admin)
     * 5. Competency ratings come from the 5-point evaluation form:
     *    - competency_1: "Skill and proficiency in carrying out assignment"
     *    - competency_2: "Possesses skills and knowledge to perform job effectively"  
     *    - competency_3: "Skill at planning, organizing and prioritizing workload"
     *    - competency_4: "Holds self accountable for assigned responsibilities"
     *    - competency_5: "Proficiency at improving work methods and procedures"
     * 6. Rating scale: exceptional(5), highly_effective(4), proficient(3), inconsistent(2), unsatisfactory(1)
     * 
     * IMPORTANT: Uses subquery to get only the most recent assessment per employee to ensure
     * accuracy with current evaluation status (not historical ones)
     * 
     * @param string|null $employee Optional employee ID to filter results for specific employee
     */
    public function skillGapAnalysis($employee = null)
    {
        // Debug logging for specific employee request
        \Log::info('Skill Gap Analysis Called:', [
            'requested_employee' => $employee,
            'route_name' => request()->route()->getName(),
            'url' => request()->url()
        ]);

        // Get ONLY employees whose LATEST assessment is fully evaluated
        // This prevents showing employees who have older "passed" assessments but newer "pending" ones
        $latestAssessmentSubquery = DB::connection('ess')
            ->table('assessment_results')
            ->select('employee_id', DB::raw('MAX(completed_at) as latest_completed'))
            ->groupBy('employee_id');

        $assessedEmployees = DB::connection('ess')
            ->table('assessment_results as ar')
            ->joinSub($latestAssessmentSubquery, 'latest', function ($join) {
                $join->on('ar.employee_id', '=', 'latest.employee_id')
                     ->on('ar.completed_at', '=', 'latest.latest_completed');
            })
            ->join('opvenio_hr2.users as u', 'ar.employee_id', '=', 'u.employee_id')
            ->leftJoin('learning_management.assessment_assignments as aa', 'ar.assignment_id', '=', 'aa.id')
            ->leftJoin('learning_management.quizzes as q', 'aa.quiz_id', '=', 'q.id')
            ->leftJoin('learning_management.assessment_categories as ac', 'aa.assessment_category_id', '=', 'ac.id')
            ->where('ar.status', 'passed') // Only passed/approved employees
            ->whereNotNull('ar.evaluation_data') // Must have evaluation data
            ->whereNotNull('ar.evaluated_by') // Must be evaluated by someone
            ->when($employee, function ($query, $employee) {
                return $query->where('ar.employee_id', $employee);
            })
            ->select([
                'ar.id',
                'ar.employee_id',
                'u.name as employee_name',
                'u.email as employee_email',
                'q.quiz_title',
                'ac.category_name',
                'ar.evaluation_data',
                'ar.score',
                'ar.status',
                'ar.evaluated_at'
            ])
            ->orderBy('ar.evaluated_at', 'desc')
            ->get();

        // Get external employee data for job titles
        $externalEmployees = $this->employeeApiService->getEmployees();
        $employeeLookup = [];
        if ($externalEmployees) {
            foreach ($externalEmployees as $employee) {
                $employeeLookup[$employee['employee_id']] = $employee;
            }
        }

        // Process assessment data to create skill gap analysis
        $employeeGapAnalysis = [];
        
        foreach ($assessedEmployees as $assessment) {
            $employeeId = $assessment->employee_id;
            $externalEmployee = $employeeLookup[$employeeId] ?? null;
            
            // Decode evaluation data
            $evaluationData = json_decode($assessment->evaluation_data, true);
            
            if (!isset($employeeGapAnalysis[$employeeId])) {
                $employeeGapAnalysis[$employeeId] = [
                    'employee_id' => $employeeId,
                    'employee_name' => $assessment->employee_name,
                    'employee_email' => $assessment->employee_email,
                    'job_title' => $externalEmployee['job_title'] ?? 'Unknown',
                    'overall_score' => 0,
                    'status' => 'Developing',
                    'competencies' => [],
                    'assessment_count' => 0
                ];
            }

            // Convert competency ratings to numeric scores
            $scoreMapping = [
                'exceptional' => 5,
                'highly_effective' => 4,
                'proficient' => 3,
                'inconsistent' => 2,
                'unsatisfactory' => 1
            ];

            // Use the EXACT competency labels from the evaluation form (evaluate_step2.blade.php)
            $competencies = [
                'assignment_skills' => [
                    'label' => 'Assignment Skills',
                    'description' => 'Skill and proficiency in carrying out assignment',
                    'current' => $scoreMapping[$evaluationData['competency_1'] ?? 'inconsistent'],
                    'required' => 4.0
                ],
                'job_knowledge' => [
                    'label' => 'Job Knowledge', 
                    'description' => 'Possesses skills and knowledge to perform job effectively',
                    'current' => $scoreMapping[$evaluationData['competency_2'] ?? 'inconsistent'],
                    'required' => 4.0
                ],
                'planning_organizing' => [
                    'label' => 'Planning & Organizing',
                    'description' => 'Skill at planning, organizing and prioritizing workload', 
                    'current' => $scoreMapping[$evaluationData['competency_3'] ?? 'inconsistent'],
                    'required' => 4.5
                ],
                'accountability' => [
                    'label' => 'Accountability',
                    'description' => 'Holds self accountable for assigned responsibilities; sees task through to completion, in a timely manner',
                    'current' => $scoreMapping[$evaluationData['competency_4'] ?? 'inconsistent'],
                    'required' => 4.0
                ],
                'efficiency_improvement' => [
                    'label' => 'Process Improvement',
                    'description' => 'Proficiency at improving work methods and procedures as a means toward greater efficiency',
                    'current' => $scoreMapping[$evaluationData['competency_5'] ?? 'inconsistent'],
                    'required' => 4.0
                ]
            ];

            // Update employee competencies (take latest assessment)
            $employeeGapAnalysis[$employeeId]['competencies'] = $competencies;
            $employeeGapAnalysis[$employeeId]['assessment_count']++;
            
            // Calculate overall score
            $totalCurrent = array_sum(array_column($competencies, 'current'));
            $overallScore = ($totalCurrent / 25) * 100; // Convert to percentage (5 competencies * 5 max score)
            
            $employeeGapAnalysis[$employeeId]['overall_score'] = round($overallScore);
            
            // Determine status based on score
            if ($overallScore >= 90) {
                $employeeGapAnalysis[$employeeId]['status'] = 'High Performer';
            } elseif ($overallScore >= 80) {
                $employeeGapAnalysis[$employeeId]['status'] = 'Pipeline Ready';
            } else {
                $employeeGapAnalysis[$employeeId]['status'] = 'Developing';
            }
        }

        // Convert to collection and take top employees
        $employeeGapAnalysis = collect(array_values($employeeGapAnalysis))->take(20);
        
        // Get skill gap assignments for all employees
        $employeeIds = $employeeGapAnalysis->pluck('employee_id')->toArray();
        $skillGapAssignments = DB::connection('competency_management')
            ->table('skill_gap_assignments')
            ->whereIn('employee_id', $employeeIds)
            ->whereIn('status', ['pending', 'in_progress'])
            ->select('employee_id', 'competency_key', 'action_type', 'notes', 'status', 'assigned_at', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('employee_id');
        
        // Add skill gap assignments to each employee
        $employeeGapAnalysis = $employeeGapAnalysis->map(function($employee) use ($skillGapAssignments) {
            $employee['skill_gap_assignments'] = $skillGapAssignments->get($employee['employee_id'], collect())->map(function($gap) {
                return (array) $gap;
            })->toArray();
            $employee['has_active_gaps'] = count($employee['skill_gap_assignments']) > 0;
            return $employee;
        });

        // Debug information (can be removed in production)
        \Log::info('Skill Gap Analysis Data:', [
            'requested_employee' => $employee,
            'total_assessments_found' => $assessedEmployees->count(),
            'employees_processed' => $employeeGapAnalysis->count(),
            'first_employee_sample' => $employeeGapAnalysis->first()
        ]);

        return view('competency_management.rolemapping', compact('employeeGapAnalysis'));
    }

    /**
     * Assign skill gap action to employee
     */
    public function assignSkillGap(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required',
            'competency_key' => 'required|string',
            'action_type' => 'required|in:critical,training,mentoring',
            'notes' => 'nullable|string'
        ]);

        try {
            // Get external employee data
            $allEmployees = $this->employeeApiService->getEmployees();
            $employee = collect($allEmployees)->firstWhere('employee_id', $validated['employee_id']);
            
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found'
                ], 404);
            }

            // Store the skill gap assignment
            DB::connection('competency_management')->table('skill_gap_assignments')->insert([
                'employee_id' => $validated['employee_id'],
                'employee_name' => $employee['full_name'],
                'job_title' => $employee['job_title'],
                'competency_key' => $validated['competency_key'],
                'action_type' => $validated['action_type'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending',
                'assigned_by' => Auth::id(),
                'assigned_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user() ? Auth::user()->name : 'System',
                'activity' => 'assign_skill_gap',
                'details' => "Assigned skill gap action for {$employee['full_name']} - Competency: {$validated['competency_key']}, Action: {$validated['action_type']}",
                'status' => 'Success',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Skill gap action assigned successfully',
                'data' => [
                    'employee' => $employee['full_name'],
                    'competency' => $validated['competency_key'],
                    'action' => $validated['action_type']
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error assigning skill gap: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign skill gap: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create development plan for employee
     */
    public function createDevelopmentPlan(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required',
            'plan_title' => 'required|string|max:255',
            'objectives' => 'required|array',
            'objectives.*' => 'string',
            'timeline' => 'required|string',
            'resources' => 'nullable|array',
            'resources.*' => 'string'
        ]);

        try {
            // Get external employee data
            $allEmployees = $this->employeeApiService->getEmployees();
            $employee = collect($allEmployees)->firstWhere('employee_id', $validated['employee_id']);
            
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found'
                ], 404);
            }

            // Store the development plan
            $planId = DB::connection('competency_management')->table('development_plans')->insertGetId([
                'employee_id' => $validated['employee_id'],
                'employee_name' => $employee['full_name'],
                'job_title' => $employee['job_title'],
                'plan_title' => $validated['plan_title'],
                'objectives' => json_encode($validated['objectives']),
                'timeline' => $validated['timeline'],
                'resources' => json_encode($validated['resources'] ?? []),
                'status' => 'active',
                'created_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user() ? Auth::user()->name : 'System',
                'activity' => 'create_development_plan',
                'details' => "Created development plan for {$employee['full_name']}: {$validated['plan_title']}",
                'status' => 'Success',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Development plan created successfully',
                'data' => [
                    'plan_id' => $planId,
                    'employee' => $employee['full_name'],
                    'title' => $validated['plan_title']
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating development plan: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create development plan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Schedule assessment retake
     */
    public function scheduleAssessment(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required',
            'assessment_type' => 'required|in:individual,comprehensive,practical,feedback',
            'scheduled_date' => 'required|date|after:today',
            'notes' => 'nullable|string'
        ]);

        try {
            // Get external employee data
            $allEmployees = $this->employeeApiService->getEmployees();
            $employee = collect($allEmployees)->firstWhere('employee_id', $validated['employee_id']);
            
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found'
                ], 404);
            }

            // Store the assessment schedule
            $scheduleId = DB::connection('competency_management')->table('assessment_schedules')->insertGetId([
                'employee_id' => $validated['employee_id'],
                'employee_name' => $employee['full_name'],
                'job_title' => $employee['job_title'],
                'assessment_type' => $validated['assessment_type'],
                'scheduled_date' => $validated['scheduled_date'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'scheduled',
                'scheduled_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user() ? Auth::user()->name : 'System',
                'activity' => 'schedule_assessment',
                'details' => "Scheduled {$validated['assessment_type']} assessment for {$employee['full_name']} on {$validated['scheduled_date']}",
                'status' => 'Success',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Assessment scheduled successfully',
                'data' => [
                    'schedule_id' => $scheduleId,
                    'employee' => $employee['full_name'],
                    'date' => $validated['scheduled_date'],
                    'type' => $validated['assessment_type']
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error scheduling assessment: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to schedule assessment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export gap analysis report
     */
    public function exportGapAnalysis($employee)
    {
        // This method would generate a PDF/Excel export
        // For now, return a JSON response indicating the functionality
        return response()->json([
            'success' => true,
            'message' => 'Export functionality - to be implemented with PDF/Excel generation',
            'employee_id' => $employee
        ]);
    }
}