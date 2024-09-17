<?php
require_once dirname(__DIR__) . '/autoloader.php';
require_once dirname(__DIR__) . '/include.php';

class qsoft_bizproc_migration extends CModule
{
    public $MODULE_ID = 'qsoft.bizproc_migration';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_GROUP_RIGHTS = 'Y';
    public string $MODULE_PATH;

    public function __construct()
    {
        $arModuleVersion = [];

        $this->MODULE_PATH = $this->getModulePath();
        include $this->MODULE_PATH . '/install/version.php';

        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_NAME = 'QSOFT Миграции Бизнес-процессов';
        $this->MODULE_DESCRIPTION = 'Миграции Бизнес-процессов';

        $this->PARTNER_NAME = 'QSOFT';
        $this->PARTNER_URI = 'https://qsoft.ru/';
    }

    /**
     * @return bool
     */
    public function DoInstall(): bool
    {
        try {
            RegisterModule($this->MODULE_ID);
        } catch (Exception $e) {
            global $APPLICATION;
            $APPLICATION->ThrowException(
                $e->getMessage()
            );
            UnRegisterModule($this->MODULE_ID);
            return false;
        }
        return true;
    }


    /**
     * @return void
     */
    public function DoUninstall(): void
    {
        UnRegisterModule($this->MODULE_ID);
    }


    /**
     * Return path module
     *
     * @return string
     */
    protected function getModulePath(): string
    {
        $modulePath = explode('/', __FILE__);
        $modulePath = array_slice(
            $modulePath,
            0,
            array_search($this->MODULE_ID, $modulePath) + 1
        );

        return implode('/', $modulePath);
    }

}
