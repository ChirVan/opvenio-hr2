<?php

namespace App\Modules\succession_planning\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PromotionController
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|string',
            'employee_name' => 'required|string',
            'employee_email' => 'required|email',
            'job_title' => 'required|string',
            'potential_job' => 'required|string',
            'assessment_score' => 'nullable|numeric',
            'category' => 'nullable|string',
            'strengths' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'status' => 'required|string',
        ]);

        // Check if employee already has a pending record (e.g. from training evaluation pass)
        $existing = DB::connection('succession_planning')->table('promotions')
            ->where('employee_id', $data['employee_id'])
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            // Update existing pending record with the selected potential job
            // Status becomes pending_acceptance — waiting for employee to agree
            DB::connection('succession_planning')->table('promotions')
                ->where('id', $existing->id)
                ->update([
                    'potential_job' => $data['potential_job'],
                    'assessment_score' => $data['assessment_score'] ?? $existing->assessment_score,
                    'category' => $data['category'] ?? $existing->category,
                    'strengths' => $data['strengths'] ?? $existing->strengths,
                    'recommendations' => $data['recommendations'] ?? $existing->recommendations,
                    'status' => 'pending_acceptance',
                    'employee_response' => null,
                    'employee_response_note' => null,
                    'employee_responded_at' => null,
                    'updated_at' => now(),
                ]);
        } else {
            // Insert new record — pending_acceptance until employee agrees
            DB::connection('succession_planning')->table('promotions')->insert([
                'employee_id' => $data['employee_id'],
                'employee_name' => $data['employee_name'],
                'employee_email' => $data['employee_email'],
                'job_title' => $data['job_title'],
                'potential_job' => $data['potential_job'],
                'assessment_score' => $data['assessment_score'] ?? 0.0,
                'category' => $data['category'] ?? 'Leadership',
                'strengths' => $data['strengths'] ?? '',
                'recommendations' => $data['recommendations'] ?? '',
                'status' => 'pending_acceptance',
                'employee_response' => null,
                'employee_response_note' => null,
                'employee_responded_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Session::flash('success', 'Employee has been notified for promotion acceptance.');
        return Redirect::back();
    }

    /**
     * Execute the promotion - Update employee's job title in external HR API
     */
    public function executePromotion(Request $request, $id)
    {
        try {
            // Get the promotion record
            $promotion = DB::connection('succession_planning')
                ->table('promotions')
                ->where('id', $id)
                ->first();

            if (!$promotion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Promotion record not found'
                ], 404);
            }

            if ($promotion->status === 'promoted') {
                return response()->json([
                    'success' => false,
                    'message' => 'This employee has already been promoted'
                ], 400);
            }

            // Call external HR API to update the employee's job title
            $apiUrl = "https://hr4.microfinancial-1.com/updateemployee/{$promotion->employee_id}";
            
            $response = Http::withOptions(['verify' => false])
                ->withHeaders(['X-API-Key' => 'b24e8778f104db434adedd4342e94d39cee6d0668ec595dc6f02c739c522b57a'])
                ->put($apiUrl, [
                    'job_title' => $promotion->potential_job,
                    'promoted_from' => $promotion->job_title,
                    'promotion_date' => now()->toDateString(),
                ]);

            if ($response->successful()) {
                // Update local promotion record status to 'promoted'
                DB::connection('succession_planning')
                    ->table('promotions')
                    ->where('id', $id)
                    ->update([
                        'status' => 'promoted',
                        'promoted_at' => now(),
                        'updated_at' => now(),
                    ]);

                Log::info("Employee {$promotion->employee_id} promoted successfully", [
                    'employee_name' => $promotion->employee_name,
                    'old_job' => $promotion->job_title,
                    'new_job' => $promotion->potential_job,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => "Successfully promoted {$promotion->employee_name} to {$promotion->potential_job}",
                    'data' => [
                        'employee_id' => $promotion->employee_id,
                        'employee_name' => $promotion->employee_name,
                        'old_job_title' => $promotion->job_title,
                        'new_job_title' => $promotion->potential_job,
                        'promoted_at' => now()->toDateTimeString(),
                    ]
                ]);
            } else {
                Log::error("Failed to update employee job title via API", [
                    'employee_id' => $promotion->employee_id,
                    'response' => $response->body(),
                    'status' => $response->status(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update job title in HR system. API Error: ' . $response->body()
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error("Promotion execution error", [
                'error' => $e->getMessage(),
                'promotion_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Employee accepts or declines a promotion offer
     */
    public function respondToPromotion(Request $request, $id)
    {
        $request->validate([
            'response' => 'required|in:accepted,declined',
            'note' => 'nullable|string|max:500',
        ]);

        $promotion = DB::connection('succession_planning')
            ->table('promotions')
            ->where('id', $id)
            ->first();

        if (!$promotion) {
            return response()->json(['success' => false, 'message' => 'Promotion record not found.'], 404);
        }

        if ($promotion->status !== 'pending_acceptance') {
            return response()->json(['success' => false, 'message' => 'This promotion offer is no longer pending.'], 400);
        }

        $isAccepted = $request->response === 'accepted';

        DB::connection('succession_planning')->table('promotions')
            ->where('id', $id)
            ->update([
                'status' => $isAccepted ? 'approved' : 'declined',
                'employee_response' => $request->response,
                'employee_response_note' => $request->note,
                'employee_responded_at' => now(),
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => $isAccepted
                ? 'You have accepted the promotion offer. Congratulations!'
                : 'You have declined the promotion offer.',
        ]);
    }

    /**
     * Get promotion status
     */
    public function getPromotionStatus($id)
    {
        $promotion = DB::connection('succession_planning')
            ->table('promotions')
            ->where('id', $id)
            ->first();

        if (!$promotion) {
            return response()->json([
                'success' => false,
                'message' => 'Promotion record not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $promotion
        ]);
    }
}
