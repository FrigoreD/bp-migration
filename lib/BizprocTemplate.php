<?php

namespace QSOFT\BizprocMigration;

use Bitrix\Main\IO\File;
use Exception;

/**
 * Класс, описывающий шаблон бизнес-процесса Битрикс
 */
class BizprocTemplate
{
    const DEFAULT_VARIABLE_NAME = 'bpTemplate';

    const UNIQUE_ID_PREFIX = 'bp_';

    const DOCUMENT_TYPE_MODULE_ID_KEY = 0;
    /**
     * Шаблон БП
     * @var mixed
     */
    protected array $template;

    /**
     * Переменные БП
     * @var mixed
     */
    protected array $variables;

    /**
     * ID бизнес-процесса
     * @var mixed
     */
    protected int $id;

    /**
     * Имя бизнесс-процесса
     * @var mixed
     */
    protected string $name;

    /**
     * Описание бизнесс-процесса
     * @var mixed
     */
    protected string $description = '';

    /**
     * Документ тайп для БП
     * @var mixed
     */
    protected array $documentType = [];

    /**
     * Автозапуск
     * @var mixed
     */
    protected int $autoExecute = 0;

    /**
     * Уникальный идентификатор
     * @var string
     */
    protected string $uniqueId;

    /**
     * ID модуля
     * @var string
     */
    protected string $moduleId;

    /**
     * Константы  БП
     * @var mixed
     */
    protected $constants;
    /**
     * Параметры  БП
     * @var mixed
     */
    protected $parameters;


    public function __construct(array $arTemplate)
    {
        $this->template = $arTemplate['TEMPLATE'];
        $this->variables = $arTemplate['VARIABLES'] ?? [];
        $this->id = $arTemplate['ID'] ?? 0;
        $this->documentType = $arTemplate['DOCUMENT_TYPE'];
        $this->name = $arTemplate['NAME'];
        $this->description = $arTemplate['DESCRIPTION'];
        $this->autoExecute = $arTemplate['AUTO_EXECUTE'];
        $this->setUniqueId($arTemplate['UNIQUE_ID']);
        $this->moduleId = $arTemplate['DOCUMENT_TYPE'][self::DOCUMENT_TYPE_MODULE_ID_KEY];
        $this->constants = $arTemplate['CONSTANTS'] ?? [];
        $this->parameters = $arTemplate['PARAMETERS'] ?? [];

    }

    /**
     * @return array
     */
    public function getArray(): array
    {
        $arTemplate = [];

        $arTemplate['NAME'] = $this->name;
        $arTemplate['DESCRIPTION'] = $this->description;
        $arTemplate['DOCUMENT_TYPE'] = $this->documentType;
        $arTemplate['TEMPLATE'] = $this->template;
        $arTemplate['VARIABLES'] = $this->variables;
        $arTemplate['CONSTANTS'] = $this->constants;
        $arTemplate['PARAMETERS'] = $this->parameters;
        $arTemplate['AUTO_EXECUTE'] = $this->autoExecute;
        $arTemplate['UNIQUE_ID'] = $this->uniqueId;

        return $arTemplate;
    }

    /**
     *
     * Производит запрос в БД, получает информацию о бизнес-процессе
     * @param int $id - ID Бизнес-процесс
     * @param string $uniqueId - уникальный идентификатор который будет использован в  названии php файла
     * @return BizprocTemplate|null
     */
    public static function initFromTemplateId(int $id, string $uniqueId): ?BizprocTemplate
    {
        $arFieldsTemplate = \CBPWorkflowTemplateLoader::GetList([], ['ID' => $id])->GetNext();

        if (!empty($arFieldsTemplate)) {
            $arFieldsTemplate['UNIQUE_ID'] = $uniqueId;
            $template = new static($arFieldsTemplate);
        }

        return $template;
    }

    /**
     *
     * Инициализирует объект из PHP файла с массивом бизнес-процесса
     *
     * @param File $templateFile - файл с переменными шаблона
     * @param string $variableName - имя переменной из файла
     * @return BizprocTemplate|null
     * @throws Exception
     */
    public static function initFromFile(File $templateFile, string $variableName = self::DEFAULT_VARIABLE_NAME): ?BizprocTemplate
    {
        if ($templateFile->isExists() &&  $templateFile->getExtension() === 'php') {
            include($templateFile->getPhysicalPath());
            $arFieldsTemplate = ${$variableName};
            ArrayParser::phpToArray($arFieldsTemplate);
        }

        if (!empty($arFieldsTemplate)) {
            self::parseTemplate($arFieldsTemplate['TEMPLATE']);
        }

        return !empty($arFieldsTemplate) ? new static($arFieldsTemplate) : null;
    }

    /**
     * @param $uniqueId
     */
    public function setUniqueId($uniqueId)
    {
        $this->uniqueId = $uniqueId;
    }

    /**
     * @return string
     */
    public function getUniqueId(): string
    {
        return $this->uniqueId;
    }

    /**
     * @return string
     */
    public function getVariableName(): string
    {
        return self::DEFAULT_VARIABLE_NAME;
    }

    /**
     * @return string
     */
    public function getModuleId(): string
    {
        return $this->moduleId;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getDocumentType()
    {
        return $this->documentType;
    }

    /**
     * @return mixed
     */
    public function getAutoExecute()
    {
        return $this->autoExecute;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getConstants()
    {
        return $this->constants;
    }

    /**
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return mixed
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     */
    public function setTemplate($template): void
    {
        $this->template = $template;
    }

    /**
     * Рекурсивно проходит по активити шаблона БП и выполняет функцию,
     *
     * @param array $arActivities
     * @return void
     * @throws Exception
     */
    protected static function parseTemplate(array &$arActivities): void
    {
        foreach ($arActivities as &$activity) {

            static::checkActivity($activity);

            if (isset($activity['Children']) && !empty($activity['Children'])) {
                self::parseTemplate($activity['Children']);
            }
        }
    }

    /**
     * Метод для переопределения в дочерних классах
     *
     * @param array $activity
     * @return void
     */
    public static function checkActivity(array &$activity): void
    {

    }
}
