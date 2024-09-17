<?php

namespace QSOFT\BizprocMigration\Variables;

use Bitrix\Main\Localization\Loc;
use CBPWorkflowTemplateLoader;
use Exception;
use CGroup;

/**
 * Класс для работы с переменными указывающими на номер бизнес-процесса для запуска
 */
class BizprocTemplateVariable
{
    /**
     * Возвращает Id по параметрам уникальности
     * @param string $name - 'NAME'
     * @param string $moduleId - 'MODULE_ID'
     * @param string $documentType - 'DOCUMENT_TYPE'
     * @param string $entity - 'ENTITY'
     * @return int
     * @throws Exception
     */
    public static function getId(string $name, string $moduleId, string $documentType, string $entity): int
    {
        $template = CBPWorkflowTemplateLoader::GetList([], [
            'NAME' => $name,
            'DOCUMENT_TYPE' => [
                $moduleId,
                $entity,
                $documentType,
            ]
        ])->fetch();

        if (!$template) {
            throw new Exception(Loc::getMessage('QSOOFT_BIZPROC_TEMPLATE_NOT_FOUND') . $name);
        }

        return (int) $template['ID'];
    }

    /**
     * @param string $name
     * @param bool $throwException
     * @return int
     * @throws Exception
     */
    public static function getDealTemplateIdByName(string $name, bool $throwException = true): int
    {
        $template = CBPWorkflowTemplateLoader::GetList(
            [
                'ID',
                'NAME',
            ],
            [
            'NAME' => $name,
            'DOCUMENT_TYPE' => [
                    'crm',
                    'CCrmDocumentDeal',
                    'DEAL',
                ],
            ]
        )->fetch();

        if ($throwException && !$template) {
            throw new Exception(Loc::getMessage('QSOOFT_BIZPROC_TEMPLATE_NOT_FOUND') . $name);
        }

        return (int) $template['ID'];
    }

    /**
     * @param string $categoryId - id категории
     * @param string $statusName - название статуса (например, LOSE)
     * @return string - пример: C123:LOSE
     */
    public static function getStatusCode(string $categoryId, string $statusName): string
    {
        return 'C' . $categoryId . ':' . $statusName;
    }

    /**
     * Получение кода группы для бп по символьному коду
     * @param string $groupCode
     * @return string - пример, group_g123
     */
    public static function getGroupBpCodeByCode(string $groupCode): string
    {
        $by = 'c_sort';
        $order = 'asc';
        $groupId = CGroup::GetList($by, $order, ['STRING_ID' => $groupCode])->fetch()['ID'];

        return 'group_g' . $groupId;
    }
}
