<?php

namespace QSOFT\BizprocMigration\Options\OptionTypes;

/**
 * Любое значение, которое можно сериализовать вызовом serialize
 */
class Serialized extends AbstractType
{
    protected static function encodeImpl($value): string
    {
        return serialize($value);
    }

    protected static function decodeImpl(string $value): ?string
    {
        return $value ? unserialize($value) : null;
    }

    public static function getBitrixDisplayProperties(): array
    {
        return BitrixOptionInputs::TEXTAREA;
    }
}
