<?php

namespace App\Modules\training_management\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrainingCatalogRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Please select a framework.',
            'title.string' => 'The framework must be a valid text.',
            'title.max' => 'The framework name is too long.',
            'label.required' => 'The label field is required.',
            'label.string' => 'The label must be a valid text.',
            'label.max' => 'The label is too long.',
            'description.required' => 'The description field is required.',
            'description.string' => 'The description must be a valid text.',
            'description.max' => 'The description is too long (maximum 1000 characters).',
        ];
    }
}
