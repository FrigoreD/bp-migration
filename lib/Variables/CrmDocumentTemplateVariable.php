<?php

namespace QSOFT\BizprocMigration\Variables;

use Bitrix\DocumentGenerator\Model\TemplateTable;
use Bitrix\Main\Localization\Loc;
use Exception;

/**
 * Получает ID шаблона документа CRM
 */
class CrmDocumentTemplateVariable
{
    /**
     * @param string $templateName
     * @return int
     * @throws Exception
     */
    public static function getByName(string $templateName): int
    {
        $template = TemplateTable::getList([
            'select' => ['ID'],
            'filter' => ['NAME' => $templateName],
            'order' => ['ID' => 'DESC'],
            'limit'  => 1,
        ])->fetchObject();

        if (!$template) {
            throw new Exception(Loc::getMessage('QSOFT_BIZPROC_DOCUMENT_TEMPLATE_NOT_FOUND', ['#TEMPLATE_NAME#' => $templateName]));
        }

        return (int) $template->getId();
    }
}
