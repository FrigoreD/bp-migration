<?php

use Bitrix\Main\Loader;
use QSOFT\BizprocMigration\Admin\ModuleOptions;
use QSOFT\BizprocMigration\Admin\Options;
use QSOFT\BizprocMigration\Options\Option;
use QSOFT\BizprocMigration\Options\OptionStores\ModuleOptionStore;


Loader::includeModule('qsoft.bizproc_migration');

$page = new Options();
$page->show();
