<?php

// TODO: реализовать ввод адреса конфигов хоста при добавлении & удалении (???)
// TODO: Реализовать удаление хоста

namespace anvi\manhost;

use function Couchbase\defaultDecoder;

class HostManager
{
    const NAME = 'hostman';
    private $action;
    private $params = [];
    private static $colors = [
        'red' => "\033[31m",
        'green' => "\033[32m",
        'blue' => "\033[34m",
        'yellow' => "\033[33m",
        'default' => "\033[37m",
        'white' => "\033[37m",
    ];


    /**
     * HostManager constructor.
     * @param $argv
     */
    public function __construct($argv)
    {
        $this->setInputParams($argv);
    }

    /**
     * Разбор и установка входящих параметров
     * @param $inputParams
     * @return bool
     */
    public function setInputParams($inputParams)
    {
        foreach ($inputParams as $ind => $par) {
            if (strpos($par, HostManager::NAME . ':') !== false) {
                $this->action = explode(':', $par)[1];
            } else {
                $this->params[] = $par;
            }
        }

        if (!isset($this->action)) {
            echo HostManager::getColorCode('red') . 'Команда не задана' . PHP_EOL;
            HostManager::viewHelp();
            die();
        }

        return true;
    }

    /**
     * Запускает функцию
     *
     */
    public function startAction()
    {
        // определение команды
        switch ($this->action) {
            case 'create' :
                if ($this->createHost()) {
                    echo HostManager::getColorCode('green') . 'Виртуальный хост успешно создан' .
                        HostManager::getColorCode('white') . PHP_EOL . PHP_EOL;
                } else {
                    echo HostManager::getColorCode('red') . 'Виртуальный хост не создан' .
                        HostManager::getColorCode('white') . PHP_EOL . PHP_EOL;
                }
                break;
            case 'delete' :
                if ($this->deleteHost()) {
                    echo HostManager::getColorCode('green') . 'Виртуальный хост успешно удален' .
                        HostManager::getColorCode('white') . PHP_EOL . PHP_EOL;
                } else {
                    echo HostManager::getColorCode('red') . 'Виртуальный хост не удален' .
                        HostManager::getColorCode('white') . PHP_EOL . PHP_EOL;
                }
                break;
            case 'help' :
                HostManager::viewHelp();
                break;
            default :
                echo HostManager::getColorCode('red') . 'Указана неверная команда' .
                    HostManager::getColorCode('white') . PHP_EOL . PHP_EOL;
                HostManager::viewHelp();
                break;
        }
    }


