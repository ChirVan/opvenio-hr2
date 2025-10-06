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

            // Get quiz details for additional validation
            $quiz = Quiz::on('learning_management')->with('category')->findOrFail($request->quiz_id);

            // Create the assessment assignment
            $assignment = AssessmentAssignment::create([
                'assessment_category_id' => $request->assessment_category,
                'quiz_id' => $request->quiz_id,
                'employee_id' => $request->employee_id,
                'employee_name' => $employeeDetails['full_name'] ?? 'Unknown',
                'employee_email' => $employeeDetails['email'] ?? null,
                'duration' => $request->duration,
                'start_date' => $request->start_date,
                'due_date' => $request->due_date,
                'max_attempts' => $request->max_attempts,
                'notes' => $request->notes,
                'assignment_metadata' => [
                    'employee_details' => $employeeDetails,
                    'quiz_details' => [
                        'title' => $quiz->quiz_title,
                        'category' => $quiz->category->category_name,
                        'total_questions' => $quiz->total_questions,
                        'total_points' => $quiz->total_points,
                    ],
                    'assigned_at' => now()->toISOString(),
                ],
                'assigned_by' => Auth::id(),
                'status' => 'pending'
            ]);

            DB::connection('learning_management')->commit();

            // TODO: Send notification email to employee
            // $this->sendAssignmentNotification($assignment);

            return redirect()->route('learning.hub')
                           ->with('success', 'Assessment successfully assigned to ' . $employeeDetails['full_name']);

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
    public function destroy(AssessmentAssignment $assessmentAssignment): RedirectResponse
    {
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
}