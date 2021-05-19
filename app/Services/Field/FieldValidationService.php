<?php


namespace App\Services;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class FieldValidationService
{
    private function getEmailValidationRules(): array
    {
        return [
            'value' => ['string', 'email']
        ];
    }

    private function getTextValidationRules(): array
    {
        return [
            'value' => ['string']
        ];
    }

    private function getPhoneValidationRules(): array
    {
        return [
            'value' => ['string']
        ];
    }

    private function getUrlValidationRules(): array
    {
        return [
            'value' => ['url']
        ];
    }

    private function validate(string $fieldType, $value)
    {
        $method = "get" . ucfirst($fieldType) . "ValidationRules";

        return Validator::make([
            'value' => $value
        ], $this->$method());
    }

    public function validateFields(array $fields)
    {
        foreach($fields as $index => $field) {
            $fieldName = $this->getFieldName($index);
            $validator = $this->validate($field['type'], $field['value']);

            if ($validator->fails()) {
                $messages = $validator->errors()->messages();

                throw new UnprocessableEntityHttpException(json_encode([
                    $fieldName => $messages['value']
                ]));
            }
        }
    }

    public function getFieldName(int $index)
    {
        return 'fields.' . $index . '.value';
    }

    public function getAvailableTypes()
    {
        return [
            'text',
            'email',
            'phone',
            'url'
        ];
    }
}
