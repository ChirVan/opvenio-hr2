<?php

namespace App\Modules\learning_management\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SelfAssessController extends Controller
{
    /**
     * Display all approved employees' course requests for self-assessment overview
     */
    public function index(Request $request)
    {
        try {
            // Query to get all course requests (approved employees from grant requests)
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
                ->where('cr.status', 'approved') // Show only approved requests
                ->orderBy('cr.requested_at', 'desc');

            // Filter by status if provided
            if ($request->has('status') && $request->status !== '') {
                $query->where('cr.status', $request->status);
            }

            // Search functionality
            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('cr.course_title', 'LIKE', "%{$search}%")
                      ->orWhere('tc.title', 'LIKE', "%{$search}%")
                      ->orWhere('cr.justification', 'LIKE', "%{$search}%");
                });
            }

            $requests = $query->paginate(15);

            // Get status counts for filter tabs (for all approved requests)
            $statusCounts = DB::connection('training_management')->table('course_requests')
                ->selectRaw('status, COUNT(*) as count')
                ->where('status', 'approved') // Focus on approved requests
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();
                
            // Add counts for other statuses for display purposes
            $allStatusCounts = DB::connection('training_management')->table('course_requests')
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();
                
            $statusCounts = array_merge($allStatusCounts, $statusCounts);

            // Get training assignments for approved requests
            $assignments = [];
            $assessmentAssignments = [];
            if ($requests->count() > 0) {
                $courseIds = $requests->pluck('course_id')->unique()->toArray();
                $employeeIds = $requests->pluck('employee_id')->unique()->toArray();
                
                if (!empty($courseIds)) {
                    $assignments = DB::connection('training_management')->table('training_assignment_employees as tae')
                        ->join('training_assignments as ta', 'tae.training_assignment_id', '=', 'ta.id')
                        ->join('training_catalogs as tc', 'ta.training_catalog_id', '=', 'tc.id')
                        ->whereIn('ta.training_catalog_id', $courseIds)
                        ->select([
                            'ta.id as assignment_id',
                            'ta.assignment_title',
                            'ta.training_catalog_id',
                            'tc.title as course_title',
                            'tae.employee_id',
                            'tae.status as assignment_status',
                            'tae.progress_percentage',
                            'tae.assigned_at',
                            'ta.due_date',
                            'ta.instructions'
                        ])
                        ->get()
                        ->groupBy(function($item) {
                            return $item->training_catalog_id . '_' . $item->employee_id;
                        });
                }
                
                // Get assessment assignments for employees
                if (!empty($employeeIds)) {
                    $assessmentAssignments = DB::connection('learning_management')->table('assessment_assignments')
                        ->whereIn('employee_id', $employeeIds)
                        ->select([
                            'id',
                            'employee_id',
                            'quiz_id',
                            'employee_name',
                            'status',
                            'created_at',
                            'due_date'
                        ])
                        ->get()
                        ->groupBy('employee_id');
                }
            }

            return view('learning_management.self_assess', compact('requests', 'statusCounts', 'assignments', 'assessmentAssignments'));

        } catch (\Exception $e) {
            Log::error('Error fetching self-assessment course requests: ' . $e->getMessage());
            return back()->with('error', 'Error loading your course requests: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new assessment assignment
     */
    public function create(Request $request)
    {
        // Get employee_id and course_id from query parameters
        $employeeId = $request->get('employee_id');
        $courseId = $request->get('course_id');
        
        // Get employee and course details if provided
        $employee = null;
        $course = null;
        
        if ($employeeId) {
            // Try to get employee details from the course request first
            $courseRequest = DB::connection('training_management')->table('course_requests')
                ->where('employee_id', $employeeId)
                ->where('course_id', $courseId)
                ->first();
            
            if ($courseRequest) {
                $employee = [
                    'id' => $employeeId,
                    'employee_id' => $courseRequest->employee_id,
                    'name' => $courseRequest->employee_name,
                    'email' => $courseRequest->employee_email
                ];
            } else {
                // Fallback if no course request found
                $employee = [
                    'id' => $employeeId,
                    'employee_id' => $employeeId,
                    'name' => 'Selected Employee',
                    'email' => ''
                ];
            }
        }
        
        if ($courseId) {
            $course = DB::connection('training_management')->table('training_catalogs')
                ->where('id', $courseId)
                ->first();
        }
        
        return view('learning_management.selfCRUD.create', compact('employee', 'course', 'employeeId', 'courseId'));
    }

    /**
     * Assign multiple assessments to an employee
     */
    public function assignAssessments(Request $request)
    {
        $request->validate([
            'assessment_category' => 'required|integer',
            'quiz_ids' => 'required|array|min:1',
            'quiz_ids.*' => 'required|integer',
            'employee_id' => 'required',
            'duration' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after:start_date',
            'max_attempts' => 'required|integer|min:1|max:10',
            'instructions' => 'nullable|string|max:1000'
        ]);

        try {
            DB::connection('learning_management')->beginTransaction();
            
            $assignmentIds = [];
            $user = Auth::user();
            
            // Get employee details from the pre-selected employee or API
            $employeeId = $request->employee_id;
            $employeeDetails = null;
            
            // If we have a preselected_employee_id, get details from course_requests
            if ($request->has('preselected_employee_id')) {
                $courseRequest = DB::connection('training_management')->table('course_requests')
                    ->where('employee_id', $request->preselected_employee_id)
                    ->first();
                
                if ($courseRequest) {
                    $employeeDetails = [
                        'full_name' => $courseRequest->employee_name,
                        'email' => $courseRequest->employee_email,
                        'employee_id' => $courseRequest->employee_id
                    ];
                    $employeeId = $courseRequest->employee_id;
                }
            }
            
            // If no preselected employee details, try to get from external API (fallback)
            if (!$employeeDetails) {
                try {
                    $apiResponse = \Illuminate\Support\Facades\Http::withOptions(['verify' => false])
                        ->withHeaders(['X-API-Key' => 'b24e8778f104db434adedd4342e94d39cee6d0668ec595dc6f02c739c522b57a'])
                        ->get('https://hr4.microfinancial-1.com/allemployees');
                    $responseData = $apiResponse->json();
                    $employeesApi = $responseData['data'] ?? $responseData['employees'] ?? $responseData;
                    
                    if (is_array($employeesApi)) {
                        $employee = collect($employeesApi)
                            ->firstWhere('id', $employeeId);
                        
                        if ($employee) {
                            $employeeDetails = [
                                'full_name' => $employee['full_name'],
                                'email' => $employee['email'] ?? '',
                                'employee_id' => $employee['employee_id']
                            ];
                        }
                    }
                } catch (\Exception $apiError) {
                    Log::warning('Failed to fetch employee details from API: ' . $apiError->getMessage());
                }
            }
            
            // Fallback employee details
            if (!$employeeDetails) {
                $employeeDetails = [
                    'full_name' => 'Employee',
                    'email' => '',
                    'employee_id' => $employeeId
                ];
            }
            
            // Create an assignment for each selected quiz using the AssessmentAssignment model
            foreach ($request->quiz_ids as $quizId) {
                // Get quiz details using the Quiz model
                $quiz = \App\Modules\learning_management\Models\Quiz::on('learning_management')
                    ->with('category')
                    ->find($quizId);
                
                if (!$quiz) {
                    Log::warning("Quiz not found: " . $quizId);
                    continue; // Skip if quiz not found
                }
                
                // Create the assessment assignment using the model
                $assignment = \App\Modules\learning_management\Models\AssessmentAssignment::create([
                    'assessment_category_id' => $request->assessment_category,
                    'quiz_id' => $quizId,
                    'employee_id' => $employeeId,
                    'employee_name' => $employeeDetails['full_name'],
                    'employee_email' => $employeeDetails['email'],
                    'duration' => $request->duration,
                    'start_date' => $request->start_date,
                    'due_date' => $request->due_date,
                    'max_attempts' => $request->max_attempts,
                    'notes' => $request->instructions,
                    'assignment_metadata' => [
                        'employee_details' => $employeeDetails,
                        'quiz_details' => [
                            'title' => $quiz->quiz_title,
                            'category' => $quiz->category->category_name ?? 'Unknown',
                            'total_questions' => $quiz->total_questions,
                            'total_points' => $quiz->total_points,
                        ],
                        'assigned_at' => now()->toISOString(),
                        'assigned_from' => 'self_assessment_module'
                    ],
                    'assigned_by' => $user->id,
                    'status' => 'pending'
                ]);
                
                if ($assignment) {
                    $assignmentIds[] = $assignment->id;
                    
                    // Log the assignment
                    Log::info("Assessment assigned from self-assessment module", [
                        'assignment_id' => $assignment->id,
                        'employee_id' => $employeeId,
                        'employee_name' => $employeeDetails['full_name'],
                        'quiz_id' => $quizId,
                        'quiz_title' => $quiz->quiz_title,
                        'assigned_by' => $user->id,
                        'assigned_by_name' => $user->name
                    ]);
                }
            }
            
            DB::connection('learning_management')->commit();
            
            if (count($assignmentIds) > 0) {
                $employeeName = $employeeDetails['full_name'];
                $message = count($assignmentIds) === 1 
                    ? "1 assessment has been successfully assigned to {$employeeName}."
                    : count($assignmentIds) . " assessments have been successfully assigned to {$employeeName}.";
                
                return redirect()->route('learning.self-assess')
                    ->with('success', $message);
            } else {
                DB::connection('learning_management')->rollBack();
                return back()->with('error', 'Failed to assign assessments. No valid assessments were found.');
            }
            
        } catch (\Exception $e) {
            DB::connection('learning_management')->rollBack();
            Log::error('Error assigning assessments: ' . $e->getMessage());
            return back()->with('error', 'Error assigning assessments: ' . $e->getMessage());
        }
    }

    /**
     * Submit a new course request
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer|exists:training_management.training_catalogs,id',
            'justification' => 'required|string|max:1000'
        ]);

        try {
            // Get current user details
            $user = Auth::user();
            
            // Get course details
            $course = DB::connection('training_management')->table('training_catalogs')
                ->where('id', $request->course_id)
                ->first();

            if (!$course) {
                return back()->with('error', 'Course not found.');
            }

            // Check if user already has a pending request for this course
            $existingRequest = DB::connection('training_management')->table('course_requests')
                ->where('employee_id', $user->id)
                ->where('course_id', $request->course_id)
                ->where('status', 'pending')
                ->first();

            if ($existingRequest) {
                return back()->with('error', 'You already have a pending request for this course.');
            }

            // Create the course request
            DB::connection('training_management')->table('course_requests')->insert([
                'employee_id' => $user->id,
                'employee_name' => $user->name,
                'employee_email' => $user->email,
                'course_id' => $request->course_id,
                'course_title' => $course->title,
                'request_reason' => $request->justification,
                'status' => 'pending',
                'requested_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return redirect()->route('learning.self-assess')->with('success', 'Course request submitted successfully! It will be reviewed by an administrator.');

        } catch (\Exception $e) {
            Log::error('Error submitting course request: ' . $e->getMessage());
            return back()->with('error', 'Error submitting course request: ' . $e->getMessage());
        }
    }
}