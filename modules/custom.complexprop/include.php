<?php

include_once($_SERVER["DOCUMENT_ROOT"]."/local/modules/custom.complexprop/include.php");

\Bitrix\Main\Loader::registerAutoLoadClasses('custom.complexprop', [
    'CIBlockPropertyCProp' => 'lib/CIBlockPropertyCProp.php',
]);

