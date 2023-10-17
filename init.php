<?php
if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/modules/dev.site/include.php')) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/local/modules/dev.site/include.php';
}

use Only\Site\Agents\Iblock as Agent;
use Only\Site\Handlers\Iblock as Handler;


$eventManager = \Bitrix\Main\EventManager::getInstance();

// регистрация обработчика событий
$eventManager->registerEventHandler('iblock', 'OnAfterIBlockElementAdd', [Handler::class, "addLog"]);
$eventManager->registerEventHandler('iblock', "OnAfterIBlockElementUpdate", [Handler::class, "addLog"]);

// регистрация агента
CAgent::AddAgent(Agent::class . '::clearOldLogs()', 'log', 'N', 3600, "");
