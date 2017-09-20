<?php

namespace anvi\hostman;

use anvi\hostman\ConsoleColor;
use anvi\hostman\Messages;


class HostManager
{
    const NAME = 'hostman';
    private $action;
    private $params = [];


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
    private function setInputParams($inputParams)
    {
        foreach ($inputParams as $ind => $par) {
            if (strpos($par, HostManager::NAME . ':') !== false) {
                $action = explode(':', $par)[1];
                $this->action = $action ?: null;
            } else {
                $parameter = explode(':', $par);

                $keyParam = isset($parameter[0]) ? $parameter[0] : null;
                $valueParam = isset($parameter[1]) ? $parameter[1] : '';

                if (isset($keyParam)) {
                    $this->params[$keyParam] = $valueParam;
                }
            }
        }

        if (!isset($this->action)) {
            echo ConsoleColor::getColorCode('red') . 'Команда не задана' . PHP_EOL;
            HostManager::viewHelp();
            die();
        }

        return true;
    }


    /**
     * Проверка параметров
     * @param $arConditions - массив условий для проверки параметра<br>
     * require - обязательность<br>
     * path_exist - есть ли указанный файл/папка<br>
     * allow_change - предлагать пользователю изменить параметр
     */
    private function checkParams($arConditions)
    {
        foreach ($arConditions as $keyParam => $conditionsParam) {
            // рекурсивно вызывает проверку параметра, если он не прошел проверку
            do {
                $resultCheckParam = $this->checkParam($keyParam, $conditionsParam);
            } while (!$resultCheckParam);
        }
    }

    /**
     * Проверка параметра
     * @param $keyParam
     * @param $conditionsParam
     * @return bool
     */
    private function checkParam($keyParam, $conditionsParam)
    {
        if (!isset($this->params[$keyParam])) {
            $this->params[$keyParam] = '';
        }

        $arCheckingResult = [];
        foreach ($conditionsParam as $condition) {
            switch ($condition) {
                case 'require':
                    $arCheckingResult[$condition] = $this->checkRequireParam($this->params[$keyParam]);

                    break;
                case 'path_exist':
                    $arCheckingResult[$condition] = $this->checkPathExists($this->params[$keyParam]);
                    break;
            }
        }

        $resultCheck = true;
        foreach ($arCheckingResult as $item) {
            if ($item !== true) {
                $resultCheck = false;
                break;
            }
        }

        if ($resultCheck) {
            return true;
        } else {
            $this->params[$keyParam] = $this->suggestValue($arCheckingResult, $keyParam, $this->params[$keyParam]);
            return false;
        }
    }

    /**
     * Проверяет задан ли параметр
     * @param $param
     * @return bool
     */
    private function checkRequireParam($param)
    {
        if (!empty($param)) {
            return true;
        } else {
            return 'Поле должно быть обязательно заполнено';
        }
    }


    /**
     *  Проверяет существует ли файл/папка
     * @param $path
     * @return bool
     */
    private function checkPathExists($path)
    {
        if (file_exists($path)) {
            return true;
        } else {
            return 'Не найден файл или папка по указанному пути';
        }
    }


    /**
     * Предложение пользователю ввести параметр
     * @param $arErrors
     * @param $keyParam
     * @param $value
     * @return mixed
     */
    private function suggestValue($arErrors, $keyParam, $value)
    {
        echo PHP_EOL . ConsoleColor::getColorCode('red'). "Не верно задано значение параметра {$keyParam}: {$value}" . PHP_EOL .
            ConsoleColor::getColorCode('default');

        foreach ($arErrors as $error) {
            echo "{$keyParam} - {$error}" . PHP_EOL;
        }

        echo ConsoleColor::getColorCode('yellow') . "Введите значение заново: " . PHP_EOL .
            ConsoleColor::getColorCode('default');

        $value = trim(fgets(STDIN));

        if ($value === 'exit') {
            echo PHP_EOL . ConsoleColor::getColorCode('red') . 'Выполнение скрипта остановлено' .
                ConsoleColor::getColorCode('default') . PHP_EOL;
            die();
        } else {
            return $value;
        }
    }


