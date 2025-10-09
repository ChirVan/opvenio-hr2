<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'employee_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string|array $role): bool
    {
        if (is_array($role)) {
            return in_array($this->role, $role);
        }
        
        return $this->role === $role;
    }

    /**
     * Check if user is an employee.
     */
    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    /**
     * Check if user is HR.
     */
    public function isHr(): bool
    {
        return $this->role === 'hr';
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user can access admin features.
     */
    public function canAccessAdmin(): bool
    {
        return in_array($this->role, ['admin', 'hr']);
    }

    /**
     * Get the user's training assignments
     */
    public function trainingAssignments()
    {
        return $this->hasManyThrough(
            'App\Modules\training_management\Models\TrainingAssignment',
            'App\Modules\training_management\Models\TrainingAssignmentEmployee',
            'employee_id', // Foreign key on TrainingAssignmentEmployee table
            'id', // Foreign key on TrainingAssignment table
            'employee_id', // Local key on User table
            'training_assignment_id' // Local key on TrainingAssignmentEmployee table
        );
    }

    /**
     * Get the user's assessment assignments
     */
    public function assessmentAssignments()
    {
        return $this->hasMany(
            'App\Modules\learning_management\Models\AssessmentAssignment',
            'employee_id',
            'employee_id'
        );
    }
}
