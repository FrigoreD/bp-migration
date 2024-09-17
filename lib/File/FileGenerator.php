<?php

namespace QSOFT\BizprocMigration\File;

use Bitrix\Main\Context;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\Localization\Loc;
use QSOFT\BizprocMigration\Admin\ModuleOptions;
use QSOFT\BizprocMigration\ArrayParser;
use QSOFT\BizprocMigration\BizprocTemplate;
use Exception;

/**
 * Класс, отвечающий за генерацию PHP-файла с массивом в формате
 * <?php
 * ${имя массива} = []
 */
class FileGenerator
{
    private BizprocTemplate $bizprocTemplate;

    public const DEFAULT_FILE_PATH = '/local/bizproc/';

    public function __construct(BizprocTemplate $bizprocTemplate)
    {
        $this->bizprocTemplate = $bizprocTemplate;
    }

    /**
     * @param string $filePath
     * @return bool
     * @throws Exception
     */
    public function generatePhpFile(string $filePath = ''): bool
    {
        if (empty($filePath)) {
            if (!Directory::isDirectoryExists(self::getDirectory())) {
                if (!mkdir(self::getDirectory())) {
                    throw new Exception(Loc::getMessage('QSOFT_BIZPROC_NO_RIGHT_DIRECTORY'));
                }
            }
            $filePath = $this->getDirectory() . $this->getFileName();
        }

        $result = (bool)file_put_contents($filePath, $this->preparePhpFile());
        if (!$result) {
            throw new Exception(Loc::getMessage('QSOFT_BIZPROC_NO_RIGHT_FILE'));
        }

        return true;
    }

    /**
     * @return string
     */
    public function getBase64PhpFile(): string
    {
        return base64_encode($this->preparePhpFile());
    }

    /**
     * @return string
     */
    protected function preparePhpFile(): string
    {
        return ArrayParser::arrayToPhp(
            $this->bizprocTemplate->getArray(),
            $this->bizprocTemplate->getVariableName()
        );
    }


    /**
     * @return string
     */
    protected function getDirectory(): string
    {
        $filePath = ModuleOptions::getOption(ModuleOptions::OPTIONS['FILE_PATH'])->getValue()
            ?: self::DEFAULT_FILE_PATH;

        return Context::getCurrent()->getServer()->getDocumentRoot() . $filePath;
    }

    /**
     * @return string
     */
    protected function getFileName(): string
    {
        return $this->bizprocTemplate->getUniqueId() . '.php';
    }
}
