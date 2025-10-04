<?php

namespace App\Modules\competency_management\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\competency_management\Models\Employee;

class GapAnalysis extends Model
{
    protected $connection = 'competency_management';
    protected $table = 'gap_analyses';

    protected $fillable = [
        'employee_id',
        'competency_id',
        'framework',
        'proficiency_level',
        'notes',
    ];

    public function employee()
    {
        
    }

    public function competency()
    {
        return $this->belongsTo(Competency::class, 'competency_id');
    }
}