<?php

use anvi\hostman\HostManager;

// загрузка autoloader
require_once __DIR__ . '/core/Autoloader.php';
$autoloader = new anvi\Autoloader([__DIR__ . '/core/']);
$autoloader->init();

// подключение файлов с ошибками и сообщениями
require_once __DIR__ . '/files/errors.php';
require_once __DIR__ . '/files/messages.php';


$hostman = new HostManager($argv);
$hostman->startAction();
$hostman->clearTemp();