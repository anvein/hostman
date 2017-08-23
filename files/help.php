<?php
echo PHP_EOL;
echo self::getColorCode('green') . '---------------------------------------------------------' . PHP_EOL;

echo self::getColorCode('yellow') . 'Для запуска скрипта надо запустить скрипт: php manhosts.php' . PHP_EOL;
echo self::getColorCode('white') . 'Доступные команды:' . PHP_EOL . PHP_EOL;
echo 'create    - создать виртуальный хост' . PHP_EOL;
echo 'delete    - удалить виртуальный хост' . PHP_EOL;
echo 'help      - вывести справку' . PHP_EOL . PHP_EOL;

echo self::getColorCode('yellow') . 'Подробное описание основных команд и их параметров' . PHP_EOL;
echo self::getColorCode('white') . 'create -dr:<DocumentRoot> -url:<URL> -cms:<bitrix>' . PHP_EOL;
echo '  -dr:<DocumentRoot> - путь к папке с сайтом' . PHP_EOL;
echo '  -cr:<ConfigurationRoot> - путь, где будут хранить конфиги хоста' . PHP_EOL;
echo '  -url:<URL> - адрес по которому будет доступен сайт' . PHP_EOL;
echo '  -cms:<cms> - если передать 3м параметром слово bitrix, хост будет настроен для Bitrix' . PHP_EOL . PHP_EOL;

echo self::getColorCode('yellow') . 'Пример:' . PHP_EOL;
echo self::getColorCode('default') . 'php hostman.php create -dr:/var/www/site.ru -host:site.ru -cms:bitrix' . PHP_EOL;

echo self::getColorCode('green') . '---------------------------------------------------------' . PHP_EOL . PHP_EOL;
echo self::getColorCode('default');