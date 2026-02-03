<?php

namespace App\Modules\training_management\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTrainingMaterialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'lesson_title' => [
                'required',
                'string',
                'max:255',
                'min:3',
            ],
            'competency_id' => [
                'required',
                'integer',
                'exists:competency_management.competencies,id',
            ],
            'proficiency_level' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],
            'lesson_content' => [
                'required',
                'string',
                'min:10',
            ],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'lesson_title.required' => 'The lesson title is required.',
            'lesson_title.min' => 'The lesson title must be at least 3 characters.',
            'lesson_title.max' => 'The lesson title may not be greater than 255 characters.',
            'competency_id.required' => 'Please select a competency.',
            'competency_id.exists' => 'The selected competency is invalid.',
            'proficiency_level.required' => 'Please select a proficiency level.',
            'proficiency_level.integer' => 'The proficiency level must be a number.',
            'proficiency_level.min' => 'The proficiency level must be at least 1.',
            'proficiency_level.max' => 'The proficiency level may not be greater than 5.',
            'lesson_content.required' => 'The lesson content is required.',
            'lesson_content.min' => 'The lesson content must be at least 10 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'lesson_title' => 'lesson title',
            'competency_id' => 'competency',
            'proficiency_level' => 'proficiency level',
            'lesson_content' => 'lesson content',
        ];
    }
}