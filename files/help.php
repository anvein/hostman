<?php
use anvi\hostman\ConsoleColor;

echo PHP_EOL;
echo ConsoleColor::getColorCode('green') . '=================================================================' . PHP_EOL;

// СПИСОК КОМАНД
echo ConsoleColor::getColorCode('yellow') . 'Для запуска скрипта надо запустить скрипт: php cli.php' . PHP_EOL;
echo ConsoleColor::getColorCode('yellow') . 'Доступные команды:' . PHP_EOL . ConsoleColor::getColorCode('default');
echo 'create    - создать виртуальный хост' . PHP_EOL;
echo 'delete    - удалить виртуальный хост' . PHP_EOL;
echo 'help      - вывести справку' . PHP_EOL . PHP_EOL;


// CREATE
echo ConsoleColor::getColorCode('yellow') . 'Подробное описание команд и их параметров' . PHP_EOL;
echo ConsoleColor::getColorCode('green') . 'CREATE' . PHP_EOL . ConsoleColor::getColorCode('default');
echo '  -dr:<DocumentRoot> - путь к папке с сайтом [обязательный]' . PHP_EOL;
echo '  -url:<URL> - адрес по которому будет доступен сайт [обязательный]' . PHP_EOL;

echo '  -cp:<ConfigurationRoot> - путь, где будут хранить конфиги хоста' . PHP_EOL;
echo '  -hp:<HostPath> - путь, где лежит файл hosts' . PHP_EOL;
echo '  -cms:<cms> - если передать 3м параметром название cms/framework, то хост будет настроен под указанную cms. Доступны варианты:' . PHP_EOL;
echo '       bitrix' . PHP_EOL . PHP_EOL;
echo ConsoleColor::getColorCode('yellow') . 'особенности:' . PHP_EOL . ConsoleColor::getColorCode('default');
echo '- если не указать параментр -cms, то хост будет настроен по умолчанию' . PHP_EOL;
echo '- если не указать -hp, то будут использоваться настройки для Ubuntu (/etc/hosts)' . PHP_EOL;
echo '- если не указать -cp, то будут использоваться настройи для Ubuntu и apache2 (/etc/apache2/sites-available)' . PHP_EOL . PHP_EOL;

echo ConsoleColor::getColorCode('yellow') . 'пример:' . PHP_EOL;
echo ConsoleColor::getColorCode('default') . 'php cli.php hostman:create -dr:/var/www/site.ru -url:site.ru -cms:bitrix' . PHP_EOL . PHP_EOL;


// DELETE
echo ConsoleColor::getColorCode('green') . 'DELETE' . PHP_EOL . ConsoleColor::getColorCode('default');
echo '  -url:<URL> - адрес по которому будет доступен сайт [обязательный]' . PHP_EOL;
echo '  -cp:<ConfigurationRoot> - путь, где будут хранить конфиги хоста' . PHP_EOL;
echo '  -hp:<HostPath> - путь, где лежит файл hosts' . PHP_EOL . PHP_EOL;

echo ConsoleColor::getColorCode('yellow') . 'особенности:' . PHP_EOL . ConsoleColor::getColorCode('default');
echo '- если не указать -hp, то будут использоваться настройки для Ubuntu (/etc/hosts)' . PHP_EOL;
echo '- если не указать -cp, то будут использоваться настройи для Ubuntu и apache2 (/etc/apache2/sites-available)' . PHP_EOL . PHP_EOL;

echo ConsoleColor::getColorCode('yellow') . 'пример:' . PHP_EOL;
echo ConsoleColor::getColorCode('default') . 'php cli.php hostman:delete -url:site.ru' . PHP_EOL . PHP_EOL;

// HELP
echo ConsoleColor::getColorCode('green') . 'HELP' . PHP_EOL . ConsoleColor::getColorCode('default');

echo ConsoleColor::getColorCode('yellow') . 'пример:' . PHP_EOL;
echo ConsoleColor::getColorCode('default') . 'php cli.php hostman:help' . PHP_EOL . PHP_EOL;


echo ConsoleColor::getColorCode('green') . '=================================================================' . PHP_EOL . PHP_EOL;
echo ConsoleColor::getColorCode('default');