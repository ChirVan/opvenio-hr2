<?php

namespace App\Modules\training_management\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\training_management\Models\TrainingAssignment;
use App\Modules\training_management\Models\TrainingAssignmentEmployee;
use App\Modules\training_management\Models\TrainingCatalog;
use App\Modules\training_management\Models\TrainingMaterial;
use App\Modules\competency_management\Models\GapAnalysis;
use App\Services\EmployeeApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class TrainingAssignmentController extends Controller
{
    protected $employeeApiService;

    public function __construct(EmployeeApiService $employeeApiService)
    {
        $this->employeeApiService = $employeeApiService;
    }

    /**
     * AJAX: Get published materials for a catalog
     */
    public function getMaterials($catalog)
    {
        try {
            Log::info("Fetching materials for catalog: " . $catalog);

            $materials = TrainingMaterial::where('training_catalog_id', $catalog)
                ->where('status', 'published')
                ->get(['id', 'lesson_title']);

            Log::info("Found materials: " . $materials->count());
            return response()->json($materials);

        } catch (\Exception $e) {
            Log::error("Error fetching materials: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * AJAX: Get employees from gap analysis
     */
    public function getGapAnalysisEmployees()
    {
        try {
            Log::info("Fetching employees from gap_analyses with proper relationships");

            $gapAnalyses = GapAnalysis::with(['competency'])
                ->join('hr2_competency_managements.employees', 'gap_analyses.employee_id', '=', 'employees.id')
                ->leftJoin('hr2_competency_managements.competencies', 'gap_analyses.competency_id', '=', 'competencies.id')
                ->select([
                    'gap_analyses.id',
                    'gap_analyses.employee_id',
                    'employees.firstname as employee_firstname',
                    'employees.lastname as employee_lastname',
                    'employees.job_role',
                    'gap_analyses.framework',
                    'gap_analyses.proficiency_level',
                    'gap_analyses.notes',
                    'gap_analyses.competency_id',
                    'competencies.competency_name'
                ])
                ->distinct('gap_analyses.employee_id')
                ->orderBy('employees.lastname')
                ->orderBy('employees.firstname')
                ->get();

            $employees = $gapAnalyses->map(function ($gap) {
                return [
                    'id' => $gap->employee_id,
                    'employee_firstname' => $gap->employee_firstname,
                    'employee_lastname' => $gap->employee_lastname,
                    'job_role' => $gap->job_role,
                    'competency_name' => $gap->competency_name ?: 'No Competency',
                    'framework' => $gap->framework ?: 'No Framework',
                    'proficiency_level' => $gap->proficiency_level
                ];
            });

            Log::info("Found " . $employees->count() . " unique employees in gap analysis");
            return response()->json($employees);

        } catch (\Exception $e) {
            Log::error("Model query failed: " . $e->getMessage());
            try {
                $employees = DB::connection('competency_management')
                    ->table('gap_analyses')
                    ->join('employees', 'gap_analyses.employee_id', '=', 'employees.id')
                    ->leftJoin('competencies', 'gap_analyses.competency_id', '=', 'competencies.id')
                    ->select([
                        'gap_analyses.employee_id as id',
                        'employees.firstname as employee_firstname',
                        'employees.lastname as employee_lastname',
                        'employees.job_role',
                        'gap_analyses.framework',
                        'gap_analyses.proficiency_level',
                        'competencies.competency_name'
                    ])
                    ->distinct()
                    ->orderBy('employees.lastname')
                    ->orderBy('employees.firstname')
                    ->get()
                    ->map(function ($employee) {
                        return [
                            'id' => $employee->id,
                            'employee_firstname' => $employee->employee_firstname,
                            'employee_lastname' => $employee->employee_lastname,
                            'job_role' => $employee->job_role,
                            'competency_name' => $employee->competency_name ?: 'No Competency',
                            'framework' => $employee->framework ?: 'No Framework',
                            'proficiency_level' => $employee->proficiency_level
                        ];
                    });

                Log::info("Fallback query found " . $employees->count() . " employees");
                return response()->json($employees);

            } catch (\Exception $e2) {
                Log::error("Fallback query also failed: " . $e2->getMessage());
                return response()->json([]);
            }
        }
    }

    /**
     * Helper method to fetch employee data for given employee IDs
     */
    private function getEmployeesData(array $employeeIds): array
    {
        $employeesData = [];

        if (!empty($employeeIds)) {
            $allEmployees = $this->employeeApiService->getEmployees();
            if ($allEmployees) {
                foreach ($allEmployees as $employee) {
                    if (in_array($employee['id'], $employeeIds)) {
                        $employeesData[$employee['id']] = $employee;
                    }
                }
            }
        }

        return $employeesData;
    }

    /**
     * Get employees from external API for assignment form
     */
    public function getApiEmployees(Request $request)
    {
        try {
            if ($request->has('refresh')) {
                $this->employeeApiService->clearCache();
            }

            $employees = $this->employeeApiService->getEmployees();

            if ($employees === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to fetch employees from external API',
                    'employees' => []
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Employees loaded successfully',
                'count' => count($employees),
                'employees' => $employees
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching employees for training assignment: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error loading employees',
                'employees' => []
            ], 500);
        }
    }

    /**
     * Display a listing of training assignments
     */
    public function index()
    {
        $assignments = TrainingAssignment::with([
            'trainingCatalog',
            'creator',
            'assignmentEmployees',
            'trainingMaterials'
        ])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        $employeeIds = $assignments->flatMap(function ($assignment) {
            return $assignment->assignmentEmployees->pluck('employee_id');
        })->unique()->toArray();

        $employeesData = $this->getEmployeesData($employeeIds);

        return view('training_management.assign', compact('assignments', 'employeesData'));
    }

    /**
     * Show the form for creating a new training assignment
     */
    public function create()
    {
        $trainingCatalogs = TrainingCatalog::with('framework')
            ->where('is_active', true)
            ->orderBy('title')
            ->get();

        return view('training_management.assignCRUD.create', compact('trainingCatalogs'));
    }

    /**
     * Store a newly created training assignment
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'assignment_title' => 'required|string|max:255',
                'training_catalog_id' => 'required|exists:training_management.training_catalogs,id',
                'training_materials' => 'required|array|min:1',
                'training_materials.*' => 'exists:training_management.training_materials,id',
                'employee_id' => 'required|integer|min:1',
                'job_title' => 'nullable|string|max:255',
                'priority' => 'required|in:low,medium,high,urgent',
                'assignment_type' => 'required|in:mandatory,optional,development',
                'start_date' => 'required|date|after_or_equal:today',
                'due_date' => 'required|date|after:start_date',
                'instructions' => 'nullable|string|max:5000',
                'action' => 'required|in:draft,assign'
            ]);

            $allEmployees = $this->employeeApiService->getEmployees();
            $employee = collect($allEmployees)->firstWhere('id', $validated['employee_id']);

            if (!$employee) {
                return back()->withErrors([
                    'employee_id' => 'Selected employee not found in the external system.'
                ])->withInput();
            }

            DB::beginTransaction();

            $status = $validated['action'] === 'assign' ? 'active' : 'draft';

            $assignment = TrainingAssignment::create([
                'assignment_title' => $validated['assignment_title'],
                'training_catalog_id' => $validated['training_catalog_id'],
                'priority' => $validated['priority'],
                'assignment_type' => $validated['assignment_type'],
                'start_date' => $validated['start_date'],
                'due_date' => $validated['due_date'],
                'instructions' => $validated['instructions'],
                'status' => $status,
                'created_by' => Auth::id()
            ]);

            $materialData = [];
            foreach ($validated['training_materials'] as $index => $materialId) {
                $materialData[$materialId] = [
                    'is_required' => true,
                    'order_sequence' => $index + 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            $assignment->trainingMaterials()->attach($materialData);

            TrainingAssignmentEmployee::create([
                'training_assignment_id' => $assignment->id,
                'employee_id' => $validated['employee_id'],
                'status' => 'assigned',
                'assigned_at' => now()
            ]);

            DB::commit();

            $message = ($status === 'active'
                ? 'Training assignment created and activated successfully'
                : 'Training assignment saved as draft successfully')
                . " for employee: {$employee['full_name']} ({$employee['employee_id']})";

            Log::info('Training assignment created', [
                'assignment_id' => $assignment->id,
                'employee_id' => $validated['employee_id'],
                'employee_name' => $employee['full_name'],
                'status' => $status,
                'created_by' => Auth::id()
            ]);

            return redirect()->route('training.assign.index')->with('success', $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create training assignment', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            return back()->withErrors(['error' => 'Failed to create training assignment. Please try again.'])->withInput();
        }
    }

    /**
     * Display the specified training assignment
     */
    public function show(TrainingAssignment $assignment)
    {
        $assignment->load([
            'trainingCatalog.framework',
            'trainingMaterials',
            'assignmentEmployees',
            'creator'
        ]);

        $employeeIds = $assignment->assignmentEmployees->pluck('employee_id')->toArray();
        $employeesData = $this->getEmployeesData($employeeIds);

        return view('training_management.assign', compact('assignment', 'employeesData'));
    }

    /**
     * Show the form for editing the specified training assignment
     */
    public function edit(TrainingAssignment $assignment)
    {
        $trainingCatalogs = TrainingCatalog::with('framework')
            ->where('is_active', true)
            ->orderBy('title')
            ->get();

        $assignment->load(['trainingMaterials', 'assignmentEmployees']);

        return view('training_management.assignCRUD.create', compact('assignment', 'trainingCatalogs'));
    }

    /**
     * Update the specified training assignment
     */
    public function update(Request $request, TrainingAssignment $assignment)
    {
        try {
            $validated = $request->validate([
                'assignment_title' => 'required|string|max:255',
                'training_catalog_id' => 'required|exists:training_management.training_catalogs,id',
                'training_materials' => 'required|array|min:1',
                'training_materials.*' => 'exists:training_management.training_materials,id',
                'priority' => 'required|in:low,medium,high,urgent',
                'assignment_type' => 'required|in:mandatory,optional,development',
                'start_date' => 'required|date',
                'due_date' => 'required|date|after:start_date',
                'instructions' => 'nullable|string|max:5000',
                'status' => ['sometimes', Rule::in(['draft', 'active', 'completed', 'cancelled'])]
            ]);

            DB::beginTransaction();

            $assignment->update($validated);

            if (isset($validated['training_materials'])) {
                $materialData = [];
                foreach ($validated['training_materials'] as $index => $materialId) {
                    $materialData[$materialId] = [
                        'is_required' => true,
                        'order_sequence' => $index + 1,
                        'updated_at' => now()
                    ];
                }
                $assignment->trainingMaterials()->sync($materialData);
            }

            DB::commit();

            return redirect()->route('training.assign.show', $assignment)->with('success', 'Training assignment updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update training assignment', [
                'assignment_id' => $assignment->id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return back()->withErrors(['error' => 'Failed to update training assignment. Please try again.'])->withInput();
        }
    }

    /**
     * Remove the specified training assignment
     */
    public function destroy(TrainingAssignment $assignment)
    {
        try {
            DB::beginTransaction();

            $hasActiveEmployees = $assignment->assignmentEmployees()
                ->whereIn('status', ['in_progress', 'completed'])
                ->exists();

            if ($hasActiveEmployees) {
                return back()->withErrors(['error' => 'Cannot delete assignment with employees who have started or completed the training.']);
            }

            $assignment->delete();
            DB::commit();

            return redirect()->route('training.assign.index')->with('success', 'Training assignment deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete training assignment', [
                'assignment_id' => $assignment->id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return back()->withErrors(['error' => 'Failed to delete training assignment. Please try again.']);
        }
    }

    public function activate(TrainingAssignment $assignment)
    {
        if ($assignment->status !== 'draft') {
            return back()->withErrors(['error' => 'Only draft assignments can be activated.']);
        }

        $assignment->update(['status' => 'active']);

        return back()->with('success', 'Training assignment activated successfully!');
    }

    public function cancel(TrainingAssignment $assignment)
    {
        if (!in_array($assignment->status, ['active', 'draft'])) {
            return back()->withErrors(['error' => 'Only active or draft assignments can be cancelled.']);
        }

        $assignment->update(['status' => 'cancelled']);

        return back()->with('success', 'Training assignment cancelled successfully!');
    }
}
