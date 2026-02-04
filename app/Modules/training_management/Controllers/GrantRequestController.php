<?php

namespace App\Modules\training_management\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\EmployeeApiService;

class GrantRequestController extends Controller
{
    protected $employeeApiService;

    public function __construct(EmployeeApiService $employeeApiService)
    {
        $this->employeeApiService = $employeeApiService;
    }

    /**
     * Display listing of course requests
     */
    public function index(Request $request)
    {
        try {
            $query = DB::connection('training_management')->table('course_requests as cr')
                ->leftJoin('training_catalogs as tc', 'cr.course_id', '=', 'tc.id')
                ->select([
                    'cr.id',
                    'cr.employee_id',
                    'cr.employee_name',
                    'cr.employee_email',
                    'cr.course_id',
                    'cr.course_title',
                    'cr.request_reason as justification',
                    'cr.status',
                    'cr.requested_at',
                    'cr.reviewed_at',
                    'cr.reviewed_by',
                    'cr.review_notes as admin_notes',
                    'tc.title as course_name',
                    'tc.description as course_description',
                    'tc.label as estimated_duration'
                ])
                ->orderBy('cr.requested_at', 'desc');

            // Filter by status if provided
            if ($request->has('status') && $request->status !== '') {
                $query->where('cr.status', $request->status);
            }

            // Search functionality
            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('cr.employee_name', 'LIKE', "%{$search}%")
                      ->orWhere('cr.employee_email', 'LIKE', "%{$search}%")
                      ->orWhere('tc.title', 'LIKE', "%{$search}%");
                });
            }

            $requests = $query->paginate(15);

            // Get status counts for filter tabs
            $statusCounts = DB::connection('training_management')->table('course_requests')
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            // Check which approved requests already have assessments assigned
            $assignedAssessments = [];
            $approvedRequests = $requests->filter(fn($r) => $r->status === 'approved');
            
            if ($approvedRequests->count() > 0) {
                // Get all employee IDs from approved requests
                $employeeIds = $approvedRequests->pluck('employee_id')->unique()->toArray();
                
                // Get existing assignments for these employees
                $existingAssignments = DB::connection('learning_management')
                    ->table('assessment_assignments')
                    ->whereIn('employee_id', $employeeIds)
                    ->whereIn('status', ['pending', 'in_progress', 'completed'])
                    ->select(['employee_id', 'quiz_id'])
                    ->get();
                
                // Create a lookup map of employee_id => quiz_ids
                foreach ($existingAssignments as $assignment) {
                    if (!isset($assignedAssessments[$assignment->employee_id])) {
                        $assignedAssessments[$assignment->employee_id] = [];
                    }
                    $assignedAssessments[$assignment->employee_id][] = $assignment->quiz_id;
                }
            }

            return view('training_management.grant', compact('requests', 'statusCounts', 'assignedAssessments'));

        } catch (\Exception $e) {
            Log::error('Error fetching course requests: ' . $e->getMessage());
            return back()->with('error', 'Error loading course requests: ' . $e->getMessage());
        }
    }

    /**
     * Show details of a specific course request
     */
    public function show($id)
    {
        try {
            $request = DB::connection('training_management')->table('course_requests as cr')
                ->leftJoin('training_catalogs as tc', 'cr.course_id', '=', 'tc.id')
                ->select([
                    'cr.*',
                    'tc.title as course_name',
                    'tc.description as course_description',
                    'tc.label as estimated_duration',
                    'tc.framework_id as course_category',
                    'tc.is_active as difficulty_level'
                ])
                ->where('cr.id', $id)
                ->first();

            if (!$request) {
                return back()->with('error', 'Course request not found.');
            }

            // Get training materials for the course if available
            $trainingMaterials = [];
            if ($request->course_id) {
                $trainingMaterials = DB::connection('training_management')
                    ->table('training_materials')
                    ->where('training_catalog_id', $request->course_id)
                    ->where('status', 'published')
                    ->select(['id', 'lesson_title', 'description', 'estimated_duration'])
                    ->get();
            }

            return view('training_management.grant-detail', compact('request', 'trainingMaterials'));

        } catch (\Exception $e) {
            Log::error('Error fetching course request details: ' . $e->getMessage());
            return back()->with('error', 'Error loading request details: ' . $e->getMessage());
        }
    }

    /**
     * Approve a course request
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        try {
            DB::connection('training_management')->beginTransaction();

            // Update the course request status
            $updated = DB::connection('training_management')->table('course_requests')
                ->where('id', $id)
                ->update([
                    'status' => 'approved',
                    'reviewed_at' => now(),
                    'reviewed_by' => auth()->user()->id ?? 1,
                    'review_notes' => $request->admin_notes
                ]);

            if (!$updated) {
                throw new \Exception('Course request not found.');
            }

            // Get the course request details
            $courseRequest = DB::connection('training_management')->table('course_requests')->where('id', $id)->first();

            if ($courseRequest) {
                // Create training assignment
                $assignmentId = DB::connection('training_management')->table('training_assignments')->insertGetId([
                    'assignment_title' => $courseRequest->course_title,
                    'training_catalog_id' => $courseRequest->course_id,
                    'priority' => 'medium',
                    'assignment_type' => 'mandatory',
                    'start_date' => now()->toDateString(),
                    'due_date' => now()->addDays(30)->toDateString(), // Default 30 days to complete
                    'instructions' => 'Assigned from approved course request. Notes: ' . ($request->admin_notes ?: 'No additional notes'),
                    'status' => 'active',
                    'created_by' => auth()->user()->id ?? 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Create employee assignment
                DB::connection('training_management')->table('training_assignment_employees')->insert([
                    'training_assignment_id' => $assignmentId,
                    'employee_id' => $courseRequest->employee_id,
                    'status' => 'assigned',
                    'assigned_at' => now(),
                    'notes' => 'Course request approved by ' . (auth()->user()->name ?? 'Admin'),
                    'progress_percentage' => 0.00,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Link all training materials from the course to this assignment
                $trainingMaterials = DB::connection('training_management')
                    ->table('training_materials')
                    ->where('training_catalog_id', $courseRequest->course_id)
                    ->where('status', 'published')
                    ->get();

                foreach ($trainingMaterials as $index => $material) {
                    DB::connection('training_management')->table('training_assignment_materials')->insert([
                        'training_assignment_id' => $assignmentId,
                        'training_material_id' => $material->id,
                        'is_required' => true, // Make all materials required by default
                        'order_sequence' => $index + 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                Log::info("Training assignment created with ID: {$assignmentId} and {$trainingMaterials->count()} materials linked for course request: {$id}");
            }

            DB::connection('training_management')->commit();

            // Create notification for course approval
            if ($courseRequest) {
                \App\Models\Notification::create([
                    'type' => \App\Models\Notification::TYPE_COURSE_APPROVED,
                    'title' => 'Course Request Approved',
                    'message' => "Course request for '{$courseRequest->course_title}' by {$courseRequest->employee_name} has been approved.",
                    'icon' => 'bx-book-open',
                    'icon_color' => 'text-emerald-500',
                    'link' => '/training/grant-request',
                    'data' => [
                        'request_id' => $id,
                        'course_title' => $courseRequest->course_title,
                        'employee_name' => $courseRequest->employee_name,
                    ],
                    'is_global' => true,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Course request approved and training assigned successfully!'
            ]);

        } catch (\Exception $e) {
            DB::connection('training_management')->rollBack();
            Log::error('Error approving course request: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error approving request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Deny a course request
     */
    public function deny(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000'
        ]);

        try {
            $updated = DB::connection('training_management')->table('course_requests')
                ->where('id', $id)
                ->update([
                    'status' => 'denied',
                    'reviewed_at' => now(),
                    'reviewed_by' => auth()->user()->id ?? 1,
                    'review_notes' => $request->admin_notes
                ]);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course request not found.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Course request denied successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error denying course request: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error denying request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics for dashboard
     */
    public function getStats()
    {
        try {
            $stats = DB::connection('training_management')->table('course_requests')
                ->selectRaw('
                    COUNT(*) as total_requests,
                    SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_requests,
                    SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved_requests,
                    SUM(CASE WHEN status = "denied" THEN 1 ELSE 0 END) as denied_requests
                ')
                ->first();

            return response()->json($stats);

        } catch (\Exception $e) {
            Log::error('Error fetching grant request stats: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Bulk action for multiple requests
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,deny',
            'request_ids' => 'required|array|min:1',
            'request_ids.*' => 'integer|exists:training_management.course_requests,id',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        try {
            DB::connection('training_management')->beginTransaction();

            $action = $request->action;
            $requestIds = $request->request_ids;
            $adminNotes = $request->admin_notes ?? '';

            foreach ($requestIds as $requestId) {
                if ($action === 'approve') {
                    // Update status to approved
                    DB::connection('training_management')->table('course_requests')
                        ->where('id', $requestId)
                        ->update([
                            'status' => 'approved',
                            'reviewed_at' => now(),
                            'reviewed_by' => auth()->user()->id ?? 1,
                            'review_notes' => $adminNotes
                        ]);

                    // Create training assignment
                    $courseRequest = DB::connection('training_management')->table('course_requests')->where('id', $requestId)->first();
                    if ($courseRequest) {
                        $assignmentId = DB::connection('training_management')->table('training_assignments')->insertGetId([
                            'assignment_title' => $courseRequest->course_title,
                            'training_catalog_id' => $courseRequest->course_id,
                            'priority' => 'medium',
                            'assignment_type' => 'mandatory',
                            'start_date' => now()->toDateString(),
                            'due_date' => now()->addDays(30)->toDateString(),
                            'instructions' => 'Assigned from bulk approved course request. Notes: ' . $adminNotes,
                            'status' => 'active',
                            'created_by' => auth()->user()->id ?? 1,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);

                        // Create employee assignment
                        DB::connection('training_management')->table('training_assignment_employees')->insert([
                            'training_assignment_id' => $assignmentId,
                            'employee_id' => $courseRequest->employee_id,
                            'status' => 'assigned',
                            'assigned_at' => now(),
                            'notes' => 'Bulk course request approved by ' . (auth()->user()->name ?? 'Admin'),
                            'progress_percentage' => 0.00,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);

                        // Link all training materials from the course to this assignment
                        $trainingMaterials = DB::connection('training_management')
                            ->table('training_materials')
                            ->where('training_catalog_id', $courseRequest->course_id)
                            ->where('status', 'published')
                            ->get();

                        foreach ($trainingMaterials as $index => $material) {
                            DB::connection('training_management')->table('training_assignment_materials')->insert([
                                'training_assignment_id' => $assignmentId,
                                'training_material_id' => $material->id,
                                'is_required' => true,
                                'order_sequence' => $index + 1,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }
                    }
                } else {
                    // Update status to denied
                    DB::connection('training_management')->table('course_requests')
                        ->where('id', $requestId)
                        ->update([
                            'status' => 'denied',
                            'reviewed_at' => now(),
                            'reviewed_by' => auth()->user()->id ?? 1,
                            'review_notes' => $adminNotes
                        ]);
                }
            }

            DB::connection('training_management')->commit();

            $actionText = $action === 'approve' ? 'approved' : 'denied';
            $count = count($requestIds);

            return response()->json([
                'success' => true,
                'message' => "{$count} course request(s) {$actionText} successfully!"
            ]);

        } catch (\Exception $e) {
            DB::connection('training_management')->rollBack();
            Log::error('Error processing bulk action: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error processing bulk action: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get related assessments for a course
     * Maps training catalog topics to learning management quizzes
     */
    public function getRelatedAssessments($courseId, $employeeId)
    {
        try {
            // Get the training catalog details
            $catalog = DB::connection('training_management')
                ->table('training_catalogs')
                ->where('id', $courseId)
                ->first();

            if (!$catalog) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course not found.',
                    'assessments' => [],
                    'all_assigned' => false
                ]);
            }

            // Mapping between training catalog labels/titles and assessment category slugs
            $categoryMappings = [
                'Leadership' => 'leadership-management',
                'Leadership Development' => 'leadership-management',
                'Technical' => 'technical-skills-assessment',
                'Technical Skills Training' => 'technical-skills-assessment',
                'Communication' => 'communication-skills',
                'Communication Excellence' => 'communication-skills',
                'PM' => 'project-management',
                'Project Management' => 'project-management',
                'Compliance' => 'compliance-regulations',
                'Compliance & Ethics' => 'compliance-regulations',
                'Customer Service' => 'customer-service-excellence',
                'Customer Service Excellence' => 'customer-service-excellence',
                'Analytics' => 'data-analytics-bi',
                'Data Analytics & Business Intelligence' => 'data-analytics-bi',
                'Safety' => 'health-safety',
                'Health & Safety' => 'health-safety',
                'Digital' => 'onboarding-assessment',
                'Digital Transformation' => 'onboarding-assessment',
                'HR' => 'onboarding-assessment',
                'Human Resources Management' => 'onboarding-assessment',
            ];

            // Find the matching category slug
            $categorySlug = $categoryMappings[$catalog->label] ?? $categoryMappings[$catalog->title] ?? null;

            $assessments = [];

            if ($categorySlug) {
                // Get the assessment category
                $category = DB::connection('learning_management')
                    ->table('assessment_categories')
                    ->where('category_slug', $categorySlug)
                    ->where('is_active', true)
                    ->first();

                if ($category) {
                    // Get all published quizzes in this category
                    $assessments = DB::connection('learning_management')
                        ->table('quizzes')
                        ->where('category_id', $category->id)
                        ->where('status', 'published')
                        ->select(['id', 'quiz_title', 'description', 'time_limit', 'total_questions', 'total_points'])
                        ->get()
                        ->toArray();
                }
            }

            // If no direct mapping found, try to find by keyword matching
            if (empty($assessments)) {
                $keywords = explode(' ', $catalog->title);
                $keywords = array_filter($keywords, fn($word) => strlen($word) > 3);
                
                if (!empty($keywords)) {
                    $query = DB::connection('learning_management')
                        ->table('quizzes')
                        ->where('status', 'published');
                    
                    $query->where(function($q) use ($keywords) {
                        foreach ($keywords as $keyword) {
                            $q->orWhere('quiz_title', 'LIKE', "%{$keyword}%")
                              ->orWhere('description', 'LIKE', "%{$keyword}%");
                        }
                    });
                    
                    $assessments = $query->select(['id', 'quiz_title', 'description', 'time_limit', 'total_questions', 'total_points'])
                        ->limit(10)
                        ->get()
                        ->toArray();
                }
            }

            // Get existing assignments for this employee
            $assignedQuizIds = [];
            if (!empty($assessments)) {
                $quizIds = array_column($assessments, 'id');
                $existingAssignments = DB::connection('learning_management')
                    ->table('assessment_assignments')
                    ->where('employee_id', $employeeId)
                    ->whereIn('quiz_id', $quizIds)
                    ->whereIn('status', ['pending', 'in_progress', 'completed'])
                    ->pluck('quiz_id')
                    ->toArray();
                
                $assignedQuizIds = $existingAssignments;
                
                // Add is_assigned flag to each assessment
                foreach ($assessments as &$assessment) {
                    $assessment->is_assigned = in_array($assessment->id, $assignedQuizIds);
                }
            }

            // Check if all assessments are already assigned
            $allAssigned = !empty($assessments) && count($assignedQuizIds) >= count($assessments);

            return response()->json([
                'success' => true,
                'course_title' => $catalog->title,
                'assessments' => $assessments,
                'assigned_quiz_ids' => $assignedQuizIds,
                'all_assigned' => $allAssigned
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching related assessments: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching assessments: ' . $e->getMessage(),
                'assessments' => [],
                'all_assigned' => false
            ], 500);
        }
    }

    /**
     * Assign assessment to employee
     */
    public function assignAssessment(Request $request)
    {
        $request->validate([
            'request_id' => 'required|integer',
            'employee_id' => 'required',
            'assessment_ids' => 'required|array|min:1',
            'assessment_ids.*' => 'integer',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000'
        ]);

        try {
            DB::connection('learning_management')->beginTransaction();

            $employeeId = $request->employee_id;
            $assessmentIds = $request->assessment_ids;
            $dueDate = $request->due_date ?? now()->addDays(30)->toDateString();
            $notes = $request->notes ?? '';

            // Get employee details from the course request
            $courseRequest = DB::connection('training_management')
                ->table('course_requests')
                ->where('id', $request->request_id)
                ->first();

            $employeeName = $courseRequest->employee_name ?? null;
            $employeeEmail = $courseRequest->employee_email ?? null;

            $assignedCount = 0;

            foreach ($assessmentIds as $quizId) {
                // Check if quiz exists and get its details
                $quiz = DB::connection('learning_management')
                    ->table('quizzes')
                    ->where('id', $quizId)
                    ->first();

                if (!$quiz) {
                    continue;
                }

                // Check if assignment already exists for this employee and quiz
                $existingAssignment = DB::connection('learning_management')
                    ->table('assessment_assignments')
                    ->where('quiz_id', $quizId)
                    ->where('employee_id', $employeeId)
                    ->whereIn('status', ['pending', 'in_progress'])
                    ->first();

                if ($existingAssignment) {
                    // Update existing assignment
                    DB::connection('learning_management')
                        ->table('assessment_assignments')
                        ->where('id', $existingAssignment->id)
                        ->update([
                            'due_date' => $dueDate . ' 23:59:59',
                            'notes' => $notes . ' (Re-assigned from grant request)',
                            'updated_at' => now()
                        ]);
                } else {
                    // Create new assignment using assessment_assignments table
                    DB::connection('learning_management')->table('assessment_assignments')->insert([
                        'assessment_category_id' => $quiz->category_id,
                        'quiz_id' => $quizId,
                        'employee_id' => $employeeId,
                        'employee_name' => $employeeName,
                        'employee_email' => $employeeEmail,
                        'duration' => $quiz->time_limit ?? 30,
                        'start_date' => now(),
                        'due_date' => $dueDate . ' 23:59:59',
                        'max_attempts' => '3',
                        'status' => 'pending',
                        'attempts_used' => 0,
                        'notes' => $notes . ' (Assigned from grant request approval)',
                        'assigned_by' => auth()->user()->id ?? 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                $assignedCount++;
            }

            DB::connection('learning_management')->commit();

            return response()->json([
                'success' => true,
                'message' => "{$assignedCount} assessment(s) assigned successfully!"
            ]);

        } catch (\Exception $e) {
            DB::connection('learning_management')->rollBack();
            Log::error('Error assigning assessment: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error assigning assessment: ' . $e->getMessage()
            ], 500);
        }
    }
}