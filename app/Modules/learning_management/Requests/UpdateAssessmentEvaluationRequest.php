<?php

namespace App\Modules\learning_management\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssessmentEvaluationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only allow admin or HR roles to evaluate assessments
        return auth()->user() && in_array(auth()->user()->role, ['admin', 'hr']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'evaluation_status' => 'required|in:passed,failed',
            'evaluation_notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'evaluation_status.required' => 'Please select whether to pass or fail this assessment.',
            'evaluation_status.in' => 'Evaluation status must be either passed or failed.',
            'evaluation_notes.max' => 'Evaluation notes cannot exceed 1000 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'evaluation_status' => 'evaluation decision',
            'evaluation_notes' => 'evaluation notes',
        ];
    }
}