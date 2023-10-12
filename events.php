<?php

use \Bitrix\Main\EventManager;
use Only\Site\Handlers\Iblock as IblockHandler;
//use Only\Site\Agents\Iblock as IblockAgent;

$eventManager = EventManager::getInstance();

$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementAdd', [IblockHandler::class, 'addLog']);
$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementUpdate', [IblockHandler::class, 'addLog']);
