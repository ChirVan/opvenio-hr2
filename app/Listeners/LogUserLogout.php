<?php
namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Models\AuditLog;

class LogUserLogout
{
    public function handle(Logout $event)
    {
        // Find the latest login log for this user and update time_out
        $log = AuditLog::where('user_id', $event->user->id)
            ->where('activity', 'Login')
            ->orderByDesc('id')
            ->first();
        if ($log) {
            $log->update(['time_out' => now(), 'activity' => 'Logout', 'details' => 'User logged out']);
        } else {
            AuditLog::create([
                'user_id' => $event->user->id,
                'user_name' => $event->user->name,
                'activity' => 'Logout',
                'details' => 'User logged out',
                'time_out' => now(),
                'status' => 'Success',
            ]);
        }
    }
}
