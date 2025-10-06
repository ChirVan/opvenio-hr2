<?php

namespace App\Modules\learning_management\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAssessmentAssignmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check(); // User must be authenticated
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'assessment_category' => [
                'required',
                'integer',
                'exists:learning_management.assessment_categories,id'
            ],
            'quiz_id' => [
                'required',
                'integer',
                'exists:learning_management.quizzes,id'
            ],
            'employee_id' => [
                'required',
                'string',
                'max:255'
            ],
            'duration' => [
                'required',
                'integer',
                'min:1',
                'max:480' // Max 8 hours
            ],
            'start_date' => [
                'required',
                'date',
                'after_or_equal:now'
            ],
            'due_date' => [
                'required',
                'date',
                'after:start_date'
            ],
            'max_attempts' => [
                'required',
                Rule::in(['1', '2', '3', 'unlimited'])
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000'
            ]
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'assessment_category.required' => 'Please select an assessment category.',
            'assessment_category.exists' => 'The selected assessment category is invalid.',
            'quiz_id.required' => 'Please select an assessment.',
            'quiz_id.exists' => 'The selected assessment is invalid.',
            'employee_id.required' => 'Please select an employee.',
            'duration.required' => 'Duration is required.',
            'duration.min' => 'Duration must be at least 1 minute.',
            'duration.max' => 'Duration cannot exceed 480 minutes (8 hours).',
            'start_date.required' => 'Please specify when the assessment should be available.',
            'start_date.after_or_equal' => 'The start date cannot be in the past.',
            'due_date.required' => 'Please specify when the assessment is due.',
            'due_date.after' => 'The due date must be after the start date.',
            'max_attempts.required' => 'Please specify the maximum number of attempts.',
            'max_attempts.in' => 'Maximum attempts must be 1, 2, 3, or unlimited.',
            'notes.max' => 'Notes cannot exceed 1000 characters.'
        ];
    }

    /**
     * Get custom attribute names for validation errors.
     */
    public function attributes(): array
    {
        return [
            'assessment_category' => 'assessment category',
            'quiz_id' => 'assessment',
            'employee_id' => 'employee',
            'start_date' => 'available from date',
            'due_date' => 'due date',
            'max_attempts' => 'maximum attempts'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure dates are properly formatted
        if ($this->start_date) {
            $this->merge([
                'start_date' => date('Y-m-d H:i:s', strtotime($this->start_date))
            ]);
        }

        if ($this->due_date) {
            $this->merge([
                'due_date' => date('Y-m-d H:i:s', strtotime($this->due_date))
            ]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Custom validation logic can be added here
            
            // Check if the quiz belongs to the selected category
            if ($this->assessment_category && $this->quiz_id) {
                $quiz = \App\Modules\learning_management\Models\Quiz::on('learning_management')->find($this->quiz_id);
                if ($quiz && $quiz->category_id != $this->assessment_category) {
                    $validator->errors()->add('quiz_id', 'The selected assessment does not belong to the selected category.');
                }
            }

            // Check if the employee already has an active assignment for this quiz
            if ($this->employee_id && $this->quiz_id) {
                $existingAssignment = \App\Modules\learning_management\Models\AssessmentAssignment::on('learning_management')
                    ->where('employee_id', $this->employee_id)
                    ->where('quiz_id', $this->quiz_id)
                    ->whereNotIn('status', ['completed', 'cancelled'])
                    ->first();
                
                if ($existingAssignment) {
                    $validator->errors()->add('employee_id', 'This employee already has an active assignment for the selected assessment.');
                }
            }
        });
    }
}