<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthLoginRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

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
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'exists:users'],
            'password' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email field is required',
            'email.exists' => 'This email does not exist',
            'password.required' => 'Password field is required'
        ];
    }
}
