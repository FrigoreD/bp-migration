<?php

namespace QSOFT\BizprocMigration\Options\OptionTypes;

class Boolean extends AbstractType
{
    public const BOOLEAN_TO_STRING = [
        true => 'Y',
        false => 'N',
    ];

    public const STRING_TO_BOOLEAN = [
        'Y' => true,
        'N' => false,
    ];

    protected static function encodeImpl($value): string
    {
        return self::BOOLEAN_TO_STRING[$value];
    }

    protected static function decodeImpl(string $value)
    {
        return self::STRING_TO_BOOLEAN[$value];
    }

    public static function getBitrixDisplayProperties(): array
    {
        return BitrixOptionInputs::CHECKBOX;
    }
}