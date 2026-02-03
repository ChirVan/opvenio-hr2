<?php

namespace App\Modules\competency_management\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGapAnalysisRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
         return [
            'employee_id' => 'required|integer|min:1', // Changed: now validates external API employee ID
            'competency_id' => 'required|exists:competency_management.competencies,id',
            'framework' => 'required|string|max:255',
            'proficiency_level' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'assessment_date' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed,on_hold',
        ];
    }

    public function messages()
    {
        return [
            'employee_id.required' => 'Employee selection is required.',
            'employee_id.integer' => 'Invalid employee selection.',
            'competency_id.required' => 'Competency selection is required.',
            'framework.required' => 'Framework is required.',
            'proficiency_level.required' => 'Proficiency level is required.',
            'assessment_date.required' => 'Assessment date is required.',
            'assessment_date.date' => 'Assessment date must be a valid date.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be one of: pending, in progress, completed, or on hold.',
        ];
    }
}