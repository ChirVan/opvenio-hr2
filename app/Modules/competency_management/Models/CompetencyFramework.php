<?php

namespace App\Modules\competency_management\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\competency_management\Models\Competency;
use App\Modules\competency_management\Models\RoleMapping;
use Carbon\Carbon;

class CompetencyFramework extends Model
{
    use HasFactory;

    protected $connection = 'competency_management';

    protected $fillable = [
        'framework_name',
        'description',
        'effective_date',
        'end_date',
        'status',
        'notes'
    ];

    protected $casts = [
        'effective_date' => 'date',
        'end_date' => 'date',
    ];

    // Scope for active frameworks
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Get status color for display
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'active' => 'text-green-600',
            'draft' => 'text-yellow-600',
            'inactive' => 'text-gray-600',
            'archived' => 'text-red-600',
            default => 'text-gray-600'
        };
    }

    /**
     * Get all competencies that belong to this framework
     */
    public function competencies()
    {
        return $this->hasMany(Competency::class, 'framework_id');
    }

    /**
     * Get only active competencies for this framework
     */
    public function activeCompetencies()
    {
        return $this->hasMany(Competency::class, 'framework_id')->where('status', 'active');
    }

    public function roleMappings()
    {
        return $this->hasMany(RoleMapping::class, 'framework_id');
    }

    /**
     * Get all training catalogs for this framework
     */
    public function trainingCatalogs()
    {
        return $this->hasMany(\App\Modules\training_management\Models\TrainingCatalog::class, 'framework_id');
    }

    /**
     * Get only active training catalogs for this framework
     */
    public function activeTrainingCatalogs()
    {
        return $this->hasMany(\App\Modules\training_management\Models\TrainingCatalog::class, 'framework_id')
                    ->where('is_active', true);
    }
}