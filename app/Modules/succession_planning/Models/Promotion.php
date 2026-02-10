<?php
namespace App\Modules\succession_planning\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $connection = 'succession_planning';
    protected $table = 'promotions';
    protected $fillable = [
        'employee_id',
        'employee_name',
        'employee_email',
        'job_title',
        'potential_job',
        'assessment_score',
        'category',
        'strengths',
        'recommendations',
        'status',
        'employee_response',
        'employee_response_note',
        'employee_responded_at',
    ];

    protected $casts = [
        'employee_responded_at' => 'datetime',
    ];
}
