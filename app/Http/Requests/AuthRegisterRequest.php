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
            'name.required' => 'Это поле обязательно',
            'email.required' => 'Это поле обязательно',
            'email.email' => 'Пожалуйста, введите корректный email',
            'email.unique' => 'Пользователь с таким email уже существует',
            'password.required' => 'Это поле обязательно',
            'password.between' => 'Пароль должен содержать от 6 до 10 символов',
            'repeatedPassword.required' => 'Пароли должны совпадать',
            'repeatedPassword.same' => 'Пароли должны совпадать'
        ];
    }
}
