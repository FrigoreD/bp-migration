<?php

namespace QSOFT\BizprocMigration\Options\OptionTypes;

class Integer extends AbstractType
{
    public static function encodeImpl($value): string
    {
        return (string)$value;
    }

    public static function decodeImpl(string $value): int
    {
        return (int)$value;
    }

    public static function getBitrixDisplayProperties(): array
    {
        return BitrixOptionInputs::TEXT;
    }
}
