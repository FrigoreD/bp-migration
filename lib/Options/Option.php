<?php

namespace QSOFT\BizprocMigration\Options;

use Bitrix\Main\Type\DateTime as BitrixDateTime;
use QSOFT\BizprocMigration\Options\OptionStores\OptionStoreInterface;
use QSOFT\BizprocMigration\Options\OptionTypes\TypeInterface;
use InvalidArgumentException;

class Option
{
    private string $code;

    private string $description;

    /**
     * @var class-string<TypeInterface>
     */
    private string $type;

    private $defaultValue;

    private bool $isEditable = true;

    private bool $isHidden = false;

    private static OptionStoreInterface $optionStore;

    /**
     * Option constructor.
     * @param string $code
     * @param string $description
     * @param class-string<TypeInterface> $type
     * @param null $defaultValue
     */
    public function __construct(
        string $code,
        string $description,
        string $type,
        $defaultValue = null
    ) {
        if (!is_subclass_of($type, TypeInterface::class)) {
            throw new InvalidArgumentException("Тип настройки должен реализовывать TypeInterface");
        }

        $this->type = $type;
        $this->code = $code;
        $this->description = $description;
        $this->defaultValue = $defaultValue;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @return bool
     */
    public function isEditable(): bool
    {
        return $this->isEditable;
    }

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->isHidden;
    }

    /**
     * @return class-string<TypeInterface>
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return $this
     */
    public function makeReadonly(): self
    {
        $this->isEditable = false;
        return $this;
    }

    /**
     * @return $this
     */
    public function makeHidden(): self
    {
        $this->makeReadonly();
        $this->isHidden = true;
        return $this;
    }

    /**
     * @param OptionStoreInterface $store
     * @return void
     */
    public static function setOptionStore(OptionStoreInterface $store): void
    {
        self::$optionStore = $store;
    }

    /**
     * @return OptionStoreInterface
     */
    public static function getOptionStore(): OptionStoreInterface
    {
        return self::$optionStore;
    }

    /**
     * @return array|null|bool|BitrixDateTime|string
     */
    public function getValue()
    {
        if (is_null(self::$optionStore->get($this->code))) {
            return $this->defaultValue;
        }

        $value = self::$optionStore->get($this->code);
        return $this->type::decode($value);
    }

    /**
     * @param $value
     * @return void
     */
    public function setValue($value): void
    {
        $newValue = $this->type::encode($value);
        self::$optionStore->set($this->code, $newValue);
    }

    /**
     * @param $value
     * @return void
     */
    public function setValueFromBitrix($value): void
    {
        $value = $this->type::encodeFromBitrixString($value);
        self::$optionStore->set($this->code, $value);
    }

    /**
     * @return string[]
     */
    public function getBitrixDisplayParams(): array
    {
        if (! $this->isEditable) {
            return ['statictext'];
        }

        return $this->type::getBitrixDisplayProperties();
    }
}
