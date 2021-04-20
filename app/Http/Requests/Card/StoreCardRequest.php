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
            'title.required' => 'Title field is required',
            'username.required' => 'Name field is required',
            'description.required' => 'Description field is required',

            'fields.*.type.required' => 'Type of field is required',

            'fields.*.name.required' => 'Name of field is required',

            'fields.*.value.required' => 'Value of field is required'
        ];
    }


}
