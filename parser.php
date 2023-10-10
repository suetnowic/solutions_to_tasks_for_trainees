<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

if (!$USER->IsAdmin()) {
    LocalRedirect('/');
}

use \Bitrix\Main\Loader;

Loader::includeModule('iblock');

if (!Loader::includeModule('iblock')) {
    die('Модуль "iblock" не установлен');
}

$IBLOCK_ID = 8;
$csvFile = $_SERVER['DOCUMENT_ROOT'] . "/upload/vacancy.csv";

$el = new CIBlockElement;
$propertyEnum = getPropertyEnum();
$rows = readCsv($csvFile);
$PROP = [];

foreach ($rows as $data) {

    $PROP['ACTIVITY'] = $PROP['ACTIVITY'] = getId('ACTIVITY', $data[9]);
    $PROP['FIELD'] = $PROP['FIELD'] = getId('FIELD', $data[11]);
    $PROP['OFFICE'] = $data[1];
    $PROP['LOCATION'] = $data[2];
    $PROP['REQUIRE'] = $data[4];
    $PROP['DUTY'] = $data[5];
    $PROP['CONDITIONS'] = $data[6];
    $PROP['EMAIL'] = $data[12];
    $PROP['DATE'] = date('d.m.Y');
    $PROP['TYPE'] = getId('TYPE', $data[8]);
    $PROP['SALARY_TYPE'] = '';
    $PROP['SALARY_VALUE'] = $data[7];
    $PROP['SCHEDULE'] = getId('SCHEDULE', $data[10]);

    foreach ($PROP as $key => $value) {
        $value = trim($value);
        if (stripos($value, "•") !== false) {
            $value = str_replace("\n", "", $value);
            $value = explode("•", $value);
            array_splice($value, 0, 1);

            if (!is_array($PROP[$key])) {
                $PROP[$key] = [];
            }

            foreach ($value as $arValue) {
                $arVal = trim($arValue);
                $PROP[$key][] = $arVal;
            }
        }

        if ($key == 'LOCATION') {
            foreach ($propertyEnum['LOCATION'] as $arKey => $arValue) {
                if (str_contains($arKey, mb_strtolower($value))) {
                    $PROP['LOCATION'] = $arValue;
                }
            }
        }

        if ($key == "OFFICE") {
            $value = str_replace("\n", " ", $value);
            $PROP['OFFICE'] = getOfficeId($value);
        }
    } // endforeach

    if ($PROP['SALARY_VALUE'] == '-') {
        $PROP['SALARY_VALUE'] = '';
    } elseif ($PROP['SALARY_VALUE'] == 'по договоренности') {
        $PROP['SALARY_VALUE'] = '';
        $PROP['SALARY_TYPE'] = $propertyEnum['SALARY_TYPE']['договорная'];
    } else {
        $arSalary = explode(' ', $PROP['SALARY_VALUE']);
        $part = trim($arSalary[0]);
        if ($part == "от" || $part == "до") {
            $PROP['SALARY_TYPE'] = $propertyEnum['SALARY_TYPE'][$part];
            array_splice($arSalary, 0, 1);
            $PROP['SALARY_VALUE'] = implode(' ', $arSalary);
        } else {
            $PROP['SALARY_TYPE'] = $propertyEnum['SALARY_TYPE']['='];
        }
    }

    $arLoadProductArray = [
        "MODIFIED_BY" => $USER->GetID(),
        "IBLOCK_SECTION_ID" => false,
        "IBLOCK_ID" => $IBLOCK_ID,
        "PROPERTY_VALUES" => $PROP,
        "NAME" => $data[3],
        "ACTIVE" => end($rows) ? 'Y' : 'N',
    ];

    addElement($arLoadProductArray);
}

function getId($propCode, $value) {
    global $propertyEnum;
    $propertyId = $propertyEnum[$propCode][trim(mb_strtolower($value))];
    if ($propertyId == 0) {
        $propId = getPropertyIdByCode($propCode);
        $propertyEnumId = addPropertyValue($propId, $value);
        if ($propertyEnumId !== false) {
            $propertyId = $propertyEnumId;
        }
    }
    return $propertyId;
}

function getOfficeId($compareValue): int {
    global $propertyEnum;
    $result = 0;
    foreach ($propertyEnum as $subArray) {
        foreach ($subArray as $key => $value) {
            $keyLower = mb_strtolower(trim($key));
            $compareValueLower = mb_strtolower(trim($compareValue));
            if ($keyLower === $compareValueLower) {
                $result = $value;
            }
        }
    }
    return $result;
}

function getPropertyEnum(): array {
    global $IBLOCK_ID;
    $propertyEnum = CIBlockPropertyEnum::GetList(["SORT" => "ASC", "VALUE" => "ASC"], ['IBLOCK_ID' => $IBLOCK_ID]);
    $arProps = [];
    while ($enumFields = $propertyEnum->Fetch()) {
        $key = trim(mb_strtolower($enumFields["VALUE"]));
        $arProps[$enumFields['PROPERTY_CODE']][$key] = $enumFields['ID'];
    }
    return $arProps;
}

function readCsv($file): array {
    $rows = [];
    if (($handle = fopen($file, "r")) !== false) {
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            $rows[] = $data;
        }
        fclose($handle);
    }
    array_shift($rows);
    return $rows;
}

function addElement(array $element): void {
    global $el;
    if ($PRODUCT_ID = $el -> Add($element)) {
        echo "Добавлен элемент с ID : " . $PRODUCT_ID . "<br>";
    } else {
        echo "Error: " . $el->LAST_ERROR . '<br>';
    }
}

function deleteElementsIBlock(): void {
    global $IBLOCK_ID;
    if (CIBlock::GetElementCount($IBLOCK_ID) > 0) {
        $rsElements = CIBlockElement::GetList([], ['IBLOCK_ID' => $IBLOCK_ID], false, false, ['ID']);
        while ($element = $rsElements->GetNext()) {
            CIBlockElement::Delete($element['ID']);
        }
    }
}

function getPropertyIdByCode($code): int {
    global $IBLOCK_ID;
    $properties = CIBlockProperty::GetList([], ["IBLOCK_ID" => $IBLOCK_ID, "CODE" => $code]);
    $propId = 0;
    while ($prop = $properties -> GetNext()) {
        $propId = $prop['ID'];
    }
    return $propId;
}

function addPropertyValue(int $propId, string $value) {
    $value = trim($value);
    global $propertyEnum;
    $addPropId = CIBlockPropertyEnum::Add(['VALUE' => $value, 'PROPERTY_ID' => $propId, 'XML_ID' => CUtil::translit($value, "ru")]);
    $propertyEnum = getPropertyEnum();
    return $addPropId;
}
