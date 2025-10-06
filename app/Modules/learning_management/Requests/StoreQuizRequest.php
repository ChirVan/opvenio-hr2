<?php

namespace App\Modules\learning_management\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQuizRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Add authorization logic as needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'quiz_title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:learning_management.assessment_categories,id',
            'competency' => 'required|exists:competency_management.competencies,id',
            'time_limit' => 'nullable|integer|min:5|max:180',
            'description' => 'nullable|string|max:1000',
            'action' => 'required|in:draft,publish',
            
            // Questions validation
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string|max:1000',
            'questions.*.answer' => 'required|string|max:500',
            'questions.*.points' => 'required|integer|min:1|max:10',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'quiz_title.required' => 'The quiz title is required.',
            'quiz_title.max' => 'The quiz title may not be greater than 255 characters.',
            'competency.required' => 'Please select a competency for this quiz.',
            'competency.exists' => 'The selected competency is invalid.',
            'time_limit.integer' => 'Time limit must be a number.',
            'time_limit.min' => 'Time limit must be at least 5 minutes.',
            'time_limit.max' => 'Time limit cannot exceed 180 minutes.',
            'description.max' => 'The description may not be greater than 1000 characters.',
            'action.required' => 'Please specify an action (draft or publish).',
            'action.in' => 'The action must be either draft or publish.',
            
            // Questions messages
            'questions.required' => 'At least one question is required.',
            'questions.min' => 'At least one question is required.',
            'questions.*.question.required' => 'Each question text is required.',
            'questions.*.question.max' => 'Question text may not exceed 1000 characters.',
            'questions.*.answer.required' => 'Each question must have a correct answer.',
            'questions.*.answer.max' => 'Answer may not exceed 500 characters.',
            'questions.*.points.required' => 'Each question must have points assigned.',
            'questions.*.points.integer' => 'Points must be a number.',
            'questions.*.points.min' => 'Points must be at least 1.',
            'questions.*.points.max' => 'Points cannot exceed 10.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'quiz_title' => 'quiz title',
            'competency' => 'competency',
            'time_limit' => 'time limit',
            'description' => 'description',
            'questions.*.question' => 'question',
            'questions.*.answer' => 'answer',
            'questions.*.points' => 'points',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'time_limit' => $this->time_limit ?: 30,
        ]);
    }
}