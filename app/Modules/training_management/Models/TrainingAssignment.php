<?php

namespace App\Modules\training_management\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Modules\competency_management\Models\GapAnalysis;

class TrainingAssignment extends Model
{
    use HasFactory;

    protected $connection = 'training_management';
    protected $table = 'training_assignments';

    protected $fillable = [
        'assignment_title',
        'training_catalog_id',
        'priority',
        'assignment_type',
        'start_date',
        'due_date',
        'instructions',
        'status',
        'created_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be appended to the model's array form.
     */
    protected $appends = ['status_badge', 'priority_badge'];

    /**
     * Get the training catalog that this assignment belongs to
     */
    public function trainingCatalog(): BelongsTo
    {
        return $this->belongsTo(TrainingCatalog::class, 'training_catalog_id');
    }

    /**
     * Get the user who created this assignment
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the training materials for this assignment
     */
    public function trainingMaterials(): BelongsToMany
    {
        return $this->belongsToMany(
            TrainingMaterial::class,
            'training_assignment_materials',
            'training_assignment_id',
            'training_material_id'
        )->withPivot(['is_required', 'order_sequence'])
         ->withTimestamps()
         ->orderBy('pivot_order_sequence');
    }

    /**
     * Get the employees assigned to this training
     */
    public function assignmentEmployees(): HasMany
    {
        return $this->hasMany(TrainingAssignmentEmployee::class, 'training_assignment_id');
    }

    /**
     * Get employees through the pivot table with additional data
     */
    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(
            \App\Modules\competency_management\Models\Employee::class,
            'training_assignment_employees',
            'training_assignment_id',
            'employee_id'
        )->withPivot([
            'status',
            'assigned_at',
            'started_at', 
            'completed_at',
            'notes',
            'progress_percentage'
        ])->withTimestamps();
    }

    /**
     * Scope: Get active assignments
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: Get assignments by priority
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope: Get overdue assignments
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('status', '!=', 'completed');
    }

    /**
     * Get status badge for display
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => 'bg-gray-100 text-gray-800',
            'active' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800'
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get priority badge for display
     */
    public function getPriorityBadgeAttribute()
    {
        $badges = [
            'low' => 'bg-green-100 text-green-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'high' => 'bg-orange-100 text-orange-800',
            'urgent' => 'bg-red-100 text-red-800'
        ];

        return $badges[$this->priority] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Check if assignment is overdue
     */
    public function getIsOverdueAttribute()
    {
        return $this->due_date < now() && $this->status !== 'completed';
    }

    /**
     * Get completion percentage across all assigned employees
     */
    public function getCompletionPercentageAttribute()
    {
        $totalEmployees = $this->assignmentEmployees()->count();
        if ($totalEmployees === 0) return 0;

        $completedEmployees = $this->assignmentEmployees()
            ->where('status', 'completed')
            ->count();

        return round(($completedEmployees / $totalEmployees) * 100, 2);
    }

    /**
     * Get days until due date
     */
    public function getDaysUntilDueAttribute()
    {
        return now()->diffInDays($this->due_date, false);
    }
}