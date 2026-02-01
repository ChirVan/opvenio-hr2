<?php

namespace App\Modules\training_management\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\training_management\Models\TrainingCatalog;
use App\Modules\training_management\Models\TrainingMaterial;
use App\Modules\training_management\Models\TrainingRoomBooking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class TrainingRoomController extends Controller
{
    /**
     * Display the training room index.
     */
    public function index(Request $request)
    {
        // Get all active training catalogs
        $catalogs = TrainingCatalog::with(['framework', 'materials' => function($query) {
            $query->where('status', 'published');
        }])
        ->orderBy('title')
        ->get();

        // Get statistics
        $stats = [
            'total_courses' => $catalogs->count(),
            'total_materials' => TrainingMaterial::where('status', 'published')->count(),
            'active_learners' => $this->getActiveLearners(),
        ];

        return view('training_management.room', compact('catalogs', 'stats'));
    }

    /**
     * Show a specific training room/course.
     */
    public function show($id)
    {
        $catalog = TrainingCatalog::with(['framework', 'materials' => function($query) {
            $query->where('status', 'published')->orderBy('order', 'asc');
        }])->findOrFail($id);

        return view('training_management.room-detail', compact('catalog'));
    }

    /**
     * Get all bookings (API)
     */
    public function getBookings(Request $request)
    {
        try {
            $query = TrainingRoomBooking::with('trainingCatalog')
                ->orderBy('created_at', 'desc');

            // Apply filters
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            if ($request->has('course_id') && $request->course_id) {
                $query->where('training_catalog_id', $request->course_id);
            }

            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('course_name', 'like', "%{$search}%")
                      ->orWhere('booking_code', 'like', "%{$search}%")
                      ->orWhere('facilitator', 'like', "%{$search}%");
                });
            }

            $bookings = $query->get();

            return response()->json([
                'success' => true,
                'bookings' => $bookings->map(function($booking) {
                    return [
                        'id' => $booking->id,
                        'booking_code' => $booking->booking_code,
                        'title' => $booking->course_name,
                        'courseId' => $booking->training_catalog_id,
                        'courseName' => $booking->course_name,
                        'sessionDate' => $booking->session_date->format('Y-m-d'),
                        'roomLocation' => $booking->location,
                        'startTime' => $booking->start_time,
                        'endTime' => $booking->end_time,
                        'facilitator' => $booking->facilitator,
                        'notes' => $booking->notes,
                        'attendees' => $booking->attendees,
                        'attendeeCount' => $booking->attendee_count,
                        'status' => $booking->status,
                        'createdAt' => $booking->created_at->toISOString(),
                    ];
                }),
                'stats' => [
                    'total' => TrainingRoomBooking::count(),
                    'upcoming' => TrainingRoomBooking::upcoming()->count(),
                    'totalAttendees' => TrainingRoomBooking::sum('attendee_count'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new booking (API)
     */
    public function storeBooking(Request $request)
    {
        try {
            $validated = $request->validate([
                'training_catalog_id' => 'required|exists:training_management.training_catalogs,id',
                'course_name' => 'required|string|max:255',
                'session_date' => 'required|date',
                'location' => 'nullable|string|max:255',
                'start_time' => 'nullable',
                'end_time' => 'nullable',
                'facilitator' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
                'attendees' => 'required|array|min:1',
            ]);

            $booking = TrainingRoomBooking::create([
                'booking_code' => TrainingRoomBooking::generateBookingCode(),
                'training_catalog_id' => $validated['training_catalog_id'],
                'course_name' => $validated['course_name'],
                'session_date' => $validated['session_date'],
                'location' => $validated['location'] ?? 'Training Room',
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'facilitator' => $validated['facilitator'],
                'notes' => $validated['notes'],
                'attendees' => $validated['attendees'],
                'attendee_count' => count($validated['attendees']),
                'status' => 'pending',
                'created_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'booking' => [
                    'id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'title' => $booking->course_name,
                    'courseId' => $booking->training_catalog_id,
                    'courseName' => $booking->course_name,
                    'sessionDate' => $booking->session_date->format('Y-m-d'),
                    'roomLocation' => $booking->location,
                    'startTime' => $booking->start_time,
                    'endTime' => $booking->end_time,
                    'facilitator' => $booking->facilitator,
                    'notes' => $booking->notes,
                    'attendees' => $booking->attendees,
                    'attendeeCount' => $booking->attendee_count,
                    'status' => $booking->status,
                    'createdAt' => $booking->created_at->toISOString(),
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update booking status (API)
     */
    public function updateBookingStatus(Request $request, $id)
    {
        try {
            $booking = TrainingRoomBooking::findOrFail($id);
            
            $validated = $request->validate([
                'status' => 'required|in:pending,approved,ongoing,completed,cancelled'
            ]);

            $booking->update(['status' => $validated['status']]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'booking' => $booking
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a booking (API)
     */
    public function deleteBooking($id)
    {
        try {
            $booking = TrainingRoomBooking::findOrFail($id);
            $booking->delete();

            return response()->json([
                'success' => true,
                'message' => 'Booking deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active learners count (from assignments).
     */
    private function getActiveLearners()
    {
        try {
            return \DB::connection('training_management')
                ->table('training_assignments')
                ->where('status', 'active')
                ->distinct('employee_id')
                ->count('employee_id');
        } catch (\Exception $e) {
            return 0;
        }
    }
}
