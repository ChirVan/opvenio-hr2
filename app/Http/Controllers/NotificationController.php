<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get notifications for the current user
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $limit = $request->get('limit', 10);

        $notifications = Notification::forUser($userId)
            ->recent(30) // Last 30 days
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        $unreadCount = Notification::forUser($userId)
            ->unread()
            ->recent(30)
            ->count();

        return response()->json([
            'success' => true,
            'notifications' => $notifications->map(function($n) {
                return [
                    'id' => $n->id,
                    'type' => $n->type,
                    'title' => $n->title,
                    'message' => $n->message,
                    'icon' => $n->icon,
                    'icon_color' => $n->icon_color,
                    'link' => $n->link,
                    'data' => $n->data,
                    'is_read' => $n->is_read,
                    'time_ago' => $n->getTimeAgo(),
                    'created_at' => $n->created_at->format('M d, Y h:i A'),
                ];
            }),
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Get unread count only (for polling)
     */
    public function unreadCount()
    {
        $userId = Auth::id();
        
        $count = Notification::forUser($userId)
            ->unread()
            ->recent(30)
            ->count();

        return response()->json([
            'success' => true,
            'unread_count' => $count,
        ]);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = Notification::find($id);
        
        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        $userId = Auth::id();
        
        Notification::forUser($userId)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $notification = Notification::find($id);
        
        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
        }

        // Only allow deletion of user's own notifications (not global ones they just see)
        if (!$notification->is_global && $notification->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted'
        ]);
    }

    /**
     * Clear all notifications for user
     */
    public function clearAll()
    {
        $userId = Auth::id();
        
        // Delete user-specific notifications
        Notification::where('user_id', $userId)->delete();
        
        // For global notifications, just mark as read
        Notification::where('is_global', true)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications cleared'
        ]);
    }

    /**
     * Check for new data from HR4 API (polling endpoint)
     */
    public function checkHr4Updates()
    {
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(10)
                ->withOptions(['verify' => false])
                ->withHeaders(['X-API-Key' => 'b24e8778f104db434adedd4342e94d39cee6d0668ec595dc6f02c739c522b57a'])
                ->get('https://hr4.microfinancial-1.com/allemployees');

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Could not connect to HR4 API'
                ]);
            }

            $responseData = $response->json();
            $employees = $responseData['data'] ?? $responseData;

            // Count employees from API
            $apiCount = count($employees);

            // Count users with employee_id in our database
            $localCount = \App\Models\User::whereNotNull('employee_id')->count();

            // Check if there are new employees
            $newEmployees = $apiCount - $localCount;

            return response()->json([
                'success' => true,
                'api_count' => $apiCount,
                'local_count' => $localCount,
                'potential_new' => max(0, $newEmployees),
                'has_updates' => $newEmployees > 0,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking HR4: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Check for pending training room bookings
     */
    public function checkTrainingRoomUpdates()
    {
        try {
            $pendingCount = \App\Modules\training_management\Models\TrainingRoomBooking::where('status', 'pending')->count();
            $recentApproved = \App\Modules\training_management\Models\TrainingRoomBooking::where('status', 'approved')
                ->where('updated_at', '>=', now()->subHours(24))
                ->count();

            return response()->json([
                'success' => true,
                'pending_count' => $pendingCount,
                'recent_approved' => $recentApproved,
                'has_updates' => $pendingCount > 0 || $recentApproved > 0,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking training rooms: ' . $e->getMessage()
            ]);
        }
    }
}
