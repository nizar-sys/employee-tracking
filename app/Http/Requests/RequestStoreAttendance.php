<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestStoreAttendance extends FormRequest
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
        $rules = [
            'employee_id' => ['required', 'exists:employees,id'],
            'date' => ['required', 'date_format:Y-m-d'],
            'attendance_type' => ['required'],
            'is_late' => ['nullable'],
        ];

        if ($this->input('attendance_type') === 'check_in') {
            $rules = array_merge($rules, [
                'check_in' => ['required', 'date_format:H:i'],
                'location_check_in' => ['required', 'string', 'min:5'],
                'longlat_check_in' => ['required', 'regex:/^-?\d{1,3}\.\d+,\s*-?\d{1,3}\.\d+$/'],
                'picture_check_in' => ['nullable', 'file', 'mimes:jpeg,jpg,png'],
            ]);
        }

        if ($this->input('attendance_type') === 'check_out') {
            $rules = array_merge($rules, [
                'check_out' => ['required', 'date_format:H:i'],
                'location_check_out' => ['required', 'string', 'min:5'],
                'longlat_check_out' => ['required', 'regex:/^-?\d{1,3}\.\d+,\s*-?\d{1,3}\.\d+$/'],
                'picture_check_out' => ['nullable', 'file', 'mimes:jpeg,jpg,png'],
            ]);
        }

        return $rules;
    }
}
