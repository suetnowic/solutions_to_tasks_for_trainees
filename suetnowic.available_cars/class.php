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

        $requestData = $this->handleRequestParam();
        $element = $this->getCurrentUserPosition();
        $comfortLevels = $this->getComfortLevelsForPosition($element);
        $companyCars = $this->getAllCompanyCars();
        $allCarsPosition = $this->getCarsForCurrentUserPosition($companyCars, $comfortLevels);
        $trips = $this->getAllTrips();

        $tripCarNames = array_keys($trips);
        $availableCarNames = array_keys($allCarsPosition);
        $unreservedCars = array_diff($availableCarNames, $tripCarNames);

        $availableCarsInTime = $this->checkAvailableCarsInTime($trips, $requestData, $unreservedCars, $availableCarNames);

        $filteredArray = array_intersect_key($companyCars, array_flip($availableCarsInTime));

        $this->formatFilteredArrayToResultArray($filteredArray);

        $this->includeComponentTemplate();
    }

    private function formatFilteredArrayToResultArray($filteredArray): void
    {
        $arResult = [];
        foreach ($filteredArray as $key => $value) {
            $arResult[$key] = [
                'comfort' => $this->getComfortName($value[0]),
                'driver' => $value[1]
            ];
        }
        $this->arResult = $arResult;

    }

    private function getComfortName($id)
    {
        $list = CIBlockElement::GetList([], ['IBLOCK_CODE' => 'COMFORT', 'ID' => $id], false, false, ['ID', 'NAME'])->Fetch();
        return $list['NAME'];
    }

    private function checkAvailableCarsInTime($trips, $requestData, $unreservedCars, $availableCarNames): array
    {
        $availableCarsInTime = [];

        $matchingTrips = $this->filterTripsByAvailableCars($trips, $availableCarNames);

        foreach ($matchingTrips as $carModel => $value) {
            $carStartTime = $value['start_time'];
            $carEndTime = $value['end_time'];
            $startTimeParam = $requestData['start_time'];
            $endTimeParam = $requestData['end_time'];

            $carIsAvailable = true;

            if (($startTimeParam >= $carStartTime && $startTimeParam <= $carEndTime) && ($endTimeParam >= $carStartTime && $endTimeParam <= $carEndTime)) {
                $carIsAvailable = false;
            }
            if ($carIsAvailable) {
                $availableCarsInTime[] = $carModel;
            }
        }
        foreach ($unreservedCars as $carName) {
            $availableCarsInTime[] = $carName;
        }

        return $availableCarsInTime;
    }

    private function filterTripsByAvailableCars($trips, $availableCarNames): array
    {
        $matchingTrips = [];

        foreach ($trips as $carModel => $value) {
            if (in_array($carModel, $availableCarNames)) {
                $matchingTrips[$carModel] = $value;
            }
        }
        return $matchingTrips;
    }

    private function getAllTrips(): array
    {
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
        return $trips;
    }

    private function getNameById($id)
    {
        $companyCarsIBlock = CIBlockElement::GetList([], ['IBLOCK_CODE' => 'COMPANY_CARS'], false, false, ['IBLOCK_ID'])->Fetch();
        $propertyResult = CIBlockElement::GetProperty($companyCarsIBlock['IBLOCK_ID'], $id, ['CODE' => 'model'])->Fetch();
        return $propertyResult['VALUE'];
    }

    private function getCarsForCurrentUserPosition($companyCars, $comfortLevels): array
    {
        $allCarsPosition = [];
        foreach ($companyCars as $key => $value) {
            if (in_array($value[0], $comfortLevels)) {
                $allCarsPosition[$key] = $value;
            }
        }
        return $allCarsPosition;
    }

    private function getAllCompanyCars(): array
    {
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
        return $companyCars;
    }

    private function getComfortLevelsForPosition(array $element): array
    {
        $propsPosRes = CIBlockElement::GetProperty($element['IBLOCK_ID'], $element['ID'], ['sort' => 'asc'], ['CODE' => 'COMFORT_LEVEL']);
        $comfortLevels = [];
        while ($prop = $propsPosRes->GetNext()) {
            $comfortLevels[] = $prop['VALUE'];
        }
        return $comfortLevels;
    }

    private function getCurrentUserPosition(): array
    {
        $currentUserId = CurrentUser::get()->getId();
        $userPosition = CUser::GetList(['ID'], ['ID'], ['ID' => $currentUserId])->Fetch()['WORK_POSITION'];
        return CIBlockElement::GetList([], ['IBLOCK_CODE' => 'POSITIONS', 'NAME' => $userPosition], false, false, ['NAME', '*'])->Fetch();
    }

    private function handleRequestParam(): array
    {
        $request = Context::getCurrent()->getRequest();
        $startTimeParam = strtotime($request->get('start_time'));
        $endTimeParam = strtotime($request->get('end_time'));
        return [
            'start_time' => $startTimeParam,
            'end_time' => $endTimeParam
        ];
    }
}