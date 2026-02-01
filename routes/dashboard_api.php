<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardRealtimeController;

// Real-time dashboard data endpoint
Route::get('/dashboard/realtime', [DashboardRealtimeController::class, 'realtimeData']);
