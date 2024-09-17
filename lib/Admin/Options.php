<?php

// phpcs:disable PSR1.Files.SideEffects

namespace QSOFT\BizprocMigration\Admin;

use Bitrix\Main\Context;
use CAdminTabControl;

use QSOFT\BizprocMigration\Options\AbstractConfigurable;
use QSOFT\BizprocMigration\Options\Option;
use QSOFT\BizprocMigration\Options\OptionTypes\Boolean;
use function __AdmSettingsDrawList;
use const QSOFT\BizprocMigration\MODULE_ID;

IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/modules/main/options.php');

class Options
{

    /**
     * @var class-string<AbstractConfigurable>[]
     */
    protected static array $configurables = [];

    protected string $message;

    /**
     * @param class-string<AbstractConfigurable> $configurableClass
     * @return void
     */
    public static function registerOptions(string $configurableClass): void
    {
        static::$configurables[] = $configurableClass;
    }

    /**
     * @return void
     */
    public static function saveDefaultsToDb(): void
    {
        /** @var AbstractConfigurable $configurable */
        foreach (static::$configurables as $configurable) {
            $configurable::setDefaults();
        }
    }

    /**
     * @return array
     */
    protected function getTabs(): array
    {
        $tabs = [];

        foreach (static::$configurables as $configurable) {
            $tabs[] = $this->makeOptionTab($configurable);
        }

        return $tabs;
    }

    /**
     * @param class-string<AbstractConfigurable> $configurableClass
     * @return array
     */
    protected function makeOptionTab(string $configurableClass): array
    {
        $arTab = [
            'DIV' => $configurableClass::getOptionPrefix(),
            'TAB' => $configurableClass::getOptionsTabTitle(),
            'TITLE' => $configurableClass::getOptionsSectionTitle(),
            'OPTIONS' => $this->formatOptions($configurableClass::getAllOptions()),
        ];

        if(method_exists($configurableClass, 'getContent')) {
            $arTab['CONTENT'] = $configurableClass::getContent();
        }

        return $arTab;
    }

    /**
     * @param Option[] $options
     * @return array
     */
    protected function formatOptions(array $options): array
    {
        $formattedOptions = [];

        foreach ($options as $option) {
            if ($option->isHidden()) {
                continue;
            }

            $formattedOptions[] = [
                $option->getCode(),
                $option->getDescription(),
                $option->getDefaultValue(),
                $option->getBitrixDisplayParams()
            ];
        }

        return $formattedOptions;
    }

    /**
     * @return void
     */
    protected function doAction(): void
    {
        global $APPLICATION;

        if (check_bitrix_sessid()) {
            $request = Context::getCurrent()->getRequest();

            if (!empty($request->getPost('apply'))) {
                $this->applyAction();

                LocalRedirect($APPLICATION->GetCurPage() . '?lang=' . LANG . '&mid=' . MODULE_ID);
            }
        }
    }

    /**
     * @return void
     */
    protected function applyAction(): void
    {
        $request = Context::getCurrent()->getRequest();


        foreach ($this->collectAllOptions() as $option) {
            $code = $option->getCode();
            $value = $request->getPost($code);

            $this->saveOption($option, $value);
        }
    }

    /**
     * @return array
     */
    protected function collectAllOptions(): array
    {
        $options = [];

        /** @var AbstractConfigurable $configurable */
        foreach (static::$configurables as $configurable) {
            $options = array_merge($options, $configurable::getAllOptions());
        }

        return $options;
    }

    /**
     * @param Option $option
     * @param $value
     * @return void
     */
    protected function saveOption(Option $option, $value): void
    {
        if (is_null($value)) {
            /* Отключенный чекбокс передается как отсутствие значения */
            if ($option->getType() === Boolean::class) {
                $value = Boolean::BOOLEAN_TO_STRING[false];
            } else {
                return;
            }
        }

        $option->setValueFromBitrix($value);
    }

    /**
     * @return void
     */
    public function show(): void
    {
        ob_start();

        $this->doAction();

        $arTabs = $this->getTabs();

        $tabControl = new CAdminTabControl(
            'tabControl',
            $arTabs
        );

        $tabControl->Begin();

        global $APPLICATION;

        $action = sprintf(
            '%s?lang=%s&mid=%s',
            $APPLICATION->GetCurPage(),
            LANG,
            MODULE_ID
        );
        echo "<form action='$action' method='post'>";

        foreach ($arTabs as $aTab) {
            if ($aTab['OPTIONS']) {
                $tabControl->BeginNextTab();
                __AdmSettingsDrawList(MODULE_ID, $aTab['OPTIONS']);
            }
        }

        $tabControl->Buttons();


        echo sprintf(
            "<input type='submit' name='apply' value='%s' class='adm-btn-save'/>",
            GetMessage('MAIN_SAVE')
        );

        if (isset($this->message)) {
            ShowMessage([
                'MESSAGE' => $this->message,
                'TYPE' => 'OK',
            ]);
        }

        echo bitrix_sessid_post();
        echo "</form>";

        $tabControl->End();

        ob_end_flush();
    }
}
