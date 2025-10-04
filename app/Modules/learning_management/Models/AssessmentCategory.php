<?php

namespace App\Modules\learning_management\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AssessmentCategory extends Model
{
    use HasFactory;

    protected $connection = 'learning_management';
    protected $table = 'assessment_categories';

    protected $fillable = [
        'category_name',
        'category_slug',
        'category_icon',
        'description',
        'color_theme',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];



    /**
     * The attributes that should be appended to the model's array form.
     */
    protected $appends = ['status_badge', 'color_classes'];

    /**
     * Get the user who created this category
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the quizzes that belong to this category
     */
    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class, 'category_id');
    }

    /**
     * Get published quizzes for this category
     */
    public function publishedQuizzes(): HasMany
    {
        return $this->quizzes()->where('status', 'published');
    }

    /**
     * Get the assessments for this category
     */
    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class, 'category_id');
    }

    /**
     * Scope: Get active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Get categories by color theme
     */
    public function scopeByColor($query, $color)
    {
        return $query->where('color_theme', $color);
    }

    /**
     * Scope: Order by name
     */
    public function scopeOrderByName($query)
    {
        return $query->orderBy('category_name');
    }

    /**
     * Get status badge for display
     */
    public function getStatusBadgeAttribute()
    {
        return $this->is_active 
            ? 'bg-green-100 text-green-800' 
            : 'bg-red-100 text-red-800';
    }

    /**
     * Get color classes for the category card
     */
    public function getColorClassesAttribute()
    {
        $colorMap = [
            'blue' => [
                'gradient' => 'from-blue-400 to-blue-600',
                'from' => 'from-blue-400',
                'to' => 'to-blue-600',
                'border' => 'border-blue-300',
                'bg' => 'bg-blue-500',
                'text' => 'text-blue-700'
            ],
            'green' => [
                'gradient' => 'from-green-400 to-green-600',
                'from' => 'from-green-400',
                'to' => 'to-green-600',
                'border' => 'border-green-300',
                'bg' => 'bg-green-500',
                'text' => 'text-green-700'
            ],
            'red' => [
                'gradient' => 'from-red-400 to-red-600',
                'from' => 'from-red-400',
                'to' => 'to-red-600',
                'border' => 'border-red-300',
                'bg' => 'bg-red-500',
                'text' => 'text-red-700'
            ],
            'purple' => [
                'gradient' => 'from-purple-400 to-purple-600',
                'from' => 'from-purple-400',
                'to' => 'to-purple-600',
                'border' => 'border-purple-300',
                'bg' => 'bg-purple-500',
                'text' => 'text-purple-700'
            ],
            'orange' => [
                'gradient' => 'from-orange-400 to-orange-600',
                'from' => 'from-orange-400',
                'to' => 'to-orange-600',
                'border' => 'border-orange-300',
                'bg' => 'bg-orange-500',
                'text' => 'text-orange-700'
            ],
            'teal' => [
                'gradient' => 'from-teal-400 to-teal-600',
                'from' => 'from-teal-400',
                'to' => 'to-teal-600',
                'border' => 'border-teal-300',
                'bg' => 'bg-teal-500',
                'text' => 'text-teal-700'
            ],
            'indigo' => [
                'gradient' => 'from-indigo-400 to-indigo-600',
                'from' => 'from-indigo-400',
                'to' => 'to-indigo-600',
                'border' => 'border-indigo-300',
                'bg' => 'bg-indigo-500',
                'text' => 'text-indigo-700'
            ],
            'pink' => [
                'gradient' => 'from-pink-400 to-pink-600',
                'from' => 'from-pink-400',
                'to' => 'to-pink-600',
                'border' => 'border-pink-300',
                'bg' => 'bg-pink-500',
                'text' => 'text-pink-700'
            ]
        ];

        return $colorMap[$this->color_theme] ?? $colorMap['blue'];
    }

    /**
     * Get assessments count
     */
    public function getAssessmentsCountAttribute()
    {
        try {
            return $this->assessments()->count();
        } catch (\Exception $e) {
            // Return 0 if assessments table doesn't exist yet
            return 0;
        }
    }

    /**
     * Get active assessments count
     */
    public function getActiveAssessmentsCountAttribute()
    {
        try {
            return $this->assessments()->where('is_active', true)->count();
        } catch (\Exception $e) {
            // Return 0 if assessments table doesn't exist yet
            return 0;
        }
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Ensure slug is always set and created_by is filled
        static::creating(function ($category) {
            if (empty($category->category_slug)) {
                $category->category_slug = Str::slug($category->category_name);
            }
            
            // Set created_by if not already set and user is authenticated
            if (empty($category->created_by) && Auth::check()) {
                $category->created_by = Auth::id();
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('category_name') && empty($category->category_slug)) {
                $category->category_slug = Str::slug($category->category_name);
            }
        });
    }
}