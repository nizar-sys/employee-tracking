<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestStoreTaskReport extends FormRequest
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
        $proofAssignmentRule = $this->isMethod('put') ? 'nullable' : 'required';

        return [
            'task_id' => 'required|exists:tasks,id',
            'status' => 'required|string|in:pending,rejected,approved,revision',
            'note' => 'nullable|string|max:255',
            'proof_assignment' => [
                $proofAssignmentRule,
                'mimes:jpeg,png,jpg,gif',
                'max:2048',
            ],
        ];
    }


    public function messages(): array
    {
        return [
            '*.required' => ':attribute is required.',
            '*.string' => ':attribute must be a string.',
            '*.max' => ':attribute may not be greater than :max characters.',
        ];
    }
}
