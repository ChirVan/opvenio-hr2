<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'icon',
        'icon_color',
        'link',
        'data',
        'is_read',
        'is_global',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'is_global' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Notification types
     */
    const TYPE_HR4_SYNC = 'hr4_sync';
    const TYPE_HR4_NEW_EMPLOYEE = 'hr4_new_employee';
    const TYPE_TRAINING_ROOM_APPROVED = 'training_room_approved';
    const TYPE_TRAINING_ROOM_REJECTED = 'training_room_rejected';
    const TYPE_COURSE_REQUEST = 'course_request';
    const TYPE_COURSE_APPROVED = 'course_approved';
    const TYPE_COURSE_DENIED = 'course_denied';
    const TYPE_ASSESSMENT_ASSIGNED = 'assessment_assigned';
    const TYPE_TRAINING_ASSIGNED = 'training_assigned';
    const TYPE_SYSTEM = 'system';

    /**
     * Get the user that owns the notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for user notifications (including global)
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where('user_id', $userId)
              ->orWhere('is_global', true);
        });
    }

    /**
     * Scope for recent notifications
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
        return $this;
    }

    /**
     * Create HR4 sync notification
     */
    public static function createHr4SyncNotification($created, $updated, $skipped, $errors)
    {
        $message = "HR4 Sync completed: {$created} created, {$updated} updated";
        if ($skipped > 0) {
            $message .= ", {$skipped} skipped";
        }
        if ($errors > 0) {
            $message .= ", {$errors} errors";
        }

        return self::create([
            'user_id' => auth()->id(),
            'type' => self::TYPE_HR4_SYNC,
            'title' => 'HR4 Sync Completed',
            'message' => $message,
            'icon' => 'bx-sync',
            'icon_color' => $errors > 0 ? 'text-amber-500' : 'text-emerald-500',
            'data' => [
                'created' => $created,
                'updated' => $updated,
                'skipped' => $skipped,
                'errors' => $errors,
            ],
            'is_global' => true, // Notify all HR admins
        ]);
    }

    /**
     * Create new employee notification (from HR4)
     */
    public static function createNewEmployeeNotification($employeeName, $employeeId, $count = 1)
    {
        if ($count > 1) {
            return self::create([
                'type' => self::TYPE_HR4_NEW_EMPLOYEE,
                'title' => 'New Employees Added',
                'message' => "{$count} new employees have been added from HR4 system.",
                'icon' => 'bx-user-plus',
                'icon_color' => 'text-blue-500',
                'data' => ['count' => $count],
                'is_global' => true,
            ]);
        }

        return self::create([
            'type' => self::TYPE_HR4_NEW_EMPLOYEE,
            'title' => 'New Employee Added',
            'message' => "{$employeeName} has been added from HR4 system.",
            'icon' => 'bx-user-plus',
            'icon_color' => 'text-blue-500',
            'link' => '/employees', // or relevant link
            'data' => [
                'employee_id' => $employeeId,
                'employee_name' => $employeeName,
            ],
            'is_global' => true,
        ]);
    }

    /**
     * Create training room booking approval notification
     */
    public static function createTrainingRoomApprovedNotification($booking)
    {
        return self::create([
            'type' => self::TYPE_TRAINING_ROOM_APPROVED,
            'title' => 'Training Room Approved',
            'message' => "Training room booking '{$booking->booking_code}' for '{$booking->course_name}' has been approved.",
            'icon' => 'bx-check-circle',
            'icon_color' => 'text-emerald-500',
            'link' => '/training/room',
            'data' => [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'course_name' => $booking->course_name,
                'session_date' => $booking->session_date?->format('M d, Y'),
            ],
            'is_global' => true,
        ]);
    }

    /**
     * Create training room booking rejection notification
     */
    public static function createTrainingRoomRejectedNotification($booking)
    {
        return self::create([
            'type' => self::TYPE_TRAINING_ROOM_REJECTED,
            'title' => 'Training Room Rejected',
            'message' => "Training room booking '{$booking->booking_code}' for '{$booking->course_name}' has been rejected.",
            'icon' => 'bx-x-circle',
            'icon_color' => 'text-red-500',
            'link' => '/training/room',
            'data' => [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'course_name' => $booking->course_name,
            ],
            'is_global' => true,
        ]);
    }

    /**
     * Create course request approved notification
     */
    public static function createCourseApprovedNotification($request)
    {
        return self::create([
            'type' => self::TYPE_COURSE_APPROVED,
            'title' => 'Course Request Approved',
            'message' => "Course request for '{$request->course_title}' by {$request->employee_name} has been approved.",
            'icon' => 'bx-book-open',
            'icon_color' => 'text-emerald-500',
            'link' => '/training/grant-request',
            'data' => [
                'request_id' => $request->id,
                'course_title' => $request->course_title,
                'employee_name' => $request->employee_name,
            ],
            'is_global' => true,
        ]);
    }

    /**
     * Get icon HTML
     */
    public function getIconHtml()
    {
        return "<i class='bx {$this->icon} {$this->icon_color}'></i>";
    }

    /**
     * Get time ago string
     */
    public function getTimeAgo()
    {
        return $this->created_at->diffForHumans();
    }
}