    /**
     * Создание хоста
     * @return true|false
     */
    public static function createHost()
    {
        echo 'CREATE';
        die();

        $requiredParams = [
            'doc_root' => '-dr',
            'url' => '-url',
        ];

        $defParams = [
            'cms' => 'default',
            'conf_path' => '/etc/apache2/sites-available',
            'hosts_path' => '/etc/hosts',
        ];

        $useParams = [
            'doc_root' => '-dr',
            'conf_path' => '-cp',
            'url' => '-url',
            'cms' => '-cms',
            'hosts_path' => '-hp',
        ];

        // работа с параметрами
        $arParams = self::prepareParams($arParams, $useParams);
        if (self::checkRequireParams($arParams, $requiredParams)) {
            $arParams = self::setDefaultParams($arParams, $useParams, $defParams);

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
                //system("sudo mv $pathTmpConf $pathConf");
                //system("sudo a2ensite {$arParams['url']}.conf");

                // редактирование файла hosts
                if (file_exists($arParams['hosts_path'])) {
                    $fileHostsContent = file($arParams['hosts_path']);
                    array_unshift($fileHostsContent, "127.0.0.1     {$arParams['url']}");

                    $fp = fopen($arParams['hosts_path'], 'w');
                    fwrite($fp, implode($fileHostsContent));
                    fclose($fp);

//                    echo '<pre>';
//                    print_r($fileHostsContent);
//                    echo '</pre>';

                } else {
                    echo self::getColorCode('yellow') . "Файл hosts не найден в дирректории {$arParams['hosts_path']}" . PHP_EOL;
                }

                //system("sudo service apache2 reload");

                return true;
            } else {
                echo self::getColorCode('yellow') . 'Конфиг хоста уже существует' . PHP_EOL;
                return false;
            }
        }

    }

    /**
     * Удаление хоста
     * @return bool
     */
    public static function deleteHost()
    {
        echo 'DELETE';
        die();


        $requiredParams = [
            'url' => '-url',
        ];

        $defParams = [
            'conf_path' => '/etc/apache2/sites-available',
            'hosts_path' => '/etc/hosts',
        ];

        $useParams = [
            'conf_path' => '-cp',
            'url' => '-url',
            'hosts_path' => '-hp',
        ];

        // работа с параметрами
        $arParams = self::prepareParams($arParams, $useParams);
        if (self::checkRequireParams($arParams, $requiredParams)) {
            $arParams = self::setDefaultParams($arParams, $useParams, $defParams);

            // функционал удаления
            if (file_exists($arParams['conf_path'])) {
                echo self::getColorCode('red') . "(!!!) " .
                    self::getColorCode('yellow') . "Вы уверены, что хотите удалить хост {$arParams['url']}? " .
                    " [Да / нет]" . PHP_EOL;
                echo self::getColorCode('default');

                // подтверждение пользователя
                if (self::checkAnswerConfirm(fgets(STDIN))) {
                    system("sudo a2dissite {$arParams['url']}.conf");
                    system("sudo rm {$arParams['conf_path']}/{$arParams['url']}.conf");
                    system('service apache2 restart');
                    return true;
                } else {
                    return false;
                }

            } else {
                echo self::getColorCode('red') . "Виртуальный хост {$arParams['url']} не существует" . PHP_EOL;
                return false;
            }
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
     * Проверка заполненности обязательных параметров
     * @param $arRequired
     * @param $arParams
     * @return bool
     */
    private static function checkRequireParams($arParams, $arRequired)
    {
        $errMessage = '';

        foreach ($arRequired as $key => $val) {
            if (!isset($arParams[$key])) {
                $errMessage .= self::getColorCode('red') . 'Не задан обязательный параметр ' . $val . PHP_EOL;
                $errMessage .= self::getColorCode('default');
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
    private static function prepareParams($arParams, $arUseParams)
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

    /**
     * Задает параметры, которые не заполнены значениями по умолчанию
     * @param $arParams
     * @param $arUseParams
     * @param $arDefParams
     * @return mixed
     */
    private static function setDefaultParams($arParams, $arUseParams, $arDefParams)
    {
        foreach ($arUseParams as $key => $usePar) {
            if (!isset($arParams[$key]) && isset($arDefParams[$key])) {
                $arParams[$key] = $arDefParams[$key];
            }
        }

        return $arParams;
    }

    /**
     * Проверяет введенный пользователем ответ (Да/нет)
     * @param $answer
     * @return bool
     */
    private static function checkAnswerConfirm($answer)
    {
        $answer = (string)trim($answer);

        switch ($answer) {
            case 'Да' :
                $result = true;
                break;
            case 'нет' :
                $result = false;
                break;
            default :
                echo self::getColorCode('yellow') . "Неверный ответ. Повторите ввод" . PHP_EOL;
                $result = self::checkAnswerConfirm(fgets(STDIN));
                break;
        }

        return $result;
    }

    /**
     * Проверка прав
     * @return bool
     */
    public function checkPermSudo()
    {
        echo 'текущие права:';
        $permission = system('whoami');
        echo PHP_EOL;

        if (strpos($permission ,'root') !== false) {
            return true;
        } else {
            return false;
        }
    }





    /**
     * Возвращает команду
     * @return bool
     */
    public function getAction()
    {
        if (isset($this->action)) {
            return $this->action;
        } else {
            return false;
        }
    }

    /**
     * Возвращает параметры
     * @return array|bool
     */
    public function getParams()
    {
        if (isset($this->params)) {
            return $this->params;
        } else {
            return false;
        }
    }



    public function clearTemp()
    {
        $arFiles = glob(__DIR__ . "/*");


        print_r($arFiles);

        if (count($arFiles) > 0) {
            foreach ($arFiles as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }
    }




    /**
     *  Получить код цвета (для консоли) по названию
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

}