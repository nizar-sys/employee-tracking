<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestStoreTask extends FormRequest
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
            'employee_id' => 'required|integer|exists:employees,id',
            'title' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today',
            'longlat' => 'required|string',
            'description' => 'nullable|string|max:500',
            'type' => 'required|string|max:255',
            'is_validate_location' => 'required|in:yes,no',
        ];
    }

    public function messages(): array
    {
        return [
            '*.required' => ':attribute is required.',
            '*.integer' => ':attribute must be an integer.',
            '*.exists' => ':attribute does not exist in the database.',
            '*.string' => ':attribute must be a string.',
            '*.max' => ':attribute may not be greater than :max characters.',
            '*.date' => ':attribute is not a valid date.',
            '*.after_or_equal' => ':attribute must be today or later.',
            '*.in' => ':attribute must be one of the following: :values.',
        ];
    }
}
