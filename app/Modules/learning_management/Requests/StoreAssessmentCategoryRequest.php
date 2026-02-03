<?php

namespace App\Modules\learning_management\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAssessmentCategoryRequest extends FormRequest
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
            'category_name' => 'required|string|max:255',
            'category_slug' => 'required|string|max:255|unique:hr2_learning_management.assessment_categories,category_slug',
            'category_icon' => 'required|string|in:bx-code-alt,bx-user-voice,bx-briefcase,bx-medal,bx-brain,bx-heart',
            'description' => 'required|string|max:5000',
            'color_theme' => 'required|string|in:blue,green,red,purple,orange,teal,indigo,pink',
            'is_active' => 'required|boolean',
            'action' => 'required|in:draft,active'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'category_name.required' => 'The category name is required.',
            'category_name.max' => 'The category name may not be greater than 255 characters.',
            'category_slug.required' => 'The category slug is required.',
            'category_slug.unique' => 'This category slug is already taken.',
            'category_icon.required' => 'Please select an icon for the category.',
            'category_icon.in' => 'The selected icon is invalid.',
            'description.required' => 'The category description is required.',
            'description.max' => 'The description may not be greater than 5000 characters.',
            'color_theme.required' => 'Please select a color theme.',
            'color_theme.in' => 'The selected color theme is invalid.',
            'is_active.boolean' => 'The status field must be true or false.',
            'action.required' => 'Please specify an action.',
            'action.in' => 'The action must be either draft or active.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Auto-generate slug if not provided
        if (empty($this->category_slug) && !empty($this->category_name)) {
            $this->merge([
                'category_slug' => \Str::slug($this->category_name)
            ]);
        }

        // Convert action to boolean for is_active
        if ($this->has('action')) {
            $this->merge([
                'is_active' => $this->action === 'active'
            ]);
        }
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'category_name' => 'category name',
            'category_slug' => 'category slug',
            'category_icon' => 'category icon',
            'description' => 'description',
            'color_theme' => 'color theme',
            'is_active' => 'status'
        ];
    }
}