<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardRealtimeController extends Controller
{
    public function realtimeData(Request $request)
    {
        // Example: Fetch real-time data for dashboard metrics
        // Replace with actual queries/models as needed

        // 1. Trainings: training_materials table in training_management DB
        $trainingCount = DB::connection('training_management')->table('training_materials')->count();
        $trainingMonthly = DB::connection('training_management')->table('training_materials')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get();

        // 2. Competencies: competencies table in competency_managements DB
        $competencyCount = DB::connection('competency_managements')->table('competencies')->count();
        $competencyMonthly = DB::connection('competency_managements')->table('competencies')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get();

        // 3. Successions: promotions table in succession_planning DB
        $successionCount = DB::connection('succession_planning')->table('promotions')->count();
        $successionMonthly = DB::connection('succession_planning')->table('promotions')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get();

        // 4. Learning Modules: assessment_categories table in learning_management DB
        $learningModulesCount = DB::connection('learning_management')->table('assessment_categories')->count();
        $learningMonthly = DB::connection('learning_management')->table('assessment_categories')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get();

        return response()->json([
            'trainingCount' => $trainingCount,
            'competencyCount' => $competencyCount,
            'successionCount' => $successionCount,
            'learningModulesCount' => $learningModulesCount,
            'trainingMonthly' => $trainingMonthly,
            'competencyMonthly' => $competencyMonthly,
            'successionMonthly' => $successionMonthly,
            'learningMonthly' => $learningMonthly,
        ]);
    }
}
