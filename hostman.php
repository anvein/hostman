<?php
require_once __DIR__ . '/core/HostManager.php';
use anvi\manhost\HostManager;


//разбор входного маасива
$action = '';
$arPar = [];

foreach ($argv as $ind => $par) {
    if ($ind === 1) {
        $action = $par;
    } elseif ($ind > 1) {
        $arPar[] = $par;
    }
}

// определение команды
switch ($action) {
    case 'create' :
        if (HostManager::createHost($arPar)) {
            echo HostManager::getColorCode('green') . 'Виртуальный хост создан' . PHP_EOL . PHP_EOL;
        } else {
            echo HostManager::getColorCode('red') . 'Виртуальный хост не создан' . PHP_EOL . PHP_EOL;
            echo HostManager::getColorCode('white');
        }
        break;
    case 'delete' :
        if (HostManager::deleteHost()) {

        } else {
            echo HostManager::getColorCode('red') . 'Виртуальный хост не удален' . PHP_EOL . PHP_EOL;
            echo HostManager::getColorCode('white');
        }
        break;
    case 'help' :
        HostManager::viewHelp();
        break;
    default :
        echo HostManager::getColorCode('red') . 'Указана невеная команда' . PHP_EOL . PHP_EOL;
        echo HostManager::getColorCode('white');
        HostManager::viewHelp();
        break;
}