<?php

use anvi\manhost\HostManager;

require_once __DIR__ . '/core/HostManager.php';


$hostman = new HostManager($argv);

if (!$hostman->checkPermSudo()) {
    echo HostManager::getColorCode('red') . 'Скрипт необходимо запускать от sudo' . PHP_EOL;
    die();
}

$hostman->startAction();

$hostman->clearTemp();





