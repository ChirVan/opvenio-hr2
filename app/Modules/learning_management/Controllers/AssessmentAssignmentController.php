<?php

namespace App\Modules\learning_management\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\learning_management\Models\AssessmentAssignment;
use App\Modules\learning_management\Models\AssessmentCategory;
use App\Modules\learning_management\Models\Quiz;
use App\Modules\learning_management\Requests\StoreAssessmentAssignmentRequest;
use App\Services\EmployeeApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssessmentAssignmentController extends Controller
{
    protected $employeeApiService;

    public function __construct(EmployeeApiService $employeeApiService)
    {
        $this->employeeApiService = $employeeApiService;
    }

    /**
     * Display a listing of assessment assignments.
     */
    public function index(Request $request): View
    {
        $query = AssessmentAssignment::on('learning_management')->with(['assessmentCategory', 'quiz'])
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by employee if provided
        if ($request->has('employee_id') && $request->employee_id !== '') {
            $query->where('employee_id', $request->employee_id);
        }

        // Search functionality
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('employee_name', 'LIKE', "%{$search}%")
                  ->orWhere('employee_email', 'LIKE', "%{$search}%")
                  ->orWhereHas('quiz', function($quizQuery) use ($search) {
                      $quizQuery->where('quiz_title', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('assessmentCategory', function($categoryQuery) use ($search) {
                      $categoryQuery->where('category_name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $assignments = $query->paginate(15);

        // Get statistics for dashboard
        $stats = [
            'total' => AssessmentAssignment::on('learning_management')->count(),
            'pending' => AssessmentAssignment::on('learning_management')->where('status', 'pending')->count(),
            'in_progress' => AssessmentAssignment::on('learning_management')->where('status', 'in_progress')->count(),
            'completed' => AssessmentAssignment::on('learning_management')->where('status', 'completed')->count(),
            'overdue' => AssessmentAssignment::on('learning_management')->overdue()->count(),
        ];

        return view('learning_management.hub', compact('assignments', 'stats'));
    }

    /**
     * Show the form for creating a new assessment assignment.
     */
    public function create(): View
    {
        return view('learning_management.hubCRUD.create');
    }

    /**
     * Store a newly created assessment assignment.
     * Handles multiple quiz assignments at once.
     */
    public function store(StoreAssessmentAssignmentRequest $request): RedirectResponse
    {
        try {
            DB::connection('learning_management')->beginTransaction();

            // Get employee details from external API
            $employeeDetails = $this->getEmployeeDetails($request->employee_id);
            
            if (!$employeeDetails) {
                return back()->withErrors(['employee_id' => 'Unable to fetch employee details from external system.'])
                            ->withInput();
            }

            $quizIds = $request->quiz_ids;
            $categoryIds = $request->category_ids;
            $createdAssignments = [];
            
            // Determine assignment source and build notes
            $source = $request->input('source', 'self_request');
            $baseNotes = $request->notes ?? '';
            
            // Add source identifier to notes for proper assignment type detection
            if ($source === 'gap_analysis') {
                $sourceNote = '[Source: Competency Gap Analysis - Skill Gap Requirement]';
            } else {
                $sourceNote = '[Source: Self Assessment Request]';
            }
            
            // Combine source note with user notes
            $finalNotes = trim($sourceNote . ($baseNotes ? "\n\n" . $baseNotes : ''));

            // Create an assignment for each selected quiz
            foreach ($quizIds as $index => $quizId) {
                $categoryId = $categoryIds[$index] ?? null;
                
                // Get quiz details
                $quiz = Quiz::on('learning_management')->with('category')->findOrFail($quizId);

                // Create the assessment assignment
                $assignment = AssessmentAssignment::create([
                    'assessment_category_id' => $categoryId ?? $quiz->category_id,
                    'quiz_id' => $quizId,
                    'employee_id' => $request->employee_id,
                    'employee_name' => $employeeDetails['full_name'] ?? 'Unknown',
                    'employee_email' => $employeeDetails['email'] ?? null,
                    'duration' => $quiz->time_limit ?? 60, // Use quiz's time limit or default 60 min
                    'start_date' => $request->start_date,
                    'due_date' => $request->due_date,
                    'max_attempts' => $request->max_attempts,
                    'notes' => $finalNotes,
                    'assignment_metadata' => [
                        'employee_details' => $employeeDetails,
                        'quiz_details' => [
                            'title' => $quiz->quiz_title,
                            'category' => $quiz->category->category_name ?? 'Unknown',
                            'total_questions' => $quiz->total_questions,
                            'total_points' => $quiz->total_points,
                        ],
                        'assigned_at' => now()->toISOString(),
                        'batch_assignment' => count($quizIds) > 1,
                        'assignment_source' => $source, // Store source in metadata too
                    ],
                    'assigned_by' => Auth::id(),
                    'status' => 'pending'
                ]);

                $createdAssignments[] = $assignment;
            }

            DB::connection('learning_management')->commit();

            // TODO: Send notification email to employee
            // $this->sendAssignmentNotification($createdAssignments);

            $assignmentCount = count($createdAssignments);
            $message = $assignmentCount === 1 
                ? "Assessment successfully assigned to {$employeeDetails['full_name']}"
                : "{$assignmentCount} assessments successfully assigned to {$employeeDetails['full_name']}";

            return redirect()->route('learning.hub')
                           ->with('success', $message);

        } catch (\Exception $e) {
            DB::connection('learning_management')->rollBack();
            
            // Log the actual error for debugging
            \Log::error('Assessment assignment failed: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            
            return back()->withErrors(['error' => 'Failed to assign assessment. Please check the form data and try again.'])
                        ->withInput();
        }
    }

    /**
     * Display the specified assessment assignment.
     */
    public function show(AssessmentAssignment $assessmentAssignment): View
    {
        $assessmentAssignment->load(['assessmentCategory', 'quiz.questions']);
        
        return view('learning_management.assessment_assignments.show', compact('assessmentAssignment'));
    }

    /**
     * Show the form for editing the specified assessment assignment.
     */
    public function edit(AssessmentAssignment $assessmentAssignment): View
    {
        $assessmentAssignment->load(['assessmentCategory', 'quiz']);
        
        return view('learning_management.assessment_assignments.edit', compact('assessmentAssignment'));
    }

    /**
     * Update the specified assessment assignment.
     */
    public function update(Request $request, AssessmentAssignment $assessmentAssignment): RedirectResponse
    {
        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:now',
            'due_date' => 'required|date|after:start_date',
            'max_attempts' => 'required|in:1,2,3,unlimited',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,in_progress,completed,overdue,cancelled'
        ]);

        $assessmentAssignment->update($validated);

        return redirect()->route('learning.assessment-assignments.show', $assessmentAssignment)
                       ->with('success', 'Assessment assignment updated successfully.');
    }

    /**
     * Remove the specified assessment assignment.
     */
    public function destroy($id): RedirectResponse
    {
        $assessmentAssignment = AssessmentAssignment::on('learning_management')->findOrFail($id);
        $employeeName = $assessmentAssignment->employee_name;
        $assessmentAssignment->delete();

        return redirect()->route('learning.assessment-assignments.index')
            ->with('success', "Assessment assignment for {$employeeName} has been deleted.");
    }

    /**
     * Cancel an assessment assignment.
     */
    public function cancel(AssessmentAssignment $assessmentAssignment): RedirectResponse
    {
        if (in_array($assessmentAssignment->status, ['completed', 'cancelled'])) {
            return back()->withErrors(['error' => 'Cannot cancel a completed or already cancelled assignment.']);
        }

        $assessmentAssignment->update(['status' => 'cancelled']);

        return back()->with('success', 'Assessment assignment has been cancelled.');
    }

    /**
     * Get assignments for a specific employee via API.
     */
    public function getEmployeeAssignments(Request $request): JsonResponse
    {
        $employeeId = $request->get('employee_id');
        
        if (!$employeeId) {
            return response()->json(['success' => false, 'message' => 'Employee ID is required.']);
        }

        $assignments = AssessmentAssignment::on('learning_management')->with(['assessmentCategory', 'quiz'])
            ->where('employee_id', $employeeId)
            ->orderBy('due_date', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'assignments' => $assignments->map(function ($assignment) {
                return [
                    'id' => $assignment->id,
                    'quiz_title' => $assignment->quiz->quiz_title,
                    'category_name' => $assignment->assessmentCategory->category_name,
                    'status' => $assignment->status,
                    'due_date' => $assignment->due_date->format('Y-m-d H:i:s'),
                    'progress_percentage' => $assignment->getProgressPercentage(),
                    'can_attempt' => $assignment->canAttempt(),
                    'is_available' => $assignment->isAvailable(),
                    'is_overdue' => $assignment->isOverdue(),
                ];
            })
        ]);
    }

    /**
     * Get assignment statistics for dashboard.
     */
    public function getStats(): JsonResponse
    {
        $stats = [
            'total_assignments' => AssessmentAssignment::on('learning_management')->count(),
            'pending' => AssessmentAssignment::on('learning_management')->where('status', 'pending')->count(),
            'in_progress' => AssessmentAssignment::on('learning_management')->where('status', 'in_progress')->count(),
            'completed' => AssessmentAssignment::on('learning_management')->where('status', 'completed')->count(),
            'overdue' => AssessmentAssignment::on('learning_management')->overdue()->count(),
            'due_soon' => AssessmentAssignment::on('learning_management')->dueWithin(7)->count(),
            'completion_rate' => $this->getCompletionRate(),
        ];

        return response()->json(['success' => true, 'stats' => $stats]);
    }

    /**
     * Get employee details from external API.
     */
    private function getEmployeeDetails(string $employeeId): ?array
    {
        try {
            $employees = $this->employeeApiService->getEmployees();
            
            foreach ($employees as $employee) {
                if ($employee['id'] == $employeeId) {
                    return $employee;
                }
            }
            
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Calculate completion rate.
     */
    private function getCompletionRate(): float
    {
        $total = AssessmentAssignment::on('learning_management')->count();
        
        if ($total === 0) {
            return 0.0;
        }
        
        $completed = AssessmentAssignment::on('learning_management')->where('status', 'completed')->count();
        
        return round(($completed / $total) * 100, 2);
    }

    /**
     * Send assignment notification to employee.
     * TODO: Implement email notification
     */
    private function sendAssignmentNotification(AssessmentAssignment $assignment): void
    {
        // This would typically send an email notification to the employee
        // Implementation depends on your notification system
    }

    /**
     * Auto-reassign failed assessments to give employee another attempt.
     * Instead of creating new assignments, reset the existing one and increment attempt counter.
     */
    public function reassign(Request $request): RedirectResponse
    {
        try {
            $employeeId = $request->employee_id;
            $resultIds = $request->result_ids ? explode(',', $request->result_ids) : [];

            if (empty($employeeId)) {
                return redirect()->route('assessment.results')
                    ->with('error', 'Employee ID is required for reassignment.');
            }

            // Get the failed assessment results for this employee
            $failedResults = DB::connection('ess')
                ->table('assessment_results')
                ->where('employee_id', $employeeId)
                ->where('evaluation_status', 'failed');

            // If specific result IDs provided, filter by them
            if (!empty($resultIds)) {
                $failedResults->whereIn('id', $resultIds);
            }

            $failedResults = $failedResults->get();

            if ($failedResults->isEmpty()) {
                return redirect()->route('assessment.results')
                    ->with('error', 'No failed assessments found for this employee.');
            }

            DB::connection('learning_management')->beginTransaction();
            DB::connection('ess')->beginTransaction();

            $updatedAssignments = 0;
            $employeeName = '';

            foreach ($failedResults as $result) {
                // Get the original assignment details
                $originalAssignment = AssessmentAssignment::on('learning_management')
                    ->with(['quiz', 'assessmentCategory'])
                    ->find($result->assignment_id);

                if (!$originalAssignment) {
                    continue;
                }

                $employeeName = $originalAssignment->employee_name;

                // Check if max attempts reached
                $currentAttempts = $originalAssignment->attempts_used ?? 0;
                $maxAttempts = $originalAssignment->max_attempts ?? 3;

                if ($currentAttempts >= $maxAttempts) {
                    continue; // Skip if max attempts already reached
                }

                // Update the existing assignment to allow another attempt
                $originalAssignment->update([
                    'status' => 'pending', // Reset status to pending
                    'started_at' => null, // Clear so timer resets on new attempt
                    'completed_at' => null, // Clear completion timestamp
                    'due_date' => now()->addDays(7), // Give 7 days to complete
                    'notes' => ($originalAssignment->notes ?? '') . "\n[Retry allowed on " . now()->format('M d, Y') . " - Previous attempt failed with score: " . ($result->score ?? 0) . "%]",
                    'updated_at' => now()
                ]);

                // Mark the old result as a previous attempt (keep for history)
                DB::connection('ess')
                    ->table('assessment_results')
                    ->where('id', $result->id)
                    ->update([
                        'status' => 'retried', // Mark as retried so it's not shown as active
                        'updated_at' => now()
                    ]);

                $updatedAssignments++;
            }

            DB::connection('learning_management')->commit();
            DB::connection('ess')->commit();

            if ($updatedAssignments === 0) {
                return redirect()->route('assessment.results')
                    ->with('error', 'No assessments could be reassigned. Max attempts may have been reached.');
            }

            $message = $updatedAssignments === 1 
                ? "Reassessment successfully assigned to {$employeeName}. They have 7 days to complete."
                : "{$updatedAssignments} reassessments successfully assigned to {$employeeName}. They have 7 days to complete.";

            return redirect()->route('assessment.results')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::connection('learning_management')->rollBack();
            DB::connection('ess')->rollBack();
            
            \Log::error('Assessment reassignment failed: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            
            return redirect()->route('assessment.results')
                ->with('error', 'Failed to reassign assessment. Please try again.');
        }
    }
}