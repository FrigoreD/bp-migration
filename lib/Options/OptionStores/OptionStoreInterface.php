<?php

namespace QSOFT\BizprocMigration\Options\OptionStores;

interface OptionStoreInterface
{
    /**
     * @param string $name
     * @return string|null
     */
    public function get(string $name): ?string;

    /**
     * @param string $name
     * @param string|null $value
     * @return void
     */
    public function set(string $name, ?string $value): void;
}
