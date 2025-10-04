<?php

namespace App\Modules\training_management\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingAssignmentEmployee extends Model
{
    use HasFactory;

    protected $connection = 'training_management';
    protected $table = 'training_assignment_employees';

    protected $fillable = [
        'training_assignment_id',
        'employee_id',
        'status',
        'assigned_at',
        'started_at',
        'completed_at',
        'notes',
        'progress_percentage'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'progress_percentage' => 'decimal:2'
    ];

    /**
     * Get the training assignment
     */
    public function trainingAssignment(): BelongsTo
    {
        return $this->belongsTo(TrainingAssignment::class, 'training_assignment_id');
    }

    /**
     * Get the employee (from competency_management database)
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\competency_management\Models\Employee::class, 'employee_id');
    }

    /**
     * Scope: Get completed assignments
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope: Get in progress assignments
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Mark as started
     */
    public function markAsStarted()
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => now()
        ]);
    }

    /**
     * Mark as completed
     */
    public function markAsCompleted($notes = null)
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'progress_percentage' => 100.00,
            'notes' => $notes
        ]);
    }

    /**
     * Update progress
     */
    public function updateProgress($percentage, $notes = null)
    {
        $data = [
            'progress_percentage' => min(100, max(0, $percentage))
        ];

        if ($percentage > 0 && $this->status === 'assigned') {
            $data['status'] = 'in_progress';
            $data['started_at'] = $this->started_at ?? now();
        }

        if ($percentage >= 100) {
            $data['status'] = 'completed';
            $data['completed_at'] = now();
        }

        if ($notes) {
            $data['notes'] = $notes;
        }

        $this->update($data);
    }
}