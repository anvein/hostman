<?php

use anvi\hostman\HostManager;

// загрузка autoloader
require_once __DIR__ . '/core/Autoloader.php';
$autoloader = new anvi\Autoloader([__DIR__ . '/core/']);
$autoloader->init();


$hostman = new HostManager($argv);
$hostman->startAction();
$hostman->clearTemp();