<?php

namespace App\Modules\competency_management\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCompetencyFrameworkRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'framework_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('hr2_competency_managements.competency_frameworks')
            ],
            'description' => 'required|string|max:2000',
            'effective_date' => 'required|date',
            'end_date' => 'nullable|date|after:effective_date',
            'status' => 'required|in:active,inactive,draft,archived',
            'notes' => 'nullable|string|max:1000'
        ];
    }

    public function messages()
    {
        return [
            'framework_name.required' => 'Framework name is required.',
            'framework_name.unique' => 'This framework name already exists.',
            'description.required' => 'Description is required.',
            'effective_date.required' => 'Effective date is required.',
            'end_date.after' => 'End date must be after the effective date.',
            'status.required' => 'Status is required.',
        ];
    }
}