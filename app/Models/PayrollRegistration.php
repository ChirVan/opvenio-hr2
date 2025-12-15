<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollRegistration extends Model
{
    /**
     * The database connection that should be used by the model.
     */
    protected $connection = 'ess';

    /**
     * The table associated with the model.
     */
    protected $table = 'payroll_registrations';

    protected $fillable = [
        'user_id',
        'employee_id',
        'employee_name',
        'email',
        'payment_method',
        'bank_name',
        'bank_branch',
        'account_name',
        'account_number',
        'account_type',
        'id_type',
        'id_number',
        'proof_of_account_path',
        'valid_id_path',
        'status',
        'remarks',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Get the user who submitted the registration
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who approved/rejected the registration
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope for pending registrations
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved registrations
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected registrations
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
