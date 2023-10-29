<?php

use Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc,
    \Bitrix\Main\EventManager;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class custom_complexprop extends CModule
{
    var $MODULE_ID = 'custom.complexprop';

    function __construct()
    {
        $arModuleVersion = array();
        include __DIR__ . '/version.php';

        $this->MODULE_ID = 'custom.complexprop';
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage('IEX_CPROP_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('IEX_CPROP_MODULE_DESC');

        $this->PARTNER_NAME = Loc::getMessage('IEX_CPROP_PARTNER_NAME');
        $this->PARTNER_URI = 'https://phpdev.org';

        $this->FILE_PREFIX = 'complexprop';
        $this->MODULE_FOLDER = str_replace('.', '_', $this->MODULE_ID);
        $this->FOLDER = 'local';

        $this->INSTALL_PATH_FROM = '/' . $this->FOLDER . '/modules/' . $this->MODULE_ID;
    }

    function isVersionD7()
    {
        return true;
    }

    function DoInstall()
    {
        global $APPLICATION;
        if ($this->isVersionD7()) {
            $this->InstallFiles();
            $this->InstallDB();
            $this->InstallEvents();

        } else {
            $APPLICATION->ThrowException(Loc::getMessage('IEX_CPROP_INSTALL_ERROR_VERSION'));
        }
    }

    function DoUninstall()
    {

        $this->UnInstallFiles();
        $this->UnInstallEvents();
        $this->UnInstallDB();
    }


    function InstallDB()
    {
        ModuleManager::registerModule('custom.complexprop');
//        EventManager::getInstance()->registerEventHandlerCompatible('main', 'OnUserTypeBuildList',
//            'custom.complexprop', 'lib\CustomComplexProperty', 'GetUserTypeDescription');
        return true;
    }

    function UnInstallDB()
    {
//        EventManager::getInstance()->unRegisterEventHandler('main', 'OnUserTypeBuildList',
//            'custom.complexprop', 'lib\CustomComplexProperty', 'GetUserTypeDescription');
        ModuleManager::unRegisterModule('custom.complexprop');
        return true;
    }

    function installFiles()
    {
        return true;
    }

    function uninstallFiles()
    {
        return true;
    }

    function getEvents()
    {
        return [
            ['FROM_MODULE' => 'iblock', 'EVENT' => 'OnIBlockPropertyBuildList', 'TO_METHOD' => 'GetUserTypeDescription'],
        ];
    }

    function InstallEvents()
    {
        $classHandler = 'CIBlockPropertyCprop';
        $eventManager = EventManager::getInstance();

        $arEvents = $this->getEvents();
        foreach ($arEvents as $arEvent) {
            $eventManager->registerEventHandler(
                $arEvent['FROM_MODULE'],
                $arEvent['EVENT'],
                $this->MODULE_ID,
                $classHandler,
                $arEvent['TO_METHOD']
            );
        }

        return true;
    }

    function UnInstallEvents()
    {
        $classHandler = 'CIBlockPropertyCprop';
        $eventManager = EventManager::getInstance();

        $arEvents = $this->getEvents();
        foreach ($arEvents as $arEvent) {
            $eventManager->unregisterEventHandler(
                $arEvent['FROM_MODULE'],
                $arEvent['EVENT'],
                $this->MODULE_ID,
                $classHandler,
                $arEvent['TO_METHOD']
            );
        }

        return true;
    }
}