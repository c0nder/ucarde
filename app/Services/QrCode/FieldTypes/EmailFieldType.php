<?php


namespace App\Services\QrCode\FieldTypes;


use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EmailFieldType extends AbstractFieldType
{

    function getImage(string $value)
    {
        return QrCode::size(500)->email($value);
    }
}
