<?php

namespace App\Modules\succession_planning\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TalentPoolController extends Controller
{
    /**
     * Display the talent pool with approved employees from promotions table
     */
    public function index()
    {
        // Get employees from promotions table (written by Training Evaluation when passed)
        $talentPool = DB::connection('succession_planning')
            ->table('promotions')
            ->where('status', 'pending') // Only show pending, not promoted
            ->orderBy('created_at', 'desc')
            ->get();

        // Process for display
        $processedTalentPool = $talentPool->map(function ($employee) {
            $recommendations = json_decode($employee->recommendations, true) ?? [];
            
            return (object) [
                'id' => $employee->id,
                'employee_id' => $employee->employee_id,
                'employee_name' => $employee->employee_name,
                'employee_email' => $employee->employee_email,
                'job_title' => $employee->job_title,
                'potential_job' => $employee->potential_job,
                'assessment_score' => $employee->assessment_score,
                'category' => $employee->category,
                'strengths' => $employee->strengths,
                'recommendations' => $recommendations,
                'ai_reasoning' => $recommendations['ai_reasoning'] ?? '',
                'match_score' => $recommendations['match_score'] ?? 0,
                'readiness' => $recommendations['readiness'] ?? 'Unknown',
                'status' => $employee->status,
                'created_at' => $employee->created_at,
                'updated_at' => $employee->updated_at,
            ];
        });

        // Get employee IDs that are already promoted (status = 'promoted')
        $promotedEmployeeIds = DB::connection('succession_planning')
            ->table('promotions')
            ->where('status', 'promoted')
            ->pluck('employee_id')
            ->toArray();

        return view('succession_planning.talent', compact('processedTalentPool', 'promotedEmployeeIds'));
    }

    /**
     * Show promotion form for a specific employee
     */
    public function showPotential($employee_id)
    {
        // Fetch from promotions table
        $talent = DB::connection('succession_planning')
            ->table('promotions')
            ->where('employee_id', $employee_id)
            ->first();

        if (!$talent) {
            return redirect()->route('succession.talent-pool')
                ->with('error', 'Employee not found in talent pool.');
        }

        $recommendations = json_decode($talent->recommendations, true) ?? [];

        $talent = (object) [
            'id' => $talent->id,
            'employee_id' => $talent->employee_id,
            'employee_name' => $talent->employee_name,
            'employee_email' => $talent->employee_email,
            'job_title' => $talent->job_title,
            'potential_job' => $talent->potential_job,
            'assessment_score' => $talent->assessment_score,
            'category' => $talent->category,
            'strengths' => $talent->strengths,
            'recommendations' => $recommendations,
            'ai_reasoning' => $recommendations['ai_reasoning'] ?? '',
            'match_score' => $recommendations['match_score'] ?? 0,
            'readiness' => $recommendations['readiness'] ?? 'Unknown',
            'status' => $talent->status,
        ];

        return view('succession_planning.potential', compact('talent'));
    }

    /**
     * Send employee to promotion process
     */
    public function promoteEmployee(Request $request)
    {
        Log::info('Promotion form submitted', ['request_data' => $request->all()]);

        $validated = $request->validate([
            'employee_id' => 'required|string',
            'potential_job' => 'required|string',
        ]);

        try {
            // Update the existing promotion record
            $updated = DB::connection('succession_planning')
                ->table('promotions')
                ->where('employee_id', $validated['employee_id'])
                ->update([
                    'potential_job' => $validated['potential_job'],
                    'status' => 'approved', // Mark as approved for promotion
                    'updated_at' => now(),
                ]);

            if ($updated) {
                Log::info('Promotion updated successfully', ['employee_id' => $validated['employee_id']]);
                return redirect()->route('succession.talent-pool')
                    ->with('success', 'Employee promotion updated successfully!');
            } else {
                return redirect()->back()
                    ->with('error', 'Employee not found in promotions.');
            }

        } catch (\Exception $e) {
            Log::error('Promotion update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update promotion: ' . $e->getMessage());
        }
    }
}