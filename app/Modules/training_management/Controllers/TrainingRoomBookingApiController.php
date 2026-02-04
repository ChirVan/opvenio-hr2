<?php

namespace App\Modules\training_management\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\training_management\Models\TrainingRoomBooking;
use App\Modules\training_management\Models\TrainingCatalog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TrainingRoomBookingApiController extends Controller
{
    /**
     * Get all bookings with optional filters
     * 
     * GET /api/training-room-bookings
     * Query params: status, course_id, search, date_from, date_to, per_page
     */
    public function index(Request $request)
    {
        try {
            $query = TrainingRoomBooking::query();

            // Filter by status
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // Filter by course/catalog
            if ($request->has('course_id') && $request->course_id) {
                $query->where('training_catalog_id', $request->course_id);
            }

            // Filter by date range
            if ($request->has('date_from') && $request->date_from) {
                $query->whereDate('session_date', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to) {
                $query->whereDate('session_date', '<=', $request->date_to);
            }

            // Search by course name, booking code, or facilitator
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('course_name', 'like', "%{$search}%")
                      ->orWhere('booking_code', 'like', "%{$search}%")
                      ->orWhere('facilitator', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%");
                });
            }

            // Order by
            $orderBy = $request->get('order_by', 'created_at');
            $orderDir = $request->get('order_dir', 'desc');
            $query->orderBy($orderBy, $orderDir);

            // Get all records without pagination
            $bookings = $query->get();

            return response()->json([
                'success' => true,
                'data' => $bookings,
                'message' => 'Bookings retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve bookings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get booking statistics
     * 
     * GET /api/training-room-bookings/stats
     */
    public function stats()
    {
        try {
            $today = now()->toDateString();

            $stats = [
                'total_bookings' => TrainingRoomBooking::count(),
                'pending_bookings' => TrainingRoomBooking::where('status', 'pending')->count(),
                'approved_bookings' => TrainingRoomBooking::where('status', 'approved')->count(),
                'ongoing_bookings' => TrainingRoomBooking::where('status', 'ongoing')->count(),
                'completed_bookings' => TrainingRoomBooking::where('status', 'completed')->count(),
                'cancelled_bookings' => TrainingRoomBooking::where('status', 'cancelled')->count(),
                'rejected_bookings' => TrainingRoomBooking::where('status', 'rejected')->count(),
                'upcoming_sessions' => TrainingRoomBooking::where('session_date', '>=', $today)
                    ->whereNotIn('status', ['cancelled', 'completed'])
                    ->count(),
                'total_attendees' => TrainingRoomBooking::sum('attendee_count'),
                'today_sessions' => TrainingRoomBooking::whereDate('session_date', $today)
                    ->whereNotIn('status', ['cancelled'])
                    ->count(),
                'this_week_sessions' => TrainingRoomBooking::whereBetween('session_date', [
                    now()->startOfWeek()->toDateString(),
                    now()->endOfWeek()->toDateString()
                ])->whereNotIn('status', ['cancelled'])->count(),
                'this_month_sessions' => TrainingRoomBooking::whereMonth('session_date', now()->month)
                    ->whereYear('session_date', now()->year)
                    ->whereNotIn('status', ['cancelled'])
                    ->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Statistics retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific booking
     * 
     * GET /api/training-room-bookings/{id}
     */
    public function show($id)
    {
        try {
            $booking = TrainingRoomBooking::find($id);

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $booking,
                'message' => 'Booking retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new booking
     * 
     * POST /api/training-room-bookings
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'training_catalog_id' => 'required|integer',
                'course_name' => 'required|string|max:255',
                'session_date' => 'required|date',
                'location' => 'nullable|string|max:255',
                'start_time' => 'nullable',
                'end_time' => 'nullable',
                'facilitator' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
                'attendees' => 'required|array|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verify training catalog exists
            $catalog = TrainingCatalog::find($request->training_catalog_id);
            if (!$catalog) {
                return response()->json([
                    'success' => false,
                    'message' => 'Training catalog not found'
                ], 404);
            }

            $booking = TrainingRoomBooking::create([
                'booking_code' => TrainingRoomBooking::generateBookingCode(),
                'training_catalog_id' => $request->training_catalog_id,
                'course_name' => $request->course_name,
                'session_date' => $request->session_date,
                'location' => $request->location ?? 'Training Room',
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'facilitator' => $request->facilitator,
                'notes' => $request->notes,
                'attendees' => $request->attendees,
                'attendee_count' => count($request->attendees),
                'status' => 'pending',
                'created_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'data' => $booking,
                'message' => 'Booking created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a booking
     * 
     * PUT/PATCH /api/training-room-bookings/{id}
     */
    public function update(Request $request, $id)
    {
        try {
            $booking = TrainingRoomBooking::find($id);

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'training_catalog_id' => 'sometimes|integer',
                'course_name' => 'sometimes|string|max:255',
                'session_date' => 'sometimes|date',
                'location' => 'nullable|string|max:255',
                'start_time' => 'nullable',
                'end_time' => 'nullable',
                'facilitator' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
                'attendees' => 'sometimes|array|min:1',
                'status' => 'sometimes|in:pending,approved,rejected,ongoing,completed,cancelled',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = $request->only([
                'training_catalog_id',
                'course_name',
                'session_date',
                'location',
                'start_time',
                'end_time',
                'facilitator',
                'notes',
                'attendees',
                'status'
            ]);

            // Update attendee count if attendees changed
            if (isset($updateData['attendees'])) {
                $updateData['attendee_count'] = count($updateData['attendees']);
            }

            $booking->update($updateData);

            return response()->json([
                'success' => true,
                'data' => $booking->fresh(),
                'message' => 'Booking updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update booking status only
     * 
     * PATCH /api/training-room-bookings/{id}/status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $booking = TrainingRoomBooking::find($id);

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'status' => 'required|in:pending,approved,rejected,ongoing,completed,cancelled',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $oldStatus = $booking->status;
            $newStatus = $request->status;
            
            $booking->update(['status' => $newStatus]);

            // Create notification for status changes
            if ($oldStatus !== $newStatus) {
                if ($newStatus === 'approved') {
                    \App\Models\Notification::createTrainingRoomApprovedNotification($booking);
                } elseif ($newStatus === 'rejected') {
                    \App\Models\Notification::createTrainingRoomRejectedNotification($booking);
                }
            }

            return response()->json([
                'success' => true,
                'data' => $booking->fresh(),
                'message' => 'Booking status updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update booking status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a booking
     * 
     * DELETE /api/training-room-bookings/{id}
     */
    public function destroy($id)
    {
        try {
            $booking = TrainingRoomBooking::find($id);

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            $bookingCode = $booking->booking_code;
            $booking->delete();

            return response()->json([
                'success' => true,
                'message' => "Booking {$bookingCode} deleted successfully"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
