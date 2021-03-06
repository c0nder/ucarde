<?php


namespace App\Services\QrCode\FieldTypes;


use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PhoneFieldType extends AbstractFieldType
{
    function getImage(string $value)
    {
        return QrCode::size(400)->phoneNumber($value);
    }

}
