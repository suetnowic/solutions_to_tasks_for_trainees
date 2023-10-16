<?php

$eventManager = \Bitrix\Main\EventManager::getInstance();

// регистрация обработчика событий
$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementAdd', ['\\Only\\Site\\Handlers\\Iblock', "addLog"]);
$eventManager->addEventHandler("iblock", "OnAfterIBlockElementUpdate", ['\\Only\\Site\\Handlers\\Iblock', "addLog"]);

// регистрация агента
CAgent::AddAgent(Only\Site\Agents\Iblock::class . '::clearOldLogs()', 'log', 'N', 3600, "");

\Bitrix\Main\Loader::registerAutoLoadClasses(null, array(
    '\\Only\\Site\\Handlers\\Iblock' => '/local/modules/dev.site/lib/Handlers/Iblock.php',
));
