<?php

namespace App\Modules\training_management\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\competency_management\Models\Competency;

class TrainingMaterial extends Model
{
    use SoftDeletes;

    /**
     * The connection name for the model.
     */
    protected $connection = 'training_management';

    /**
     * The table associated with the model.
     */
    protected $table = 'training_materials';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'lesson_title',
        'training_catalog_id',
        'competency_id',
        'proficiency_level',
        'lesson_content',
        'status',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'proficiency_level' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the training catalog that owns the material.
     */
    public function trainingCatalog()
    {
        return $this->belongsTo(TrainingCatalog::class, 'training_catalog_id');
    }

    /**
     * Get the competency associated with the material.
     */
    public function competency()
    {
        return $this->belongsTo(Competency::class, 'competency_id');
    }

    /**
     * Scope a query to only include active materials.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include published materials.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to only include draft materials.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Get the status badge color.
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'published' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800',
            'draft' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800',
            'archived' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800',
            default => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get the proficiency level name.
     */
    public function getProficiencyLevelNameAttribute()
    {
        return match($this->proficiency_level) {
            1 => 'Beginner',
            2 => 'Intermediate',
            3 => 'Expert',
            default => "Level {$this->proficiency_level}",
        };
    }

    /**
     * Get formatted lesson content excerpt.
     */
    public function getContentExcerptAttribute()
    {
        $plainText = strip_tags($this->lesson_content);
        return \Str::limit($plainText, 150);
    }

    /**
     * Check if the material is published.
     */
    public function isPublished()
    {
        return $this->status === 'published' && $this->is_active;
    }

    /**
     * Check if the material is draft.
     */
    public function isDraft()
    {
        return $this->status === 'draft';
    }
}