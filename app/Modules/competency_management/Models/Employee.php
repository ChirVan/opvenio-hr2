<?php

namespace App\Modules\competency_management\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $connection = 'competency_management';
    protected $table = 'employees';

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'phone',
        'job_role',
        'department',
        'hire_date',
        'status'
    ];

    protected $casts = [
        'hire_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the gap analyses for this employee
     */
    public function gapAnalyses(): HasMany
    {
        return $this->hasMany(GapAnalysis::class, 'employee_id');
    }

    /**
     * Get the employee's full name
     */
    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    /**
     * Get the employee's display name (Last, First)
     */
    public function getDisplayNameAttribute()
    {
        return $this->lastname . ', ' . $this->firstname;
    }

    /**
     * Scope: Get active employees
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: Get employees by department
     */
    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    /**
     * Scope: Get employees by job role
     */
    public function scopeByJobRole($query, $jobRole)
    {
        return $query->where('job_role', $jobRole);
    }
}