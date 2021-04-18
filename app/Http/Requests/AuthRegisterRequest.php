<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRegisterRequest extends FormRequest
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
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'between:6,10'],
            'repeatedPassword' => ['required', 'same:password']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name field is required',
            'email.required' => 'Email field is required',
            'email.email' => 'Please enter a valid email',
            'email.unique' => 'User with this email already exists',
            'password.required' => 'Password field is required',
            'password.between' => 'The password must be between 6 and 10 characters long',
            'repeatedPassword.required' => 'Passwords must match',
            'repeatedPassword.same' => 'Passwords must match'
        ];
    }
}
