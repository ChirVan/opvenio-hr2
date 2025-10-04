<?php

namespace App\Modules\competency_management\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleMapping extends Model
{
    use HasFactory;

    // Specify the database connection for this model
    protected $connection = 'competency_management';

    protected $fillable = [
        'role_name',
        'framework_id',
        'competency_id',
        'proficiency_level',
        'status',
        'notes'
    ];

    // Relationship with CompetencyFramework
    public function framework()
    {
        return $this->belongsTo(CompetencyFramework::class, 'framework_id');
    }

    // Relationship with Competency
    public function competency()
    {
        return $this->belongsTo(Competency::class, 'competency_id');
    }

    // Scope for active role mappings
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Get status color for display
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'active' => 'bg-green-100 text-green-800',
            'inactive' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Get status text color for display
    public function getStatusTextColorAttribute()
    {
        return match($this->status) {
            'active' => 'text-green-600',
            'inactive' => 'text-yellow-600',
            default => 'text-gray-600'
        };
    }

    // Get proficiency level badge color
    public function getProficiencyBadgeColorAttribute()
    {
        return match($this->proficiency_level) {
            'Beginner' => 'bg-red-100 text-red-800',
            'Intermediate' => 'bg-yellow-100 text-yellow-800',
            'Advanced' => 'bg-blue-100 text-blue-800',
            'Expert' => 'bg-purple-100 text-purple-800',
            'Master' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}