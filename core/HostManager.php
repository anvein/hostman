<?php

// TODO: реализовать ввод адреса конфигов хоста при добавлении & удалении (???)
// TODO: Реализовать удаление хоста

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
        $pathToHelp = __DIR__ . '/../files/help.php';
        if (file_exists($pathToHelp)) {
            require_once $pathToHelp;
        }

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
            //'conf_system' => '-cr',
            'url' => '-url',
        ];

        $useParams = [
            'doc_root' => '-dr',
            //'conf_root' => '-cr',
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
        file_put_contents($pathTmpConf, $fileTmpConfContent);

        // перемещаем файл в папку с настройками apache
        $pathConf = '/etc/apache2/sites-available';

        if (!file_exists($pathConf . '/' . $arParams['url'].'.conf')) {
            system("sudo mv $pathTmpConf $pathConf");
            system("sudo a2ensite {$arParams['url']}.conf");
            system("sudo service apache2 reload");

            return true;
        } else {
            echo HostManager::getColorCode('yellow') . 'Конфиг хоста уже существует' . PHP_EOL;
            return false;
        }
    }


    public static function deleteHost()
    {

    }


    /**
     * Проверка заполненности обязательных параметров
     * @param $arRequired
     * @param $arParams
     * @return bool
     */
    private static function checkRequireParams($arRequired, $arParams)
    {
        $errMessage = '';

        foreach ($arRequired as $key => $val) {
            if (!isset($arParams[$key])) {
                $errMessage .= HostManager::getColorCode('red') . 'Не задан обязательный параметр ' . $val . PHP_EOL;
                $errMessage .= HostManager::getColorCode('default');
            }
        }

        if (strlen($errMessage) > 0) {
            echo $errMessage;
            return false;
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
                if (strpos($valPar, $valUse . ':') !== false) {
                    $result[$keyUse] = str_replace($valUse . ':', '', $valPar);
                    break;
                }
            }
        }

        return $result;
    }
}