<?php

use \Bitrix\Main\EventManager;

$eventManager = EventManager::getInstance();

$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementAdd', ['Only\\Site\\Handlers\\', 'addLog']);
$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementUpdate', ['Only\\Site\\Handlers\\', 'addLog']);
