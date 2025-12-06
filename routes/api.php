<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Modules\competency_management\Controllers\CompetencyGapAnalysisController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Assigned Competencies API
Route::get('/assigned-competencies', [CompetencyGapAnalysisController::class, 'getAssignedCompetencies']);
