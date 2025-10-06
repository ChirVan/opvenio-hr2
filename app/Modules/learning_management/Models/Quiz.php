<?php

namespace App\Modules\learning_management\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Quiz extends Model
{
    use HasFactory;

    /**
     * The database connection that should be used by the model.
     */
    protected $connection = 'learning_management';

    /**
     * The table associated with the model.
     */
    protected $table = 'quizzes';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'quiz_title',
        'category_id',
        'competency_id',
        'description',
        'time_limit',
        'status',
        'total_questions',
        'total_points',
        'created_by'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the assessment category that owns the quiz.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(AssessmentCategory::class, 'category_id');
    }

    /**
     * Get the competency associated with this quiz.
     */
    public function competency(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\competency_management\Models\Competency::class, 'competency_id');
    }

    /**
     * Get the user who created this quiz.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->setConnection('mysql');
    }

    /**
     * Get the questions for the quiz.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class, 'quiz_id')->orderBy('question_order');
    }

    /**
     * Get the assessment assignments for this quiz.
     */
    public function assessmentAssignments(): HasMany
    {
        return $this->hasMany(AssessmentAssignment::class);
    }

    /**
     * Scope a query to only include published quizzes.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to only include draft quizzes.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Get the status badge color.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'published' => 'bg-green-100 text-green-800',
            'draft' => 'bg-yellow-100 text-yellow-800',
            'archived' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get the formatted time limit.
     */
    public function getFormattedTimeLimitAttribute(): string
    {
        $minutes = $this->time_limit;
        if ($minutes >= 60) {
            $hours = floor($minutes / 60);
            $remainingMinutes = $minutes % 60;
            return $remainingMinutes > 0 ? "{$hours}h {$remainingMinutes}m" : "{$hours}h";
        }
        return "{$minutes} minutes";
    }

    /**
     * Update the quiz totals (questions and points).
     */
    public function updateTotals(): void
    {
        $this->update([
            'total_questions' => $this->questions()->count(),
            'total_points' => $this->questions()->sum('points'),
        ]);
    }

    /**
     * Check if quiz can be published.
     */
    public function canBePublished(): bool
    {
        return $this->questions()->count() >= 1 && !empty($this->quiz_title);
    }
}