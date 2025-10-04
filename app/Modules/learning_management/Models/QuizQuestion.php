<?php

namespace App\Modules\learning_management\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizQuestion extends Model
{
    use HasFactory;

    /**
     * The database connection that should be used by the model.
     */
    protected $connection = 'learning_management';

    /**
     * The table associated with the model.
     */
    protected $table = 'quiz_questions';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'quiz_id',
        'question',
        'correct_answer',
        'points',
        'question_order',
        'question_type'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the quiz that owns the question.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    /**
     * Scope a query to only include identification questions.
     */
    public function scopeIdentification($query)
    {
        return $query->where('question_type', 'identification');
    }

    /**
     * Get the formatted question number.
     */
    public function getQuestionNumberAttribute(): string
    {
        return "Question {$this->question_order}";
    }

    /**
     * Check if the provided answer is correct.
     */
    public function isCorrectAnswer(string $answer): bool
    {
        return trim(strtolower($answer)) === trim(strtolower($this->correct_answer));
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($question) {
            $question->quiz->updateTotals();
        });

        static::updated(function ($question) {
            $question->quiz->updateTotals();
        });

        static::deleted(function ($question) {
            $question->quiz->updateTotals();
        });
    }
}