<?php

namespace App\Http\Requests\Card;

use App\Services\FieldValidationService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCardRequest extends FormRequest
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
     * @param FieldValidationService $service
     * @return array
     */
    public function rules(FieldValidationService $service)
    {
        return [
            'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'title' => ['required', 'string', 'max:250'],
            'username' => ['required', 'string', 'max:250'],
            'description' => ['required', 'string', 'max:250'],
            'fields' => ['required', 'array'],
            'fields.*.type' => ['required', 'string', Rule::in($service->getAvailableTypes())],
            'fields.*.name' => ['required', 'string'],
            'fields.*.value' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Это поле обязательно',
            'username.required' => 'Это поле обязательно',
            'description.required' => 'Это поле обязательно',

            'fields.*.type.required' => 'Тип поля обязателен',

            'fields.*.name.required' => 'Имя поля обязательно',

            'fields.*.value.required' => 'Значение поля обязательно'
        ];
    }


}
