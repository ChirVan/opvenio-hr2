<?php

namespace App\Modules\competency_management\Models;
use App\Modules\competency_management\Models\RoleMapping;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competency extends Model
{
    use HasFactory;

    // Specify the database connection for this model
    protected $connection = 'competency_management';

    protected $fillable = [
        'competency_name',
        'description',
        'framework_id',
        'proficiency_levels',
        'status',
        'behavioral_indicators',
        'assessment_criteria',
        'notes'
    ];

    // Relationship with CompetencyFramework
    public function framework()
    {
        return $this->belongsTo(CompetencyFramework::class, 'framework_id');
    }

    // Scope for active competencies
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

    // Get framework badge color
    public function getFrameworkBadgeColorAttribute()
    {
        return match($this->framework->framework_name ?? '') {
            'Leadership Framework' => 'bg-green-100 text-green-800',
            'Technical Framework' => 'bg-purple-100 text-purple-800', 
            'Customer Service Framework' => 'bg-orange-100 text-orange-800',
            default => 'bg-blue-100 text-blue-800'
        };
    }

    public function roleMappings()
    {
        return $this->hasMany(RoleMapping::class, 'competency_id');
    }

}