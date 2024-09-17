<?php

declare(strict_types=1);

namespace QSOFT\BizprocMigration\Controller;

use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Engine\Response\AjaxJson;
use QSOFT\BizprocMigration\BizprocTemplate as BpTemplate;
use QSOFT\BizprocMigration\File\FileGenerator;
use Throwable;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Error;

class BizprocTemplate extends Controller
{
    /**
     * Получение шаблона бизнес-процесса по его id
     * @param int $id
     * @return AjaxJson
     */
    public function getBpTemplateAction(int $id):AjaxJson
    {
        try {
            $template = BpTemplate::initFromTemplateId($id, 'bp_' . $id);

            if ($template === null) {
                return AjaxJson::createError(new ErrorCollection([new Error('Шаблон бп не найден')]));
            }

            $base64File = (new FileGenerator($template))->getBase64PhpFile();
        } catch (Throwable $e) {
            return AjaxJson::createError(new ErrorCollection([new Error($e->getMessage())]));
        }

        return AjaxJson::createSuccess($base64File);
    }
}
