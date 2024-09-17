<?php

namespace QSOFT\BizprocMigration\Options;

use QSOFT\BizprocMigration\Options\OptionTypes\TypeInterface;
use InvalidArgumentException;

/**
 * Сущность, которая выставляет свои настройки в настройки модуля. Если наследуется абстрактным классом, все
 * конкретные классы получают свои настройки
 */
abstract class AbstractConfigurable
{
    /**
     * @var array
     */
    protected static array $options = [];

    /**
     * @var string[]
     */
    protected static array $prefixes = [];

    /**
     * @param string $code
     * @param string $description
     * @param class-string<TypeInterface> $type
     * @param null $defaultValue
     * @return Option
     */
    protected static function declareOption(
        string $code,
        string $description,
        string $type,
        $defaultValue = null
    ): Option {
        $code = static::getOptionPrefix() . $code;

        $option = new Option(
            $code,
            $description,
            $type,
            $defaultValue,
        );

        static::addNewOption($option);
        return $option;
    }

    /**
     * @param Option $option
     * @return void
     */
    protected static function addNewOption(Option $option): void
    {
        $currentClass = get_called_class();

        if (!isset(static::$options[$currentClass])) {
            static::$options[$currentClass] = [];
        }

        $found = false;
        /** @var Option $option */
        foreach (static::$options[$currentClass] as $idx => $existingOption) {
            if ($existingOption->getCode() === $option->getCode()) {
                static::$options[$currentClass][$idx] = $option;
                $found = true;
            }
        }

        if (!$found) {
            static::$options[$currentClass][] = $option;
        }
    }

    /**
     * @return Option[]
     */
    public static function getAllOptions(): array
    {
        $currentClass = get_called_class();

        if (!isset(static::$options[$currentClass])) {
            /** @noinspection  */
            static::declareOptions();
        }

        return static::$options[$currentClass] ?? [];
    }

    /**
     * @param string $code
     * @return Option
     */
    public static function getOption(string $code): Option
    {
        $code = static::getOptionPrefix() . $code;
        return static::getOptionByFullCode($code);
    }

    /**
     * @param string $code
     * @return Option
     */
    protected static function getOptionByFullCode(string $code): Option
    {
        foreach (static::getAllOptions() as $option) {
            if ($option->getCode() === $code) {
                return $option;
            }
        }

        throw new InvalidArgumentException("Опция $code не найдена");
    }


    /**
     * Устанавливает значения по умолчанию для всех опций
     * @return void
     */
    public static function setDefaults(): void
    {
        $store = Option::getOptionStore();

        foreach (static::getAllOptions() as $option) {
            if (is_null($store->get($option->getCode())) && $defaultValue = $option->getDefaultValue()) {
                $option->setValue($defaultValue);
            }
        }
    }

    /**
     * Добавлять новые настройки через static::declareOption
     * @return void
     */
    abstract protected static function declareOptions(): void;

    /**
     * Переопределять в конкретных классах
     * @return string - заголовок раздела вкладки на странице настроек модуля
     */
    abstract public static function getOptionsSectionTitle(): string;

    /**
     * Переопределять в абстрактных или родительских классах
     * @return string - заголовок вкладки на странице настроек модуля
     */
    abstract public static function getOptionsTabTitle(): string;

    /**
     * Не выводим префикс из имени класса, а просим задать явно. Иначе смена имени класса приведет к сбросу опций.
     * @return string - уникальный префикс для сохранения опций в БД.
     */
    abstract public static function getOptionPrefix(): string;
}
