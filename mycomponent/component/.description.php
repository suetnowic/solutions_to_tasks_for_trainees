<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    "NAME" =>"Элементы",
    "DESCRIPTION" => "Элементы инфоблока",
    "ICON" => "",
    "SORT" => 20,
//	"SCREENSHOT" => array(
//		"/images/post-77-1108567822.jpg",
//		"/images/post-1169930140.jpg",
//	),
    "CACHE_PATH" => "Y",
    "PATH" => array(
        "ID" => "mycomponent",
        "CHILD" => array(
            "ID" => "component",
            "NAME" => "Мои компоненты",
            "SORT" => 10,
            "CHILD" => array(
                "ID" => "comp",
            ),
        ),
    ),
);