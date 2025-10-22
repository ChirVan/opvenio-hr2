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
}