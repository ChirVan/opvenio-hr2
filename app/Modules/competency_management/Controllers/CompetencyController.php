<?php

namespace App\Modules\competency_management\Controllers;

use App\Modules\competency_management\Models\Competency;
use App\Modules\competency_management\Models\CompetencyFramework;
use App\Modules\competency_management\Requests\StoreCompetencyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class CompetencyController extends Controller
{
    public function index(Request $request)
    {
        $query = Competency::with('framework');

        // Search functionality
        if ($request->filled('competency_search')) {
            $searchTerm = $request->competency_search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('competency_name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('framework', function($q) use ($request) {
                $q->where('framework_name', $request->category);
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sorting
        if ($request->sort_name === 'az') {
            $query->orderBy('competency_name', 'asc');
        } elseif ($request->sort_name === 'za') {
            $query->orderBy('competency_name', 'desc');
        } else {
            $query->orderBy('id', 'asc');
        }

        // CHANGE FROM get() TO paginate()
        $competencies = $query->paginate(10);

        return view('competency_management.frameworks', compact('competencies'));
    }

    public function create(Request $request)
    {
        $frameworks = CompetencyFramework::active()->get();
        
        // Fetch all competencies for assignment
        $competencies = Competency::where('status', 'active')
            ->orderBy('competency_name', 'asc')
            ->get();
        
        // Fetch employees from the employeeApiService or database
        try {
            $employeeService = app(\App\Services\EmployeeApiService::class);
            $employees = $employeeService->getEmployees() ?? [];
        } catch (\Exception $e) {
            // Fallback to empty array if API fails
            $employees = [];
        }
        
        // Get selected employee if provided
        $selectedEmployee = null;
        $employeeSkillGaps = [];
        
        if ($request->has('employee_id')) {
            $employeeId = $request->get('employee_id');
            
            // Find the employee from the list
            $selectedEmployee = collect($employees)->firstWhere('employee_id', $employeeId);
            
            // Fetch employee's current skill gap assignments
            $employeeSkillGaps = \DB::connection('competency_management')
                ->table('skill_gap_assignments')
                ->where('employee_id', $employeeId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($gap) {
                    $competencyLabels = [
                        'assignment_skills' => 'Assignment Skills',
                        'job_knowledge' => 'Job Knowledge',
                        'planning_organizing' => 'Planning & Organizing',
                        'accountability' => 'Accountability',
                        'efficiency_improvement' => 'Process Improvement'
                    ];
                    
                    return [
                        'competency_key' => $gap->competency_key,
                        'competency_label' => $competencyLabels[$gap->competency_key] ?? $gap->competency_key,
                        'action_type' => $gap->action_type,
                        'notes' => $gap->notes,
                        'status' => $gap->status,
                        'created_at' => $gap->created_at,
                    ];
                })
                ->toArray();
        }
        
        return view('competency_management.CompetencyCRUD.create', compact('frameworks', 'competencies', 'employees', 'selectedEmployee', 'employeeSkillGaps'));
    }

    public function store(StoreCompetencyRequest $request)
    {
        try {
            // Generate next competency_id (CMP-001, CMP-002, ...)
            $last = \App\Modules\competency_management\Models\Competency::orderByDesc('id')->first();
            $nextNumber = $last && $last->competency_id ? ((int) preg_replace('/\D/', '', $last->competency_id)) + 1 : 1;
            $nextCompetencyId = 'CMP-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            $data = $request->validated();
            $data['competency_id'] = $nextCompetencyId;

            $competency = Competency::create($data);

            // Log activity (CREATE)
            \App\Models\ActivityLog::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name ?? 'Unknown',
                'activity' => 'Create',
                'details' => 'Created competency: ' . $competency->competency_name,
                'status' => 'Success',
                'created_at' => now(),
            ]);

            return redirect()
                ->route('competency.frameworks')
                ->with('success', 'Competency created successfully!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the competency.');
        }
    }

    public function show(Competency $competency)
    {
        $competency->load('framework');
        return view('competency_management.CompetencyCRUD.view', compact('competency'));
    }

    public function edit(Competency $competency)
    {
        $frameworks = CompetencyFramework::active()->get();
        return view('competency_management.CompetencyCRUD.edit', compact('competency', 'frameworks'));
    }

    public function update(Request $request, Competency $competency)
    {
        try {
            $competency->update($request->all());

            // Log activity (UPDATE)
            \App\Models\ActivityLog::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name ?? 'Unknown',
                'activity' => 'Edit',
                'details' => 'Updated competency: ' . $competency->competency_name,
                'status' => 'Success',
                'created_at' => now(),
            ]);

            return redirect()
                ->route('competency.frameworks')
                ->with('success', 'Competency updated successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred while updating the competency.');
        }
    }

    public function destroy(Competency $competency)
    {
        try {
            $name = $competency->competency_name;
            $competency->delete();

            // Log activity (DELETE)
            \App\Models\ActivityLog::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name ?? 'Unknown',
                'activity' => 'Delete',
                'details' => 'Deleted competency: ' . $name,
                'status' => 'Success',
                'created_at' => now(),
            ]);

            return redirect()
                ->route('competency.frameworks')
                ->with('success', 'Competency deleted successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error deleting competency.');
        }
    }
}