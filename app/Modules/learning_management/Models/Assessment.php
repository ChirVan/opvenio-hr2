<?php

namespace App\Modules\learning_management\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Assessment extends Model
{
    use HasFactory;

    protected $connection = 'learning_management';
    protected $table = 'assessments';

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'duration_minutes',
        'passing_score',
        'max_attempts',
        'is_active',
        'difficulty_level',
        'created_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'duration_minutes' => 'integer',
        'passing_score' => 'decimal:2',
        'max_attempts' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the category that owns the assessment
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(AssessmentCategory::class, 'category_id');
    }

    /**
     * Get the user who created this assessment
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the questions for this assessment
     */
    public function questions(): HasMany
    {
        return $this->hasMany(AssessmentQuestion::class, 'assessment_id');
    }

    /**
     * Scope: Get active assessments
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Get assessments by difficulty
     */
    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }

    /**
     * Get questions count
     */
    public function getQuestionsCountAttribute()
    {
        return $this->questions()->count();
    }
}