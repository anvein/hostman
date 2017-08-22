<?php

namespace anvi\manhost;

class HostManager
{
    private static $colors = [
        'red' => "\033[31m",
        'green' => "\033[32m",
        'blue' => "\033[34m",
        'yellow' => "\033[33m",
        'default' => "\033[37m",
        'white' => "\033[37m",
    ];


    /**
     *  Получить код цвета по названию (для консоли)
     * @param $color
     * @return string|null
     */
    public static function getColorCode($color)
    {
        if (isset(self::$colors[$color])) {
            return self::$colors[$color];
        } else {
            return null;
        }
    }


    /**
     * Вывод справки
     * @return true
     */
    public static function viewHelp()
    {
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
        echo '  -url:<URL> - адрес по которому бкдет доступен сайт' . PHP_EOL;
        echo '  -cms:<cms> - если передать 3м параметром слово bitrix, хост будет настроен для Bitrix' . PHP_EOL . PHP_EOL;

        echo self::getColorCode('yellow') . 'Пример:' . PHP_EOL;
        echo self::getColorCode('default') . 'php manhost.php create /var/www/site.ru site.ru bitrix' . PHP_EOL;

        echo self::getColorCode('green') . '---------------------------------------------------------' . PHP_EOL . PHP_EOL;
        echo self::getColorCode('default');

        return true;
    }


    /**
     * Создать Хост
     * @param $arParams
     */
    public static function createHost($arParams)
    {
        // обрабатываем параметры
        //if ()
    }


    public function deleteHost()
    {
        //...
    }


}