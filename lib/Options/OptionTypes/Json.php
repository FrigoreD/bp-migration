<?php

namespace QSOFT\BizprocMigration\Options\OptionTypes;
use InvalidArgumentException;

class Json extends AbstractType
{
    /**
     * @param $value - любое значение, кроме скалярных (строка, число, булево).
     * @return string
     */
    protected static function encodeImpl($value): string
    {
        if (is_scalar($value)) {
            throw new InvalidArgumentException(
                "Не допускается хранение скалярных значений в JSON. Передано: $value"
            );
        }

        return json_encode($value);
    }

    /**
     * @param string $value
     * @return array|null
     */
    protected static function decodeImpl(string $value): ?array
    {
        return json_decode($value, true);
    }

    /**
     * @return array
     */
    public static function getBitrixDisplayProperties(): array
    {
        return BitrixOptionInputs::TEXTAREA;
    }

    /**
     * @param string $value
     * @return string|null
     */
    public static function encodeFromBitrixString(string $value): ?string
    {
        if ($value === '') {
            return null;
        }

        return $value;
    }
}
