<?php


namespace App\Services;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class FieldValidationService
{
    private function getEmailValidationRules(): array
    {
        return [
            'value' => ['email']
        ];
    }

    private function getTextValidationRules(): array
    {
        return [
            'value' => ['string']
        ];
    }

    private function validate(string $fieldType, $value)
    {
        $method = "get" . ucfirst($fieldType) . "ValidationRules";

        return Validator::make([
            'value' => $value
        ], $this->$method());
    }

    public function validateFields(array $fields): MessageBag
    {
        foreach($fields as $field) {
            $validator = $this->validate($field['type'], $field['value']);

            if ($validator->fails()) {
                return $validator->errors();
            }
        }

        return new MessageBag();
    }

    public function getAvailableTypes()
    {
        return [
            'text',
            'email',
            'phone',
            'address'
        ];
    }
}
