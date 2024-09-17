<?php

namespace QSOFT\BizprocMigration\Options\OptionTypes;

class Text extends AbstractType
{

    public static function encodeImpl($value): string
    {
        return $value;
    }

    public static function decodeImpl(string $value): string
    {
        return $value;
    }

    public static function getBitrixDisplayProperties(): array
    {
        return BitrixOptionInputs::LINE;
    }
}