    /**
     * Запускает указанную команду
     */
    public function startAction()
    {
        switch ($this->action) {
            case 'create' :
                if (!$this->checkPermSudo()) {
                    echo ConsoleColor::getColorCode('red') . 'Скрипт необходимо запускать от sudo' . PHP_EOL;
                    return;
                }

                $result = $this->createHost();

                if ($result) {
                    echo ConsoleColor::getColorCode('green') . 'Виртуальный хост успешно создан' .
                        ConsoleColor::getColorCode('white') . PHP_EOL . PHP_EOL;
                } else {
                    echo ConsoleColor::getColorCode('red') . 'Виртуальный хост не создан' .
                        ConsoleColor::getColorCode('white') . PHP_EOL . PHP_EOL;
                }
                break;
            case 'delete' :
                if (!$this->checkPermSudo()) {
                    echo ConsoleColor::getColorCode('red') . 'Скрипт необходимо запускать от sudo' . PHP_EOL;
                    return;
                }

                $result = $this->deleteHost();

                if ($result) {
                    echo ConsoleColor::getColorCode('green') . 'Виртуальный хост успешно удален' .
                        ConsoleColor::getColorCode('white') . PHP_EOL . PHP_EOL;
                } else {
                    echo ConsoleColor::getColorCode('red') . 'Виртуальный хост не удален' .
                        ConsoleColor::getColorCode('white') . PHP_EOL . PHP_EOL;
                }
                break;
            case 'help' :
                HostManager::viewHelp();
                break;
            default :
                echo ConsoleColor::getColorCode('red') . 'Указана неверная команда' .
                    ConsoleColor::getColorCode('white') . PHP_EOL . PHP_EOL;
                HostManager::viewHelp();
                break;
        }
    }


    /**
     * Создание файла виртуального хоста
     * @return bool
     */
    private function createConfigVirtualHostFile()
    {
        // копируем файл-шаблон конфига хоста в /tmp
        switch ($this->params['-cms']) {
            case 'bitrix' :
                // можно создавать новые шаблоны виртуальных хостов
                // и сюда прописывать присатвку (cms) к ним
                break;
            default :
                $this->params['-cms'] = 'default';
                break;
        }

        $pathTemplateConf = __DIR__ . '/../files/' . $this->params['-cms'] . '.conf';
        $pathTmpConf = __DIR__ . '/../tmp/' . $this->params['-url'] . '.conf';

        copy($pathTemplateConf, $pathTmpConf);

        // заменяем #docRoot# и #host# в файле конфига (когда он в tmp)
        $fileTmpConfContent = file_get_contents($pathTmpConf);
        $fileTmpConfContent = str_replace('#host#', $this->params['-url'], $fileTmpConfContent);
        $fileTmpConfContent = str_replace('#document_root#', $this->params['-dr'], $fileTmpConfContent);
        file_put_contents($pathTmpConf, $fileTmpConfContent);

        system("sudo mv {$pathTmpConf} {$this->params['-cp']}");
        system("sudo a2ensite {$this->params['-url']}.conf");

        return true;
    }


    /**
     * Прописывание маршрутизации в файле hosts
     * @return bool
     */
    private function updateEtcHostsFile()
    {
        $fileHostsContent = file($this->params['-hp']);

        switch ($this->action) {
            case 'create':
                array_unshift($fileHostsContent, "127.0.0.1     {$this->params['-url']}" . PHP_EOL);
                break;
            case 'delete':
                foreach ($fileHostsContent as $index => $str) {
                    if (strpos($str, ' ' . $this->params['-url']) !== false ||
                        strpos($str, '	' . $this->params['-url']) !== false) {
                        unset($fileHostsContent[$index]);
                    }
                }
                break;
            default:
                return false;
                break;
        }

        $fp = fopen($this->params['-hp'], 'w');
        fwrite($fp, implode($fileHostsContent));
        fclose($fp);

        return true;
    }


