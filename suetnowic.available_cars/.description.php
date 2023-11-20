<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = array(
    'NAME' => GetMessage('AVAILABLE_CARS_NAME'),
    'DESCRIPTION' => GetMessage('AVAILABLE_CARS_DESC'),
    'ICON' => '',
    'SORT' => 20,
    'COMPLEX' => 'N',
    'PATH' => array(
        'ID' => 'office',
        'NAME' => GetMessage('AVAILABLE_CARS'),
    )
);