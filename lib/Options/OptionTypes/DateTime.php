<?php

namespace QSOFT\BizprocMigration\Options\OptionTypes;

use Bitrix\Main\ObjectException;
use Bitrix\Main\Type\DateTime as BitrixDateTime;

class DateTime extends AbstractType
{
    /**
     * @param $value
     * @return string
     */
    protected static function encodeImpl($value): string
    {
        /* format не учитывает часовой пояс */
        return $value->toString();
    }

    /**
     * @param string $value
     * @return BitrixDateTime
     * @throws ObjectException
     */
    protected static function decodeImpl(string $value): BitrixDateTime
    {
        return new BitrixDateTime($value);
    }

    /**
     * @return array
     */
    public static function getBitrixDisplayProperties(): array
    {
        return BitrixOptionInputs::TEXT;
    }
}
