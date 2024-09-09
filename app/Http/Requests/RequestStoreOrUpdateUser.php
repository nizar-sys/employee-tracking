<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestStoreOrUpdateUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|',
            'role' => 'required|in:admin,user',
        ];

        if($this->isMethod('POST')){
            $rules['password'] = 'required|min:6';
            $rules['confirmation_password'] = 'required|same:password';
            $rules['email'] .= 'unique:users,id,'.$this->user()->id;
            $rules['avatar'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }

        return $rules;
    }
}
