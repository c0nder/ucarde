<?php

namespace App\Http\Requests\Card;

use App\Services\FieldValidationService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCardRequest extends FormRequest
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
            'title' => ['required', 'string'],
            'username' => ['required', 'string'],
            'description' => ['string', 'nullable'],
            'fields' => ['required', 'array'],
            'fields.*.id' => ['sometimes', 'int', 'exists:fields,id'],
            'fields.*.type' => ['required', 'string', Rule::in($service->getAvailableTypes())],
            'fields.*.name' => ['required', 'string'],
            'fields.*.value' => ['required']
        ];
    }
}
