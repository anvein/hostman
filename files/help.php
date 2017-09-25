<?php

use \anvi\hostman\Viewer;


echo PHP_EOL;
echo Viewer::getMessString('=================================================================', 'green', 1);
echo Viewer::getMessString('Для запуска скрипта надо запустить скрипт: php cli.php', 'yellow');

// СПИСОК КОМАНД
echo Viewer::getMessString('create    - создать виртуальный хост');
echo Viewer::getMessString('delete    - удалить виртуальный хост');
echo Viewer::getMessString('help      - вывести справку', 1);


// CREATE
echo Viewer::getMessString('Подробное описание команд и их параметров', 'yellow');
echo Viewer::getMessString('CREATE', 'green');

echo Viewer::getMessString('  -dr:<DocumentRoot> - путь к папке с сайтом [обязательный]');
echo Viewer::getMessString('  -url:<URL> - адрес по которому будет доступен сайт [обязательный]');
echo Viewer::getMessString('  -cp:<ConfigurationRoot> - путь, где будут хранить конфиги хоста');
echo Viewer::getMessString('  -hp:<HostPath> - путь, где лежит файл hosts');
echo Viewer::getMessString('  -cms:<cms> - если передать 3м параметром название cms/framework, то хост будет настроен под указанную cms. Доступны варианты:');
echo Viewer::getMessString('       bitrix', 1);

echo Viewer::getMessString('особенности:', 'yellow');
echo Viewer::getMessString('- если не указать параментр -cms, то хост будет настроен по умолчанию');
echo Viewer::getMessString('- если не указать -hp, то будут использоваться настройки для Ubuntu (/etc/hosts)');
echo Viewer::getMessString('- если не указать -cp, то будут использоваться настройи для Ubuntu и apache2 (/etc/apache2/sites-available)', 1);

echo Viewer::getMessString('пример:', 'yellow');
echo Viewer::getMessString('php cli.php hostman:create -dr:/var/www/site.ru -url:site.ru -cms:bitrix', 1);


// DELETE
echo Viewer::getMessString('DELETE', 'green');

echo Viewer::getMessString('  -url:<URL> - адрес по которому будет доступен сайт [обязательный]');
echo Viewer::getMessString('  -cp:<ConfigurationRoot> - путь, где будут хранить конфиги хоста');
echo Viewer::getMessString('  -hp:<HostPath> - путь, где лежит файл hosts', 1);

echo Viewer::getMessString('особенности:', 'yellow');
echo Viewer::getMessString('- если не указать -hp, то будут использоваться настройки для Ubuntu (/etc/hosts)');
echo Viewer::getMessString('- если не указать -cp, то будут использоваться настройи для Ubuntu и apache2 (/etc/apache2/sites-available)');

echo Viewer::getMessString('пример');
echo Viewer::getMessString('php cli.php hostman:delete -url:site.ru', 1);


// HELP
echo Viewer::getMessString('HELP', 'green');
echo Viewer::getMessString('пример:', 'yellow');
echo Viewer::getMessString('php cli.php hostman:help', 1);

echo Viewer::getMessString('=================================================================', 'green', 1);