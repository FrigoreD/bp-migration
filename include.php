<?php

namespace QSOFT\BizprocMigration;



use QSOFT\BizprocMigration\Admin\ModuleOptions;
use QSOFT\BizprocMigration\Admin\DownloadOptions;
use QSOFT\BizprocMigration\Admin\Options;
use QSOFT\BizprocMigration\Options\Option;
use QSOFT\BizprocMigration\Options\OptionStores\ModuleOptionStore;
use Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs('/local/modules/qsoft.bizproc_migration/js/script.js');

require_once __DIR__ . '/autoloader.php';
/**
 * Имя модуля
 */
const MODULE_ID = 'qsoft.bizproc_migration';

Option::setOptionStore(new ModuleOptionStore());
Options::registerOptions(ModuleOptions::class);
Options::registerOptions(DownloadOptions::class);
