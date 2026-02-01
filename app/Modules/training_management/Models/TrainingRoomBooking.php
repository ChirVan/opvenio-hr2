<?php

namespace App\Modules\training_management\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingRoomBooking extends Model
{
    use HasFactory;

    protected $connection = 'training_management';
    protected $table = 'training_room_bookings';

    protected $fillable = [
        'booking_code',
        'training_catalog_id',
        'course_name',
        'session_date',
        'location',
        'start_time',
        'end_time',
        'facilitator',
        'notes',
        'attendees',
        'attendee_count',
        'status',
        'created_by',
    ];

    protected $casts = [
        'attendees' => 'array',
        'session_date' => 'date',
    ];

    /**
     * Get the training catalog associated with this booking
     */
    public function trainingCatalog()
    {
        return $this->belongsTo(TrainingCatalog::class, 'training_catalog_id');
    }

    /**
     * Generate a unique booking code
     */
    public static function generateBookingCode()
    {
        $prefix = 'BK';
        $date = now()->format('Ymd');
        $lastBooking = self::whereDate('created_at', today())->latest()->first();
        
        if ($lastBooking) {
            $lastNumber = intval(substr($lastBooking->booking_code, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $date . $newNumber;
    }

    /**
     * Scope for upcoming sessions
     */
    public function scopeUpcoming($query)
    {
        return $query->where('session_date', '>=', today())
                     ->where('status', '!=', 'cancelled');
    }

    /**
     * Scope for filtering by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
