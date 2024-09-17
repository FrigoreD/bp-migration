<?php

namespace BizprocMigration\Admin\Import;
use QSOFT\BizprocMigration\BizprocTemplate;
use CBPWorkflowTemplateLoader;

/**
 * Производит импорт файла в таблицу бизнес-процессов
 */
class ImportController
{
    protected BizprocTemplate $bizprocTemplate;

    public function __construct(BizprocTemplate $bizprocTemplate)
    {
        $this->bizprocTemplate = $bizprocTemplate;
    }

    /**
     * Импортирует БП
     * @return int
     */
    public function import(): int
    {
        $bizprocId = $this->searchTemplate();

        return (int)CBPWorkflowTemplateLoader::importTemplateFromArray(
            $bizprocId,
            $this->bizprocTemplate->getDocumentType(),
            $this->bizprocTemplate->getAutoExecute(),
            $this->bizprocTemplate->getName(),
            $this->bizprocTemplate->getDescription(),
            [
                'TEMPLATE' => $this->bizprocTemplate->getTemplate(),
                'PARAMETERS' => $this->bizprocTemplate->getParameters(),
                'VARIABLES' => $this->bizprocTemplate->getVariables(),
                'CONSTANTS' => $this->bizprocTemplate->getConstants(),
            ],
            false,
            true
        );
    }


    /**
     * Ищет ID шаблона по его параметрам
     * @return int
     */
    public function searchTemplate(): int
    {
        return (int)CBPWorkflowTemplateLoader::GetList(
            [],
            [
                'NAME'      => $this->bizprocTemplate->getName(),
                'MODULE_ID' => $this->bizprocTemplate->getModuleId(),
            ]
        )->fetch()['ID'];
    }
}
