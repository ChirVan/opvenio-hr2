<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Modules\competency_management\Controllers\CompetencyGapAnalysisController;
use App\Http\Controllers\Api\LeaveApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Assigned Competencies API
Route::get('/assigned-competencies', [CompetencyGapAnalysisController::class, 'getAssignedCompetencies']);

// Leave API Endpoints (for other departments)
Route::prefix('leaves')->group(function () {
    // Fetch all leave requests (with optional filters)
    Route::get('/', [LeaveApiController::class, 'index']);
    
    // Fetch a single leave request
    Route::get('/{id}', [LeaveApiController::class, 'show']);
    
    // Create a new leave request
    Route::post('/', [LeaveApiController::class, 'store']);
    
    // Update a leave request
    Route::put('/{id}', [LeaveApiController::class, 'update']);
    Route::patch('/{id}', [LeaveApiController::class, 'update']);
    
    // Delete a leave request
    Route::delete('/{id}', [LeaveApiController::class, 'destroy']);
    
    // Bulk update leave statuses
    Route::post('/bulk-update-status', [LeaveApiController::class, 'bulkUpdateStatus']);
    
    // Get leave statistics
    Route::get('/stats/summary', [LeaveApiController::class, 'statistics']);
});
