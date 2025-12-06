<?php

namespace App\Modules\competency_management\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignedCompetency extends Model
{
    use HasFactory;

    // Specify the database connection for this model
    protected $connection = 'competency_management';

    protected $fillable = [
        'employee_id',
        'employee_name',
        'job_title',
        'competency_id',
        'framework_id',
        'assignment_type',
        'proficiency_level',
        'priority',
        'target_date',
        'notes',
        'status',
        'progress_percentage',
        'assigned_by',
        'assigned_at',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'target_date' => 'date',
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'progress_percentage' => 'integer',
    ];

    // Relationship with Competency
    public function competency()
    {
        return $this->belongsTo(Competency::class, 'competency_id');
    }

    // Relationship with CompetencyFramework
    public function framework()
    {
        return $this->belongsTo(CompetencyFramework::class, 'framework_id');
    }

    // Relationship with User (assigned by)
    public function assignedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['assigned', 'in_progress']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // Accessors
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'assigned' => 'text-blue-600 bg-blue-100',
            'in_progress' => 'text-yellow-600 bg-yellow-100',
            'completed' => 'text-green-600 bg-green-100',
            'on_hold' => 'text-gray-600 bg-gray-100',
            'cancelled' => 'text-red-600 bg-red-100',
            default => 'text-gray-600 bg-gray-100'
        };
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'critical' => 'text-red-600 bg-red-100',
            'high' => 'text-orange-600 bg-orange-100',
            'medium' => 'text-yellow-600 bg-yellow-100',
            'low' => 'text-blue-600 bg-blue-100',
            default => 'text-gray-600 bg-gray-100'
        };
    }

    public function getProgressStatusAttribute()
    {
        if ($this->progress_percentage >= 100) {
            return 'completed';
        } elseif ($this->progress_percentage >= 75) {
            return 'nearly_complete';
        } elseif ($this->progress_percentage >= 50) {
            return 'in_progress';
        } elseif ($this->progress_percentage > 0) {
            return 'started';
        } else {
            return 'not_started';
        }
    }
}
