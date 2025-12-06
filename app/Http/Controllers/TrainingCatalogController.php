<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Modules\training_management\Models\TrainingCatalog;
use App\Modules\training_management\Models\TrainingMaterial;

class TrainingCatalogController extends Controller
{
    /**
     * Get all available courses from training_catalogs for Course Grant
     */
    public function getAvailableCourses()
    {
        try {
            $user = Auth::user();
            
            // Get all active training catalogs with their frameworks
            $trainingCatalogs = TrainingCatalog::with('framework')
                ->where('is_active', true)
                ->orderBy('title')
                ->get();

            // Get user's existing course requests
            $existingRequests = [];
            if ($user) {
                $existingRequests = DB::connection('training_management')
                    ->table('course_requests')
                    ->where('employee_id', $user->id)
                    ->whereIn('status', ['pending', 'approved'])
                    ->pluck('status', 'course_id')
                    ->toArray();
            }

            // Transform the data for frontend consumption
            $courses = $trainingCatalogs->map(function ($catalog) use ($existingRequests) {
                $requestStatus = $existingRequests[$catalog->id] ?? null;
                
                return (object) [
                    'id' => $catalog->id,
                    'title' => $catalog->title,
                    'description' => $catalog->description ?: 'Comprehensive training program designed to enhance your skills and knowledge.',
                    'category' => $catalog->label ?: 'General Training',
                    'duration' => 'Self-paced', // Default duration
                    'difficulty_level' => $catalog->framework ? $catalog->framework->framework_name : 'Intermediate',
                    'learning_objectives' => $catalog->description ?: 'Gain practical skills and knowledge in this area.',
                    'created_at' => $catalog->created_at,
                    'request_status' => $requestStatus, // null, 'pending', 'approved'
                    'can_request' => $requestStatus === null
                ];
            });

            // Get unique categories for filtering
            $categories = $trainingCatalogs->pluck('label')
                ->filter()
                ->unique()
                ->sort()
                ->values();

            return response()->json([
                'success' => true,
                'courses' => $courses,
                'categories' => $categories
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching available courses: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load available courses',
                'courses' => [],
                'categories' => []
            ], 500);
        }
    }

    /**
     * Get training materials for a specific course
     */
    public function getCourseTrainingMaterials($courseId)
    {
        try {
            // Get training materials for the specific course using the model
            $materials = TrainingMaterial::where('training_catalog_id', $courseId)
                ->where('status', 'published')
                ->where('is_active', true)
                ->with(['competency.framework'])
                ->select([
                    'id',
                    'lesson_title',
                    'lesson_content',
                    'competency_id',
                    'proficiency_level',
                    'status',
                    'created_at',
                    'updated_at'
                ])
                ->orderBy('created_at')
                ->get()
                ->map(function($material) {
                    return [
                        'id' => $material->id,
                        'lesson_title' => $material->lesson_title,
                        'lesson_content' => $material->lesson_content,
                        'proficiency_level' => $material->proficiency_level_name,
                        'competency_name' => $material->competency ? $material->competency->competency_name : 'General Training',
                        'framework_name' => ($material->competency && $material->competency->framework) ? $material->competency->framework->framework_name : 'Core Skills',
                        'status' => ucfirst($material->status),
                        'content_excerpt' => $material->content_excerpt,
                        'created_at' => $material->created_at->format('M d, Y'),
                        'has_competency' => $material->competency ? true : false,
                    ];
                });

            return response()->json([
                'success' => true,
                'materials' => $materials,
                'count' => $materials->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching course training materials: ' . $e->getMessage(), [
                'course_id' => $courseId,
                'error' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load course materials',
                'materials' => []
            ], 500);
        }
    }

    /**
     * Handle course access request from employee
     */
    public function requestCourse(Request $request)
    {
        try {
            $user = Auth::user();
            $courseId = $request->input('course_id');
            $reason = $request->input('reason', '');

            // Validate required fields
            if (!$courseId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course ID is required'
                ], 400);
            }

