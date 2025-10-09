<?php

namespace App\Modules\learning_management\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAnswer extends Model
{
    use HasFactory;

    protected $connection = 'ess'; // This model uses the ESS database
    protected $table = 'user_answers';

    protected $fillable = [
        'result_id',
        'question_id',
        'user_answer',
        'correct_answer',
        'is_correct',
        'points_earned',
        'points_possible'
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'points_earned' => 'integer',
        'points_possible' => 'integer',
    ];

    /**
     * Get the assessment result that this answer belongs to
     */
    public function assessmentResult()
    {
        return $this->belongsTo(AssessmentResult::class, 'result_id');
    }

    /**
     * Get the question that this answer belongs to
     */
    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id');
    }

    /**
     * Get the status badge class for UI
     */
    public function getStatusBadgeClass()
    {
        return $this->is_correct ? 'bg-success' : 'bg-danger';
    }

    /**
     * Get the status text
     */
    public function getStatusText()
    {
        return $this->is_correct ? 'Correct' : 'Incorrect';
    }

    /**
     * Get the score text
     */
    public function getScoreText()
    {
        return "{$this->points_earned}/{$this->points_possible}";
    }

    /**
     * Check if the answer is blank/empty
     */
    public function isBlank()
    {
        return empty(trim($this->user_answer));
    }

    /**
     * Get formatted user answer for display
     */
    public function getFormattedUserAnswer()
    {
        return $this->isBlank() ? '<em class="text-muted">(No answer provided)</em>' : e($this->user_answer);
    }
}