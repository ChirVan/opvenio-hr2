<?php

namespace App\Modules\training_management\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\training_management\Models\TrainingAssignment;
use App\Modules\training_management\Models\TrainingAssignmentEmployee;
use App\Modules\training_management\Models\TrainingCatalog;
use App\Modules\training_management\Models\TrainingMaterial;
use App\Modules\competency_management\Models\GapAnalysis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class TrainingAssignmentController extends Controller
{
    /**
     * Display a listing of training assignments
     */
    public function index()
    {
        $assignments = TrainingAssignment::with([
            'trainingCatalog',
            'creator',
            'assignmentEmployees.employee',
            'trainingMaterials'
        ])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        return view('training_management.assign', compact('assignments'));
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
            // Validate the request
            $validated = $request->validate([
                'assignment_title' => 'required|string|max:255',
                'training_catalog_id' => 'required|exists:training_management.training_catalogs,id',
                'training_materials' => 'required|array|min:1',
                'training_materials.*' => 'exists:training_management.training_materials,id',
                'employee_id' => 'required|integer',
                'priority' => 'required|in:low,medium,high,urgent',
                'assignment_type' => 'required|in:mandatory,optional,development',
                'start_date' => 'required|date|after_or_equal:today',
                'due_date' => 'required|date|after:start_date',
                'instructions' => 'nullable|string|max:5000',
                'action' => 'required|in:draft,assign'
            ]);

            // Verify employee exists in gap analysis
            $employeeExists = GapAnalysis::where('employee_id', $validated['employee_id'])
                ->exists();

            if (!$employeeExists) {
                return back()->withErrors([
                    'employee_id' => 'Selected employee not found in gap analysis data.'
                ])->withInput();
            }

            DB::beginTransaction();

            // Determine status based on action
            $status = $validated['action'] === 'assign' ? 'active' : 'draft';

            // Create the training assignment
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

            // Attach training materials to the assignment
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

            // Create assignment for the selected employee
            TrainingAssignmentEmployee::create([
                'training_assignment_id' => $assignment->id,
                'employee_id' => $validated['employee_id'],
                'status' => 'assigned',
                'assigned_at' => now()
            ]);

            DB::commit();

            $message = $status === 'active' 
                ? 'Training assignment created and activated successfully!'
                : 'Training assignment saved as draft successfully!';

            Log::info('Training assignment created', [
                'assignment_id' => $assignment->id,
                'employee_id' => $validated['employee_id'],
                'status' => $status,
                'created_by' => Auth::id()
            ]);

            return redirect()->route('training.assign.index')
                ->with('success', $message);

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

            return back()->withErrors([
                'error' => 'Failed to create training assignment. Please try again.'
            ])->withInput();
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
            'assignmentEmployees.employee',
            'creator'
        ]);

        return view('training_management.assign', compact('assignment'));
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

        $assignment->load([
            'trainingMaterials',
            'assignmentEmployees.employee'
        ]);

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

            // Update the assignment
            $assignment->update($validated);

            // Update training materials
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

            return redirect()->route('training.assign.show', $assignment)
                ->with('success', 'Training assignment updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update training assignment', [
                'assignment_id' => $assignment->id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return back()->withErrors([
                'error' => 'Failed to update training assignment. Please try again.'
            ])->withInput();
        }
    }

    /**
     * Remove the specified training assignment
     */
    public function destroy(TrainingAssignment $assignment)
    {
        try {
            DB::beginTransaction();

            // Check if assignment can be deleted
            $hasActiveEmployees = $assignment->assignmentEmployees()
                ->whereIn('status', ['in_progress', 'completed'])
                ->exists();

            if ($hasActiveEmployees) {
                return back()->withErrors([
                    'error' => 'Cannot delete assignment with employees who have started or completed the training.'
                ]);
            }

            $assignment->delete();
            DB::commit();

            return redirect()->route('training.assign.index')
                ->with('success', 'Training assignment deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete training assignment', [
                'assignment_id' => $assignment->id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return back()->withErrors([
                'error' => 'Failed to delete training assignment. Please try again.'
            ]);
        }
    }

    /**
     * Activate a draft assignment
     */
    public function activate(TrainingAssignment $assignment)
    {
        if ($assignment->status !== 'draft') {
            return back()->withErrors([
                'error' => 'Only draft assignments can be activated.'
            ]);
        }

        $assignment->update(['status' => 'active']);

        return back()->with('success', 'Training assignment activated successfully!');
    }

    /**
     * Cancel an active assignment
     */
    public function cancel(TrainingAssignment $assignment)
    {
        if (!in_array($assignment->status, ['active', 'draft'])) {
            return back()->withErrors([
                'error' => 'Only active or draft assignments can be cancelled.'
            ]);
        }

        $assignment->update(['status' => 'cancelled']);

        return back()->with('success', 'Training assignment cancelled successfully!');
    }
}