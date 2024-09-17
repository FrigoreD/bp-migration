<?php

namespace QSOFT\BizprocMigration\Options\OptionTypes;

use Bitrix\Main\Type\DateTime as BitrixDateTime;

interface TypeInterface
{
    /**
     * @param $value
     * @return string|null
     */
    public static function encode($value): ?string;

    /**
     * @param string|null $value
     * @return array|null|bool|BitrixDateTime|string
     */
    public static function decode(?string $value);

    /**
     * @return array
     */
    public static function getBitrixDisplayProperties(): array;

    /**
     * @param string $value
     * @return string|null
     */
    public static function encodeFromBitrixString(string $value): ?string;
}
