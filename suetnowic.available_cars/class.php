<?php

use Bitrix\Main\Context;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();


class AvailableCars extends CBitrixComponent
{

    public function onPrepareComponentParams($arParams): array
    {
        $arParams['IBLOCK_TYPE'] = trim($arParams['IBLOCK_TYPE']);
        $arParams['IBLOCK_ID'] = trim($arParams['IBLOCK_ID'] ?? '');
        return $arParams;
    }

    public function onIncludeComponentLang(): void
    {
        Loc::loadMessages(__FILE__);
    }

    public function executeComponent(): void
    {

        $request = Context::getCurrent()->getRequest();
        $startTimeParam = strtotime($request->get('start_time'));
        $endTimeParam = strtotime($request->get('end_time'));

        $currentUserId = CurrentUser::get()->getId();

        $userPosition = CUser::GetList(['ID'], ['ID'], ['ID' => $currentUserId])->Fetch()['WORK_POSITION'];
        $posRes = CIBlockElement::GetList([], ['IBLOCK_CODE' => 'POSITIONS', 'NAME' => $userPosition], false, false, ['NAME', '*']);
        $element = $posRes->Fetch();

        $propsPosRes = CIBlockElement::GetProperty($element['IBLOCK_ID'], $element['ID'], ['sort' => 'asc'], ['CODE' => 'COMFORT_LEVEL']);
        $comfortLevels = [];
        while ($prop = $propsPosRes->GetNext()) {
            $comfortLevels[] = $prop['VALUE'];
        }

        $carsResult = CIBlockElement::GetList([], ['IBLOCK_CODE' => 'COMPANY_CARS'], false, false, ['*']);
        $companyCars = [];

        while ($element = $carsResult->GetNext()) {

            $propsCarRes = CIBlockElement::GetProperty($element['IBLOCK_ID'], $element['ID'], ['SORT' => 'ASC']);

            $tmpCompanyCars = [];
            while ($props = $propsCarRes->GetNext()) {
                $tmpCompanyCars[] = $props['VALUE'];
            }
            for ($i = 0; $i < count($tmpCompanyCars); $i += 3) {
                $key = $tmpCompanyCars[$i];
                $value1 = $tmpCompanyCars[$i + 1];
                $value2 = $tmpCompanyCars[$i + 2];
                $companyCars[$key] = array($value1, $value2);
            }
        }

        $allCarsPosition = [];
        foreach ($companyCars as $key => $value) {
            if (in_array($value[0], $comfortLevels)) {
                $allCarsPosition[$key] = $value;
            }
        }

        $tripsRes = CIBlockElement::GetList([], ['IBLOCK_CODE' => 'TRIPS'], false, false, ['*']);
        $trips = [];
        while ($element = $tripsRes->GetNext()) {
            $propsTripRes = CIBlockElement::GetProperty($element['IBLOCK_ID'], $element['ID'], ['SORT' => 'DESC']);
            $tmpTrip = [];
            while ($props = $propsTripRes->GetNext()) {
                $tmpTrip[] = $props['VALUE'];
            }
            for ($i = 0; $i < count($tmpTrip); $i += 4) {
                $startTime = strtotime($tmpTrip[$i + 1]);
                $endTime = strtotime($tmpTrip[$i + 2]);
                $tripData = [
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                ];
                $trips[$this->getNameById($tmpTrip[$i + 3])] = $tripData;
            }
        }

        $tripCarNames = array_keys($trips);
        $availableCarNames = array_keys($allCarsPosition);

        $unreservedCars = array_diff($availableCarNames, $tripCarNames);

        $availableCarsInTime = [];
        foreach ($trips as $carId => $value) {
            $carStartTime = $value['start_time'];
            $carEndTime = $value['end_time'];

            $carIsAvailable = true;

            if (($startTimeParam >= $carStartTime && $startTimeParam <= $carEndTime) && ($endTimeParam >= $carStartTime && $endTimeParam <= $carEndTime)) {
                $carIsAvailable = false;
            }
            if ($carIsAvailable) {
                $availableCarsInTime[] = $carId;
            }
        }
        foreach ($unreservedCars as $carName) {
            $availableCarsInTime[] = $carName;
        }

        $filteredArray = array_intersect_key($companyCars, array_flip($availableCarsInTime));

        echo 'Доступные автомобили на запрошенное время: <br>';
        foreach ($filteredArray as $key => $value) {
            echo 'Модель: ' . $key . '<br>';
            echo 'Категория комфорта: ' . $this->getComfortName($value[0]) . '<br>';
            echo 'Водитель: ' . $value[1] . '<br>';
        }

    }

    private function getNameById($id)
    {
        $companyCarsIBlock = CIBlockElement::GetList([], ['IBLOCK_CODE' => 'COMPANY_CARS'], false, false, ['IBLOCK_ID'])->Fetch();
        $propertyResult = CIBlockElement::GetProperty($companyCarsIBlock['IBLOCK_ID'], $id, ['CODE' => 'model'])->Fetch();
        return $propertyResult['VALUE'];
    }

    private function getComfortName($id)
    {
        $list = CIBlockElement::GetList([], ['IBLOCK_CODE' => 'COMFORT', 'ID' => $id], false, false, ['ID', 'NAME'])->Fetch();
        return $list['NAME'];
    }


}