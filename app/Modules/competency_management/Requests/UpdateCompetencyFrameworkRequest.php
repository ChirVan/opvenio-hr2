<?php

namespace App\Modules\competency_management\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompetencyFrameworkRequest extends FormRequest
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
                Rule::unique('hr2_competency_managements.competency_frameworks', 'framework_name')
                    ->ignore($this->route('framework'))
            ],
            'description' => 'required|string|max:2000',
            'effective_date' => 'required|date',
            'end_date' => 'nullable|date|after:effective_date',
            'status' => 'required|in:active,inactive,draft,archived',
            'notes' => 'nullable|string|max:1000'
        ];
    }
}