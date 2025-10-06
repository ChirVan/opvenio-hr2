<?php

namespace App\Modules\learning_management\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class AssessmentAssignment extends Model
{
    use HasFactory;

    protected $connection = 'learning_management';
    protected $table = 'assessment_assignments';

    protected $fillable = [
        'assessment_category_id',
        'quiz_id',
        'employee_id',
        'employee_name',
        'employee_email',
        'duration',
        'start_date',
        'due_date',
        'max_attempts',
        'status',
        'attempts_used',
        'score',
        'started_at',
        'completed_at',
        'notes',
        'assignment_metadata',
        'assigned_by'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'due_date' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'assignment_metadata' => 'array',
        'score' => 'decimal:2'
    ];

    /**
     * Get the assessment category that owns the assignment.
     */
    public function assessmentCategory(): BelongsTo
    {
        return $this->belongsTo(AssessmentCategory::class);
    }

    /**
     * Get the quiz that owns the assignment.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get the user who assigned the assessment.
     * Note: This is a cross-database relationship and should be loaded separately if needed.
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Get the assigned by user details (helper method for cross-database relationship).
     */
    public function getAssignedByUser()
    {
        if (!$this->assigned_by) {
            return null;
        }
        
        return User::on('mysql')->find($this->assigned_by);
    }

    /**
     * Check if the assignment is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->due_date < now() && !in_array($this->status, ['completed', 'cancelled']);
    }

    /**
     * Check if the assignment is available (can be started).
     */
    public function isAvailable(): bool
    {
        return $this->start_date <= now() && $this->status === 'pending';
    }

    /**
     * Check if the employee can attempt the assessment.
     */
    public function canAttempt(): bool
    {
        if ($this->max_attempts === 'unlimited') {
            return true;
        }
        
        return $this->attempts_used < (int) $this->max_attempts;
    }

    /**
     * Get the employee's progress percentage.
     */
    public function getProgressPercentage(): int
    {
        switch ($this->status) {
            case 'pending':
                return 0;
            case 'in_progress':
                return 50;
            case 'completed':
                return 100;
            case 'overdue':
                return $this->completed_at ? 100 : 75;
            case 'cancelled':
                return 0;
            default:
                return 0;
        }
    }

    /**
     * Get the status badge color.
     */
    public function getStatusColor(): string
    {
        return match ($this->status) {
            'pending' => 'gray',
            'in_progress' => 'blue',
            'completed' => 'green',
            'overdue' => 'red',
            'cancelled' => 'gray',
            default => 'gray'
        };
    }

    /**
     * Get the formatted duration.
     */
    public function getFormattedDuration(): string
    {
        if ($this->duration < 60) {
            return $this->duration . ' minutes';
        }
        
        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;
        
        if ($minutes === 0) {
            return $hours . ' hour' . ($hours > 1 ? 's' : '');
        }
        
        return $hours . 'h ' . $minutes . 'm';
    }

    /**
     * Scope for filtering by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for filtering by employee.
     */
    public function scopeByEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Scope for assignments due within a certain timeframe.
     */
    public function scopeDueWithin($query, $days = 7)
    {
        return $query->where('due_date', '<=', now()->addDays($days))
                    ->where('due_date', '>=', now())
                    ->whereNotIn('status', ['completed', 'cancelled']);
    }

    /**
     * Scope for overdue assignments.
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->whereNotIn('status', ['completed', 'cancelled']);
    }
}