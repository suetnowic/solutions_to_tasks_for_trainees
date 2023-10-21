<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;


class NewsList extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams): array
    {
        $arParams['CACHE_TIME'] = $arParams['CACHE_TIME'] ?: 36000000;
        $arParams['IBLOCK_TYPE'] = trim($arParams['IBLOCK_TYPE']);
        $arParams['IBLOCK_ID'] = trim($arParams['IBLOCK_ID'] ?? '');
        $arParams['FILTER_NAME'] = $arParams['FILTER_NAME'] ?: [];
        $arParams['SORT_FIELD'] = $arParams['SORT_FIELD'] ?: 'ACTIVE_FROM';
        $arParams['SORT_ORDER'] = $arParams['SORT_ORDER'] === 'ASC' ? 'ASC' : 'DESC';
        $arParams['NEWS_COUNT'] = (int)$arParams['NEWS_COUNT'] ?: 20;
        return $arParams;
    }

    public function onIncludeComponentLang(): void
    {
        Loc::loadMessages(__FILE__);
    }

    public function executeComponent(): void
    {
        try {
            $this->checkModules();
            $this->getResult();
            $this->includeComponentTemplate();
        } catch (Exception $exception) {
            ShowError($exception->getMessage());
        }
    }

    private function getResult(): void
    {
        $arParams = $this->arParams;

        if (empty($arParams['IBLOCK_TYPE'])) {
            ShowError("Не указан тип инфоблока");
        }
        // если указан id получаем элементы только этого инфоблока
        if (!empty($arParams['IBLOCK_ID'])) {
            $arResult['ITEMS'] = $this->getElements($arParams['IBLOCK_ID']);
        } else {
            // иначе получаем все элементы по типу инфоблока
            $arResult['ITEMS'] = $this->getElementsByIBlockType($arParams['IBLOCK_TYPE']);

            //группируем по id инфоблоков
            $groupedItems = [];
            foreach ($arResult['ITEMS'] as $item) {
                $iblockId = $item['IBLOCK_ID'];
                if (!array_key_exists($iblockId, $groupedItems)) {
                    $groupedItems[$iblockId] = [];
                }
                $groupedItems[$iblockId][] = $item;
            }
            $arResult['ITEMS'] = $groupedItems;
        }

        $this->arResult = $arResult;

    }

    private function getElementsByIBlockType($iblockType): array
    {
        $iblocks = CIBlock::GetList([], ['TYPE' => $iblockType]);
        $arResult = [];
        while ($iblock = $iblocks->Fetch()) {
            $elements = CIBlockElement::GetList([], ['IBLOCK_ID' => $iblock['ID']]);
            while ($element = $elements->Fetch()) {
                $arResult[] = $element;
            }
        }
        return $arResult;
    }

    private function getElements($iblockId): array
    {
        $elements = CIBlockElement::GetList([], ['IBLOCK_ID' => $iblockId]);
        $arResult = [];
        while ($element = $elements->Fetch()) {
            $arResult[] = $element;
        }
        return $arResult;
    }

    /**
     * @throws LoaderException
     */
    private function checkModules(): void
    {
        if (!Loader::includeModule("iblock")) {
            throw new LoaderException(Loc::getMessage("IBLOCK_MODULE_NOT_INSTALLED"));
        }
    }

}