<?php

namespace App\Modules\learning_management\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssessmentResult extends Model
{
    use HasFactory;

    protected $connection = 'ess'; // This model uses the ESS database
    protected $table = 'assessment_results';

    protected $fillable = [
        'assignment_id',
        'employee_id',
        'quiz_id',
        'total_questions',
        'correct_answers',
        'score',
        'attempt_number',
        'status',
        'evaluation_status',
        'evaluation_notes',
        'evaluated_by',
        'evaluated_at',
        'started_at',
        'completed_at'
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'evaluated_at' => 'datetime',
    ];

    /**
     * Get the assignment that this result belongs to
     */
    public function assignment()
    {
        return $this->belongsTo(AssessmentAssignment::class, 'assignment_id');
    }

    /**
     * Get the quiz that this result belongs to
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    /**
     * Get the user answers for this result
     */
    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class, 'result_id');
    }

    /**
     * Get the evaluator (user who evaluated this result)
     */
    public function evaluator()
    {
        return $this->belongsTo(\App\Models\User::class, 'evaluated_by');
    }

    /**
     * Check if the result is pending evaluation
     */
    public function isPending()
    {
        return $this->evaluation_status === 'pending';
    }

    /**
     * Check if the result has been passed
     */
    public function isPassed()
    {
        return $this->evaluation_status === 'passed';
    }

    /**
     * Check if the result has been failed
     */
    public function isFailed()
    {
        return $this->evaluation_status === 'failed';
    }

    /**
     * Get the status badge class for UI
     */
    public function getStatusBadgeClass()
    {
        switch ($this->evaluation_status) {
            case 'passed':
                return 'bg-success';
            case 'failed':
                return 'bg-danger';
            case 'pending':
            default:
                return 'bg-warning';
        }
    }

    /**
     * Get formatted attempt text
     */
    public function getAttemptText()
    {
        return "Attempt {$this->attempt_number}";
    }

    /**
     * Get score percentage
     */
    public function getScorePercentage()
    {
        return $this->total_questions > 0 ? round(($this->correct_answers / $this->total_questions) * 100) : 0;
    }
}