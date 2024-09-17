<?php

namespace QSOFT\BizprocMigration\Options\OptionStores;

use Bitrix\Main\Config\Option;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use QSOFT\BizprocMigration;

class ModuleOptionStore implements OptionStoreInterface
{
    private const EMPTY_DB_VALUE = '';

    private array $writtenOptions = [];

    /**
     * @throws ArgumentOutOfRangeException
     */
    public function __destruct()
    {
        $this->flushWrittenOptions();
    }

    /**
     * @param string $name
     * @return string|null
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     */
    public function get(string $name): ?string
    {
        if (isset($this->writtenOptions[$name])) {
            return $this->writtenOptions[$name];
        }

        return $this->retrieve($name);
    }

    public function set(string $name, ?string $value): void
    {
        $this->writtenOptions[$name] = $value;
    }

    /**
     * @param string $name
     * @return string|null
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     */
    private function retrieve(string $name): ?string
    {
        return Option::get(BizprocMigration\MODULE_ID, $name, null);
    }

    /**
     * @return void
     * @throws ArgumentOutOfRangeException
     */
    private function flushWrittenOptions(): void
    {
        foreach ($this->writtenOptions as $name => $value) {
            $this->store($name, $value);
        }
    }

    /**
     * @param string $name
     * @param string|null $value
     * @return void
     * @throws ArgumentOutOfRangeException
     */
    private function store(string $name, ?string $value):void
    {
        Option::set(BizprocMigration\MODULE_ID, $name, $value ?? self::EMPTY_DB_VALUE);
    }
}
