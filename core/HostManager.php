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
        echo '  -cr:<ConfigurationRoot> - путь, где будут хранить конфиги хоста' . PHP_EOL;
        echo '  -url:<URL> - адрес по которому будет доступен сайт' . PHP_EOL;
        echo '  -cms:<cms> - если передать 3м параметром слово bitrix, хост будет настроен для Bitrix' . PHP_EOL . PHP_EOL;

        echo self::getColorCode('yellow') . 'Пример:' . PHP_EOL;
        echo self::getColorCode('default') . 'php hostman.php create /var/www/site.ru site.ru bitrix' . PHP_EOL;

        echo self::getColorCode('green') . '---------------------------------------------------------' . PHP_EOL . PHP_EOL;
        echo self::getColorCode('default');

        return true;
    }


    /**
     * Создать Хост
     * @param $arParams
     * @return false
     */
    public static function createHost($arParams)
    {
        $requiredParams = [
            'doc_root' => '-dr',
            'conf_system' => '-cr',
            'url' => '-url',
        ];

        $useParams = [
            'doc_root' => '-dr',
            'conf_root' => '-cr',
            'url' => '-url',
            'cms' => '-cms',
        ];

        // парсим параметры
        $arParams = HostManager::prepareParams($useParams, $arParams);

        // проверяем заполненость обязательных параметров
        if (!HostManager::checkRequireParams($requiredParams, $arParams)) {
            return false;
        }


        // если cms не установлена, то задаем default
        if (!isset($arParams['cms'])) {
            $arParams['cms'] = 'default';
        }

        // копируем файл конфига хоста в /tmp/
        $pathTemplateConf = __DIR__ . '/../files/' . $arParams['cms'] . '.conf';
        $pathTmpConf = __DIR__ . '/../tmp/' . $arParams['url'] . '.conf';
        copy($pathTemplateConf, $pathTmpConf);

        // заменяем #docRoot# и #host# в файле конфига
        $fileTmpConfContent = file_get_contents($pathTmpConf);
        $fileTmpConfContent = str_replace('#host#', $arParams['url'], $fileTmpConfContent);
        $fileTmpConfContent = str_replace('#document_root#', $arParams['doc_root'], $fileTmpConfContent);

        // перемещаем файл в папку с настройками apache




        echo '<pre>';
        print_r($fileTmpConfContent);
        echo '</pre>';


        //return true;
    }


    public function deleteHost()
    {
        //...
    }


    /**
     * Проверка заполненности обязательных параметров
     * @param $arRequired
     * @param $arParams
     * @return bool
     */
    private static function checkRequireParams($arRequired, $arParams)
    {
        foreach ($arRequired as $key => $val) {
            if (!isset($arParams[$key])) {
                echo HostManager::getColorCode('red') . 'Не задан обязательный параметр ' . $val . PHP_EOL;
                echo HostManager::getColorCode('default');
                return false;
            }
        }

        return true;
    }


    /**
     * Разбирает параметры во входящей строке
     * @param $arUseParams
     * @param $arParams
     * @return array
     */
    private static function prepareParams($arUseParams, $arParams)
    {
        $result = [];

        foreach ($arUseParams as $keyUse => $valUse) {
            foreach ($arParams as $keyPar => $valPar) {
                if (strpos($valPar, $valUse) !== false) {
                    $result[$keyUse] = str_replace($valUse . ':', '', $valPar);
                    break;
                }
            }
        }

        return $result;
    }
}