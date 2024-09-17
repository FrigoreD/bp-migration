<?php
namespace QSOFT\BizprocMigration\Variables\Deal;
use Bitrix\Crm\Category\Entity\DealCategoryTable;
use Bitrix\Main\Localization\Loc;
use Exception;

/**
 * Класс для получения ID категории сделки
 */
class Category
{
    /**
     * Получение ID сделки по имени
     * @param string $name
     * @return int
     * @throws Exception
     */
    public static function byName(string $name): int
    {
        $id = (int)DealCategoryTable::query()
            ->addFilter('NAME', $name)
            ->setLimit(1)
            ->addSelect('ID')
            ->fetch()['ID'];

        if (!($id > 0)) {
            throw new Exception(Loc::getMessage('QSOFT_BIZPROC_DEAL_CATEGORY_NOT_FOUND', ['#NAME#' => $name]));
        }

        return $id;
    }
}
