<?php
namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\AuditLog;

class LogUserLogin
{
    public function handle(Login $event)
    {
        AuditLog::create([
            'user_id' => $event->user->id,
            'user_name' => $event->user->name,
            'activity' => 'Login',
            'details' => 'User logged in',
            'time_in' => now(),
            'status' => 'Success',
        ]);
    }
}