            // Validate user is authenticated (this is already handled by middleware, but double-check)
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to request courses.'
                ], 401);
            }

            // Get course details
            $course = TrainingCatalog::find($courseId);

            if (!$course) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course not found'
                ], 404);
            }

            // Check if user already has a pending or approved request for this course
            $existingRequest = DB::connection('training_management')
                ->table('course_requests')
                ->where('employee_id', $user->id) // Use user ID instead of employee_id string
                ->where('course_id', $courseId)
                ->whereIn('status', ['pending', 'approved'])
                ->first();

            if ($existingRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have a ' . $existingRequest->status . ' request for this course'
                ], 409);
            }

            // Create course request
            $requestId = DB::connection('training_management')
                ->table('course_requests')
                ->insertGetId([
                    'employee_id' => $user->id, // Use user ID instead of employee_id string
                    'course_id' => $courseId,
                    'course_title' => $course->title,
                    'employee_name' => $user->name,
                    'employee_email' => $user->email,
                    'request_reason' => $reason,
                    'status' => 'pending',
                    'requested_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

            // Log the request
            Log::info('Course access requested', [
                'request_id' => $requestId,
                'user_id' => $user->id,
                'employee_id' => $user->employee_id,
                'course_id' => $courseId,
                'course_title' => $course->title
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Course access request submitted successfully. You will be notified once it\'s reviewed.',
                'request_id' => $requestId
            ]);

        } catch (\Exception $e) {
            Log::error('Error requesting course access: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'course_id' => $request->input('course_id'),
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'error_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit course request. Please try again or contact support if the problem persists.'
            ], 500);
        }
    }

    /**
     * Get course requests for admin/HR review (future functionality)
     */
    public function getCourseRequests()
    {
        try {
            $requests = DB::connection('training_management')
                ->table('course_requests')
                ->orderBy('requested_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'requests' => $requests
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching course requests: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load course requests'
            ], 500);
        }
    }

    /**
     * Approve or deny course request (future functionality)
     */
    public function processCourseRequest(Request $request, $requestId)
    {
        try {
            $action = $request->input('action'); // 'approve' or 'deny'
            $notes = $request->input('notes', '');

            if (!in_array($action, ['approve', 'deny'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid action. Must be approve or deny.'
                ], 400);
            }

            $status = $action === 'approve' ? 'approved' : 'denied';

            // Update request status
            $updated = DB::connection('training_management')
                ->table('course_requests')
                ->where('id', $requestId)
                ->update([
                    'status' => $status,
                    'reviewed_by' => Auth::id(),
                    'review_notes' => $notes,
                    'reviewed_at' => now(),
                    'updated_at' => now()
                ]);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course request not found'
                ], 404);
            }

            // If approved, create training assignment (future integration)
            if ($status === 'approved') {
                // TODO: Create training assignment in training_management system
                // This would integrate with the existing training assignment workflow
            }

            return response()->json([
                'success' => true,
                'message' => 'Course request ' . $status . ' successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error processing course request: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process course request'
            ], 500);
        }
    }

    /**
     * Approve a course request
     */
    public function approveRequest(Request $request, $id)
    {
        try {
            // Get the request from course_requests table
            $courseRequest = DB::connection('training_management')
                ->table('course_requests')
                ->where('id', $id)
                ->first();

            if (!$courseRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request not found.'
                ]);
            }

            if ($courseRequest->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'This request has already been processed.'
                ]);
            }

            // Update the request status to approved
            DB::connection('training_management')
                ->table('course_requests')
                ->where('id', $id)
                ->update([
                    'status' => 'approved',
                    'admin_notes' => $request->input('admin_notes'),
                    'approved_at' => now(),
                    'approved_by' => auth()->id(),
                    'updated_at' => now()
                ]);

            // Grant access to the course by adding to user_courses table
            // Check if user_courses table exists, if not, create it
            try {
                $existingAccess = DB::connection('training_management')
                    ->table('user_courses')
                    ->where('user_id', $courseRequest->user_id)
                    ->where('course_id', $courseRequest->course_id)
                    ->first();

                if (!$existingAccess) {
                    DB::connection('training_management')
                        ->table('user_courses')
                        ->insert([
                            'user_id' => $courseRequest->user_id,
                            'course_id' => $courseRequest->course_id,
                            'granted_at' => now(),
                            'granted_by' => auth()->id(),
                            'status' => 'active',
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                }
            } catch (\Exception $e) {
                // If user_courses table doesn't exist, create it dynamically
                \Log::info('Creating user_courses table...');
                DB::connection('training_management')->statement('
                    CREATE TABLE IF NOT EXISTS user_courses (
                        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        user_id BIGINT UNSIGNED NOT NULL,
                        course_id BIGINT UNSIGNED NOT NULL,
                        granted_at TIMESTAMP NULL,
                        granted_by BIGINT UNSIGNED NULL,
                        status VARCHAR(50) DEFAULT "active",
                        created_at TIMESTAMP NULL,
                        updated_at TIMESTAMP NULL,
                        UNIQUE KEY unique_user_course (user_id, course_id)
                    )
                ');
                
                // Try inserting again
                DB::connection('training_management')
                    ->table('user_courses')
                    ->insert([
                        'user_id' => $courseRequest->user_id,
                        'course_id' => $courseRequest->course_id,
                        'granted_at' => now(),
                        'granted_by' => auth()->id(),
                        'status' => 'active',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Course request approved successfully. The employee now has access to the course.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error approving course request: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while approving the request.'
            ]);
        }
    }

    /**
     * Deny a course request
     */
    public function denyRequest(Request $request, $id)
    {
        try {
            // Validate that admin_notes is provided for denial
            $request->validate([
                'admin_notes' => 'required|string|max:1000'
            ]);

            $courseRequest = DB::connection('training_management')
                ->table('course_requests')
                ->where('id', $id)
                ->first();

            if (!$courseRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request not found.'
                ]);
            }

            if ($courseRequest->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'This request has already been processed.'
                ]);
            }

            // Update the request status to denied
            DB::connection('training_management')
                ->table('course_requests')
                ->where('id', $id)
                ->update([
                    'status' => 'denied',
                    'admin_notes' => $request->input('admin_notes'),
                    'denied_at' => now(),
                    'denied_by' => auth()->id(),
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Course request has been denied.'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a reason for denying this request.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error denying course request: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while denying the request.'
            ]);
        }
    }

    /**
     * Handle bulk actions on multiple requests
     */
    public function bulkAction(Request $request)
    {
        try {
            $request->validate([
                'action' => 'required|in:approve,deny',
                'request_ids' => 'required|array',
                'request_ids.*' => 'integer',
                'admin_notes' => 'required_if:action,deny|string|max:1000'
            ]);

            $action = $request->input('action');
            $requestIds = $request->input('request_ids');
            $adminNotes = $request->input('admin_notes');

            // Get all pending requests
            $courseRequests = DB::connection('training_management')
                ->table('course_requests')
                ->whereIn('id', $requestIds)
                ->where('status', 'pending')
                ->get();

            if ($courseRequests->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No pending requests found to process.'
                ]);
            }

            $processedCount = 0;

            foreach ($courseRequests as $courseRequest) {
                if ($action === 'approve') {
                    // Update request to approved
                    DB::connection('training_management')
                        ->table('course_requests')
                        ->where('id', $courseRequest->id)
                        ->update([
                            'status' => 'approved',
                            'admin_notes' => $adminNotes,
                            'approved_at' => now(),
                            'approved_by' => auth()->id(),
                            'updated_at' => now()
                        ]);

                    // Grant course access
                    try {
                        $existingAccess = DB::connection('training_management')
                            ->table('user_courses')
                            ->where('user_id', $courseRequest->user_id)
                            ->where('course_id', $courseRequest->course_id)
                            ->first();

                        if (!$existingAccess) {
                            DB::connection('training_management')
                                ->table('user_courses')
                                ->insert([
                                    'user_id' => $courseRequest->user_id,
                                    'course_id' => $courseRequest->course_id,
                                    'granted_at' => now(),
                                    'granted_by' => auth()->id(),
                                    'status' => 'active',
                                    'created_at' => now(),
                                    'updated_at' => now()
                                ]);
                        }
                    } catch (\Exception $e) {
                        // If user_courses table doesn't exist, create it dynamically
                        \Log::info('Creating user_courses table for bulk action...');
                        DB::connection('training_management')->statement('
                            CREATE TABLE IF NOT EXISTS user_courses (
                                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                user_id BIGINT UNSIGNED NOT NULL,
                                course_id BIGINT UNSIGNED NOT NULL,
                                granted_at TIMESTAMP NULL,
                                granted_by BIGINT UNSIGNED NULL,
                                status VARCHAR(50) DEFAULT "active",
                                created_at TIMESTAMP NULL,
                                updated_at TIMESTAMP NULL,
                                UNIQUE KEY unique_user_course (user_id, course_id)
                            )
                        ');
                        
                        // Try inserting again
                        DB::connection('training_management')
                            ->table('user_courses')
                            ->insert([
                                'user_id' => $courseRequest->user_id,
                                'course_id' => $courseRequest->course_id,
                                'granted_at' => now(),
                                'granted_by' => auth()->id(),
                                'status' => 'active',
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                    }
                } else {
                    // Update request to denied
                    DB::connection('training_management')
                        ->table('course_requests')
                        ->where('id', $courseRequest->id)
                        ->update([
                            'status' => 'denied',
                            'admin_notes' => $adminNotes,
                            'denied_at' => now(),
                            'denied_by' => auth()->id(),
                            'updated_at' => now()
                        ]);
                }

                $processedCount++;
            }

            $actionText = $action === 'approve' ? 'approved' : 'denied';
            return response()->json([
                'success' => true,
                'message' => "{$processedCount} course request(s) have been {$actionText} successfully."
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in bulk action: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing the requests.'
            ]);
        }
    }
}