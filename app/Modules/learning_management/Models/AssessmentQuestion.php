<?php

namespace App\Modules\learning_management\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentQuestion extends Model
{
    use HasFactory;

    protected $connection = 'learning_management';
    protected $table = 'assessment_questions';

    protected $fillable = [
        'assessment_id',
        'question_text',
        'question_type',
        'points',
        'order_sequence',
        'is_required'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'points' => 'decimal:2',
        'order_sequence' => 'integer',
    ];

    /**
     * Get the assessment that owns the question
     */
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }

    /**
     * Get the answer options for this question
     */
    public function options(): HasMany
    {
        return $this->hasMany(AssessmentQuestionOption::class, 'question_id');
    }

    /**
     * Scope: Order by sequence
     */
    public function scopeOrderBySequence($query)
    {
        return $query->orderBy('order_sequence');
    }
}