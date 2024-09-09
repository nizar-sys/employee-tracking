<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RequestStoreEmployee extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Mengizinkan pengguna untuk membuat request
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $employeeId = $this->route('employee');

        return [
            'number' => [
                'required',
                'string',
                'max:10',
                Rule::unique('employees', 'number')->ignore($employeeId),
            ],
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('employees', 'user_id')->ignore($employeeId),
            ],
            'designation_id' => 'required|exists:designations,id',
            'phone' => 'required|string|max:15|regex:/^[0-9]+$/',
            'address' => 'required|string|max:255',
            'zip_code' => 'required|string|min:5|max:10',
            'date_of_birth' => 'required|date|before:today',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'work_hour' => 'required',
        ];
    }
}
