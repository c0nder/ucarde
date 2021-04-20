<?php


namespace App\Services\QrCode\FieldTypes;


use App\Models\Field;

abstract class AbstractFieldType
{
    abstract function getImage(string $value);
}
