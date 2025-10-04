<?php

namespace App\Modules\competency_management\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\competency_management\Models\Employee;

class GapAnalysis extends Model
{
    protected $connection = 'competency_management';
    protected $table = 'gap_analyses';

    protected $fillable = [
        'employee_id', // This stores the external API employee ID (not local DB reference)
        'competency_id',
        'framework',
        'proficiency_level',
        'notes',
        'assessment_date',
        'status',
    ];

    protected $casts = [
        'assessment_date' => 'date',
    ];

    /**
     * Get employee data from external API
     * Note: employee_id refers to external API employee ID, not local database
     */
    public function getEmployeeData()
    {
        $employeeService = app(\App\Services\EmployeeApiService::class);
        return $employeeService->getEmployee($this->employee_id);
    }

    public function employee()
    {
        // This method is kept for backwards compatibility but returns null
        // since we don't have a direct database relationship anymore
        // Use getEmployeeData() method instead to fetch from external API
        return null;
    }

    public function competency()
    {
        return $this->belongsTo(Competency::class, 'competency_id');
    }
}