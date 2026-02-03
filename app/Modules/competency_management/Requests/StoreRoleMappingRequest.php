<?php

namespace App\Modules\competency_management\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoleMappingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
        'role_name' => 'required|string|max:255',
        'framework_id' => 'required|exists:hr2_competency_managements.competency_frameworks,id',
        'competency_id' => 'required|exists:hr2_competency_managements.competencies,id',
        'proficiency_level' => 'required|integer|min:1',
        'status' => 'required|in:active,inactive',
        'notes' => 'nullable|string|max:1000'
    ];
    }

    public function messages()
    {
        return [
            'role_name.required' => 'Role name is required.',
            'framework_id.required' => 'Please select a framework.',
            'framework_id.exists' => 'Selected framework does not exist.',
            'competency_id.required' => 'Please select a competency.',
            'competency_id.exists' => 'Selected competency does not exist.',
            'proficiency_level.required' => 'Proficiency level is required.',
            'proficiency_level.in' => 'Invalid proficiency level selected.',
            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status selected.'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check for unique combination of role_name, framework_id, and competency_id
            $exists = \App\Modules\competency_management\Models\RoleMapping::where('role_name', $this->role_name)
                ->where('framework_id', $this->framework_id)
                ->where('competency_id', $this->competency_id)
                ->exists();

            if ($exists) {
                $validator->errors()->add('role_name', 'This role already has a mapping for the selected framework and competency combination.');
            }
        });
    }
}