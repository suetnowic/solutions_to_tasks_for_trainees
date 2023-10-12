<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/local/modules/dev.site/lib/Handlers/Iblock.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/local/modules/dev.site/lib/Agents/Iblock.php');

use Bitrix\Main\EventManager;
use Only\Site\Handlers\Iblock as IblockHandler;
use Only\Site\Agents\Iblock as IblockAgent;

$eventManager = EventManager::getInstance();

// регистрация обработчика событий
$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementAdd', [IblockHandler::class, 'addLog']);
$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementUpdate', [IblockHandler::class, 'addLog']);

// регистрация агента
CAgent::AddAgent(IblockAgent::class . '::clearOldLogs()', 'log', 'N', 3600);
