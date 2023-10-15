<?php

use Bitrix\Main\EventManager;
use Only\Site\Handlers\Iblock as IblockHandler;
use Only\Site\Agents\Iblock as IblockAgent;

$eventManager = EventManager::getInstance();

// регистрация обработчика событий
$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementAdd', [IblockHandler::class, 'addLog']);
$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementUpdate', [IblockHandler::class, 'addLog']);

// регистрация агента
CAgent::AddAgent(IblockAgent::class . '::clearOldLogs()', 'log', 'N', 3600);
