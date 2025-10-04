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
            'employee_id' => 'required|exists:competency_management.employees,id',
            'competency_id' => 'required|exists:competency_management.competencies,id',
            'framework' => 'required|string|max:255',
            'proficiency_level' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'lastname.required'   => 'Lastname is required.',
            'firstname.required'  => 'Firstname is required.',
            'job_role.required'   => 'Job role is required.',
            'email.required'      => 'Email is required.',
            'email.email'         => 'Email must be a valid email address.',
            'email.unique'        => 'This email is already used.',
        ];
    }
}