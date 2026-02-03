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
            'quiz_ids' => [
                'required',
                'array',
                'min:1'
            ],
            'quiz_ids.*' => [
                'required',
                'integer',
                'exists:hr2_learning_management.quizzes,id'
            ],
            'category_ids' => [
                'required',
                'array',
                'min:1'
            ],
            'category_ids.*' => [
                'required',
                'integer',
                'exists:hr2_learning_management.assessment_categories,id'
            ],
            'employee_id' => [
                'required',
                'string',
                'max:255'
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
            'quiz_ids.required' => 'Please select at least one assessment.',
            'quiz_ids.min' => 'Please select at least one assessment.',
            'quiz_ids.*.exists' => 'One or more selected assessments are invalid.',
            'category_ids.required' => 'Assessment category information is missing.',
            'category_ids.*.exists' => 'One or more assessment categories are invalid.',
            'employee_id.required' => 'Please select an employee.',
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
            'quiz_ids' => 'assessments',
            'category_ids' => 'assessment categories',
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
            // Check each quiz belongs to its corresponding category
            if ($this->quiz_ids && $this->category_ids && count($this->quiz_ids) === count($this->category_ids)) {
                foreach ($this->quiz_ids as $index => $quizId) {
                    $categoryId = $this->category_ids[$index] ?? null;
                    
                    if ($quizId && $categoryId) {
                        $quiz = \App\Modules\learning_management\Models\Quiz::on('learning_management')->find($quizId);
                        if ($quiz && $quiz->category_id != $categoryId) {
                            $validator->errors()->add('quiz_ids', 'One or more assessments do not belong to their selected category.');
                            break;
                        }
                    }
                }
            }

            // Check if the employee already has active assignments for any of the selected quizzes
            if ($this->employee_id && $this->quiz_ids) {
                foreach ($this->quiz_ids as $quizId) {
                    $existingAssignment = \App\Modules\learning_management\Models\AssessmentAssignment::on('learning_management')
                        ->where('employee_id', $this->employee_id)
                        ->where('quiz_id', $quizId)
                        ->whereNotIn('status', ['completed', 'cancelled'])
                        ->first();
                    
                    if ($existingAssignment) {
                        $quiz = \App\Modules\learning_management\Models\Quiz::on('learning_management')->find($quizId);
                        $quizTitle = $quiz ? $quiz->quiz_title : 'Unknown';
                        $validator->errors()->add('quiz_ids', "This employee already has an active assignment for '{$quizTitle}'.");
                        break;
                    }
                }
            }
        });
    }
}