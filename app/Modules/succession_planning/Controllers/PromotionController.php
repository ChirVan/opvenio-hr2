<?php

namespace App\Modules\succession_planning\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

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

        // Insert into succession_planning.promotions table
        $id = DB::connection('succession_planning')->table('promotions')->insertGetId([
            'employee_id' => $data['employee_id'],
            'employee_name' => $data['employee_name'],
            'employee_email' => $data['employee_email'],
            'job_title' => $data['job_title'],
            'potential_job' => $data['potential_job'],
            'assessment_score' => $data['assessment_score'] ?? 0.0,
            'category' => $data['category'] ?? 'Leadership',
            'strengths' => $data['strengths'] ?? '',
            'recommendations' => $data['recommendations'] ?? '',
            'status' => $data['status'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Update status to approved after insert
        DB::connection('succession_planning')->table('promotions')
            ->where('id', $id)
            ->update(['status' => 'approved']);

        Session::flash('success', 'Promotion record added and approved successfully!');
        return Redirect::back();
    }
}
