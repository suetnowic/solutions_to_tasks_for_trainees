<?php

namespace Only\Site\Agents;


class Iblock
{
    public static function clearOldLogs()
    {
        if (\Bitrix\Main\Loader::includeModule('iblock')) {
            $logIBlockCode = "LOG";
            $iblockId = CIBlock::GetList([], ['CODE' => $logIBlockCode])->Fetch()['ID'];
            $logElements = CIBlockElement::GetList(['ACTIVE_FROM' => 'DESC'], ['IBLOCK_ID' => $iblockId], false, false, ['ID', 'ACTIVE_FROM']);
            $i = 1;
            while ($element = $logElements->Fetch()) {
                if ($i > 10) {
                    CIBlockElement::Delete($element['ID']);
                }
                $i++;
            }
        }
        return "Iblock::clearOldLogs();";
    }

    public static function example()
    {
        global $DB;
        if (\Bitrix\Main\Loader::includeModule('iblock')) {
            $iblockId = \Only\Site\Helpers\IBlock::getIblockID('QUARRIES_SEARCH', 'SYSTEM');
            $format = $DB->DateFormatToPHP(\CLang::GetDateFormat('SHORT'));
            $rsLogs = \CIBlockElement::GetList(['TIMESTAMP_X' => 'ASC'], [
                'IBLOCK_ID' => $iblockId,
                '<TIMESTAMP_X' => date($format, strtotime('-1 months')),
            ], false, false, ['ID', 'IBLOCK_ID']);
            while ($arLog = $rsLogs->Fetch()) {
                \CIBlockElement::Delete($arLog['ID']);
            }
        }
        return '\\' . __CLASS__ . '::' . __FUNCTION__ . '();';
    }
}