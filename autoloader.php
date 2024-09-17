<?php

spl_autoload_register(static function ($className) {
    $baseName = 'QSOFT\BizprocMigration';
    $className = trim(substr($className, strlen($baseName)), '\\');
    $classPath = __DIR__ . '/lib/' . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';

    if (file_exists($classPath)) {
        require_once($classPath);
    }
});
