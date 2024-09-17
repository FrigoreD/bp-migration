<?php

namespace QSOFT\BizprocMigration\Options\OptionTypes;
use Bitrix\Main\Type\DateTime as BitrixDateTime;

/**
 * Тип настройки. Должен поддерживать кодирование/декодирование. Предполагается,
 * что null представляет отсутствие значения,
 * поэтому он обрабатывается особенным образом.
 */
abstract class AbstractType implements TypeInterface
{
    public static function encode($value): ?string
    {
        return is_null($value) ? null : static::encodeImpl($value);
    }

    public static function decode(?string $value)
    {
        return is_null($value) ? null : static::decodeImpl($value);
    }

    public static function encodeFromBitrixString(string $value): ?string
    {
        return $value;
    }

    /**
     * @param $value - гарантировано не null
     * @return string|null - null представляет пустое значение
     */
    abstract protected static function encodeImpl($value): string;

    /**
     * @param string $value - гарантировано не null
     * @return array|null|bool|BitrixDateTime|string
     */
    abstract protected static function decodeImpl(string $value);
}
