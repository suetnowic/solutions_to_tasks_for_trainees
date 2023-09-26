<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
{
	die();
}
/** @var array $arParams */
/*$arParams['USE_SHARE'] = (string)($arParams['USE_SHARE'] ?? 'N');
$arParams['USE_SHARE'] = $arParams['USE_SHARE'] === 'Y' ? 'Y' : 'N';
$arParams['SHARE_HIDE'] = (string)($arParams['SHARE_HIDE'] ?? 'N');
$arParams['SHARE_HIDE'] = $arParams['SHARE_HIDE'] === 'Y' ? 'Y' : 'N';
$arParams['SHARE_TEMPLATE'] = (string)($arParams['SHARE_TEMPLATE'] ?? 'N');
$arParams['SHARE_HANDLERS'] ??= [];
$arParams['SHARE_HANDLERS'] = is_array($arParams['SHARE_HANDLERS']) ? $arParams['SHARE_HANDLERS'] : [];
$arParams['SHARE_SHORTEN_URL_LOGIN'] = (string)($arParams['SHARE_SHORTEN_URL_LOGIN'] ?? 'N');
$arParams['SHARE_SHORTEN_URL_KEY'] = (string)($arParams['SHARE_SHORTEN_URL_KEY'] ?? 'N'); */


/*
$rsSections = CIBlockSection::GetList(
    Array("SORT" => "ASC"),
    Array(
        "IBLOCK_ID" => $arParams['IBLOCK_ID']
    )
);


while ($arSection = $rsSections->Fetch()) {
    $arSections[] = array(
		'SECTION_ID' => $arSection['ID'],
		'SECTION_NAME' => $arSection['NAME'],
		'IBLOCK_SECTION_ID' => $arSection['IBLOCK_SECTION_ID']
	);
}



// По нему производим неявную фильрацию
foreach($arResult["ITEMS"] as $arItem) {
    $arSections[$arItem['IBLOCK_SECTION_ID']]['ITEMS'][] = $arItem;
}

$arResult["SECTIONS"] = $arSections; 
*/

/*
$dbResSect = CIBlockSection::GetList(
   Array("SORT"=>"ASC"),
   Array("IBLOCK_ID"=>$arParams['IBLOCK_ID'])
);

//Получаем разделы и собираем в массив
while($sectRes = $dbResSect->GetNext())
{
	$arSections[] = $sectRes;
}

//Собираем  массив из Разделов и элементов
foreach($arSections as $arSection){  
	
	foreach($arResult["ITEMS"] as $key=>$arItem){
		
		 if($arItem['IBLOCK_SECTION_ID'] == $arSection['ID']){
			$arSection['ELEMENTS'][] =  $arItem;
		 }
	}
	
	$arElementGroups[] = $arSection;
	
}

$arResult["ITEMS"] = $arElementGroups;
*/