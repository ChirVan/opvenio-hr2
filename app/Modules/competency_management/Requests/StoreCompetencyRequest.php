<?php

namespace App\Modules\competency_management\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCompetencyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'competency_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('competency_management.competencies', 'competency_name')
                
            ],
            'description' => 'required|string|max:2000',
            'framework_id' => 'required|exists:competency_management.competency_frameworks,id',
            'proficiency_levels' => 'required|integer|min:1|max:10',
            'status' => 'required|in:active,inactive,draft,archived',
            'behavioral_indicators' => 'nullable|string|max:3000',
            'assessment_criteria' => 'nullable|string|max:3000',
            'notes' => 'nullable|string|max:1000'
        ];
    }

    public function messages()
    {
        return [
            'competency_name.required' => 'Competency name is required.',
            'competency_name.unique' => 'This competency name already exists.',
            'description.required' => 'Description is required.',
            'framework_id.required' => 'Please select a framework.',
            'framework_id.exists' => 'Selected framework does not exist.',
            'proficiency_levels.required' => 'Number of proficiency levels is required.',
            'proficiency_levels.min' => 'Must have at least 1 proficiency level.',
            'proficiency_levels.max' => 'Cannot have more than 10 proficiency levels.',
            'status.required' => 'Status is required.',
        ];
    }
}