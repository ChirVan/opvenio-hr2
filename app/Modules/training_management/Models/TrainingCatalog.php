<?php

namespace App\Modules\training_management\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\competency_management\Models\CompetencyFramework;

class TrainingCatalog extends Model
{
    protected $connection = 'training_management';
    
    protected $fillable = [
        'title',
        'label',
        'description',
        'is_active',
        'framework_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the framework that owns the training catalog.
     */
    public function framework(): BelongsTo
    {
        return $this->belongsTo(CompetencyFramework::class);
    }

    /**
     * Get the training materials for the catalog.
     */
    public function trainingMaterials()
    {
        return $this->hasMany(TrainingMaterial::class);
    }

    /**
     * Scope a query to only include active catalogs.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the status color class for display.
     */
    public function getStatusColorAttribute()
    {
        return $this->is_active ? 'text-green-600' : 'text-red-600';
    }

    /**
     * Get the formatted status text.
     */
    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }
}
