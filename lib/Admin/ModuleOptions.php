<?php

namespace QSOFT\BizprocMigration\Admin;

use Bitrix\Main\Localization\Loc;
use QSOFT\BizprocMigration\File\FileGenerator;
use QSOFT\BizprocMigration\Options\OptionTypes\Text;
use QSOFT\BizprocMigration\Options\AbstractConfigurable;

class ModuleOptions extends AbstractConfigurable
{
    public const OPTIONS = [
        'FILE_PATH' => 'file_path',
    ];

    /**
     * @return string
     */
    public static function getOptionPrefix(): string
    {
        return 'global_';
    }

    /**
     * @return void
     */
    protected static function declareOptions(): void
    {
        static::declareOption(
            self::OPTIONS['FILE_PATH'],
            Loc::getMessage('QSOFT_BIZPROC_PATH_OPTION'),
            Text::class,
            FileGenerator::DEFAULT_FILE_PATH
        );
    }

    /**
     * @return string
     */
    public static function getOptionsSectionTitle(): string
    {
        return Loc::getMessage('QSOFT_BIZPROC_SETTINGS_TAB_TITLE');
    }

    /**
     * @return string
     */
    public static function getOptionsTabTitle(): string
    {
        return Loc::getMessage('QSOFT_BIZPROC_SETTINGS_TAB_NAME');
    }
}
