<?php


namespace App\Services\QrCode;


use App\Models\Card;
use App\Models\Field;
use App\Services\QrCode\FieldTypes\AbstractFieldType;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    public function generateByCard(Card $card)
    {
        $hashedCardId = base64_encode($card->id);

        return QrCode::format('svg')->size(400)->generate(url("card/{$hashedCardId}"));
    }

    public function generateByField(Field $field)
    {
        $fieldTypeClass = __NAMESPACE__ . '\\FieldTypes\\' . ucfirst($field->type) . 'FieldType';

        if (class_exists($fieldTypeClass)) {
            /** @var AbstractFieldType $fieldTypeObject */
            $fieldTypeObject = new $fieldTypeClass();

            return $fieldTypeObject->getImage($field->value);
        }

        return null;
    }
}
