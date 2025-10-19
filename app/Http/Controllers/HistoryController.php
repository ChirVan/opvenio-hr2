<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\AuditLog;

class HistoryController extends Controller
{
	public function index()
	{
	// Login logs: only 'Login' and 'Logout' activities from audit_logs
	$logs = AuditLog::whereIn('activity', ['login', 'logout'])->latest()->paginate(10);


		// Activity logs: exclude 'Login' and 'Logout' activities from activity_logs
		$activityLogs = ActivityLog::whereNotIn('activity', ['login', 'logout'])->latest()->paginate(10);

		// Map activity type to label and color for display
		foreach ($activityLogs as $log) {
			$type = $log->activity;
			if ($type === 'create_framework' || $type === 'Create') {
				$log->activity_label = 'Create';
				$log->activity_class = 'bg-green-200 text-green-800';
			} elseif ($type === 'update_framework' || $type === 'Edit') {
				$log->activity_label = 'Edit';
				$log->activity_class = 'bg-blue-200 text-blue-800';
			} elseif ($type === 'delete_framework' || $type === 'Delete') {
				$log->activity_label = 'Delete';
				$log->activity_class = 'bg-red-200 text-red-800';
			} else {
				$log->activity_label = $type;
				$log->activity_class = '';
			}
		}

	return view('audit_logs.history', compact('logs', 'activityLogs'));
	}
}
