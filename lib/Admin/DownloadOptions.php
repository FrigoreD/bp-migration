<?php

declare(strict_types=1);

namespace QSOFT\BizprocMigration\Admin;

use Bitrix\Main\Localization\Loc;

use QSOFT\BizprocMigration\Options\AbstractConfigurable;


class DownloadOptions extends AbstractConfigurable
{
    public const OPTIONS = [
        'FILE_PATH' => 'file_path',
    ];

    /**
     * @return string
     */
    public static function getOptionPrefix(): string
    {
        return 'download_';
    }

    /**
     * @return void
     */
    protected static function declareOptions(): void
    {
    }

    /**
     * @return string
     */
    public static function getOptionsSectionTitle(): string
    {
        return Loc::getMessage('QSOFT_BIZPROC_DOWNLOAD_TAB_TITLE');
    }

    /**
     * @return string
     */
    public static function getOptionsTabTitle(): string
    {
        return Loc::getMessage('QSOFT_BIZPROC_DOWNLOAD_TAB_NAME');
    }

    /**
     * @return string
     */
    public static function getContent(): string
    {
        return '<form>
                    <input 
                    data-get-bp-template-input 
                    type="text" 
                    placeholder="' . Loc::getMessage('QSOFT_BIZPROC_DOWNLOAD_INPUT_TEXT') . '">
                    <input type="button" 
                    value="' . Loc::getMessage('QSOFT_BIZPROC_DOWNLOAD_BUTTON_TEXT') . '" 
                    data-get-bp-template-button>
                </form>';
    }
}