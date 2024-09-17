<?php

namespace QSOFT\BizprocMigration\Variables;

use Bitrix\Main\Localization\Loc;
use VebUser;
use Exception;

/**
 * Получает код формата group_g по коду группы
 */
class GroupVariable
{
    public const GROUP_PREFIX = 'group_g';
    /**
     * Получает код формата group_g по коду группы
     * @param string $code
     * @return string
     * @throws Exception
     */
    public static function getGroupString(string $code): string
    {
        $groupId = VebUser::getUserGroupId($code);

        if (!$groupId > 0) {
            throw new Exception(Loc::getMessage('QSOFT_BIZPROC_GROUP_NOT_FOUND', ['#CODE#' => $code]));
        }

        return static::GROUP_PREFIX . VebUser::getUserGroupId($code);
    }
}