    /**
     * Создание хоста
     * @return true|false
     */
    private function createHost()
    {
        $arConditions = [
            '-dr'   => ['require', 'path_exist', 'allow_change'],
            '-url'  => ['require'],
            '-cp'   => ['require', 'path_exist', 'allow_change'],
            '-hp'   => ['require', 'path_exist', 'allow_change'],
            '-cms'  => ['require'],
        ];

        $arDefValParams = [
            '-cp'   => '/etc/apache2/sites-available',
            '-hp'   => '/etc/hosts',
            '-cms'  => 'default',
        ];

        $this->setDefaultParams($arDefValParams);
        $this->checkParams($arConditions);


        if (!file_exists($this->params['-cp'] . '/' . $this->params['-url'].'.conf')) {
            $this->createConfigVirtualHostFile();
            $this->updateEtcHostsFile();

            system("sudo service apache2 reload");
            return true;
        } else {
            echo ConsoleColor::getColorCode('yellow') . 'Конфиг хоста уже существует' . PHP_EOL;
            return false;
        }
    }


    /**
     * Удаление хоста
     * @return bool
     */
    private function deleteHost()
    {
        $arConditions = [
            '-url'  => ['require'],
            '-hp'   => ['require', 'path_exist', 'allow_change'],
            '-cp'   => ['require', 'path_exist', 'allow_change']
        ];

        $arDefValParams = [
            '-hp'   => '/etc/hosts',
            '-cp'   => '/etc/apache2/sites-available'
        ];

        $this->setDefaultParams($arDefValParams);
        $this->checkParams($arConditions);


        // функционал удаления
        if (file_exists($this->params['-cp'] . '/' . $this->params['-url'] . '.conf')) {
            $question =  ConsoleColor::getColorCode('red') . "(!!!) " .
                ConsoleColor::getColorCode('yellow') . "Вы уверены, что хотите удалить хост {$this->params['-url']}? " .
                " [Да / нет]" . PHP_EOL . ConsoleColor::getColorCode('default');

            // подтверждение пользователя
            if (!$this->userConfirm($question)) {
                return false;
            }

            $this->updateEtcHostsFile();
            system("sudo a2dissite {$this->params['-url']}.conf");
            system("sudo rm {$this->params['-cp']}/{$this->params['-url']}.conf");
            system('service apache2 restart');

            return true;
        } else {
            echo ConsoleColor::getColorCode('red') . "Виртуальный хост {$this->params['-url']} не существует" . PHP_EOL;
            return false;
        }

    }


    /**
     * Вывод справки
     * @return true
     */
    private function viewHelp()
    {
        $pathToHelp = __DIR__ . '/../files/help.php';
        if (file_exists($pathToHelp)) {
            require_once $pathToHelp;
        }

        return true;
    }


    /**
     * Задает незаполненные параметры, для которых есть значение по умолчанию
     * @param $arDefValParams
     * @return bool
     */
    private function setDefaultParams($arDefValParams)
    {
        foreach ($arDefValParams as $key => $defParam) {
            if (!isset($this->params[$key])) {
                $this->params[$key] = strtolower($defParam);
            }
        }

        return true;
    }


    /**
     * Проверяет введенный пользователем ответ (Да/нет)
     * @param $question
     * @return bool
     */
    private function userConfirm($question)
    {
        echo $question;
        $answer = (string)trim(fgets(STDIN));

        switch ($answer) {
            case 'Да' :
                $result = true;
                break;
            case 'нет' :
                $result = false;
                break;
            default :
                $question = ConsoleColor::getColorCode('yellow') . "Неверный ответ. Повторите ввод" . PHP_EOL
                . ConsoleColor::getColorCode('default');
                $result = $this->userConfirm($question);
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
     * Очистить папку tmp
     * @return bool
     */
    public function clearTemp()
    {
        $arFiles = glob(__DIR__ . "/../tmp/*");
        if (count($arFiles) > 0) {
            foreach ($arFiles as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }

        return true;
    }

}