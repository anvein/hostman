<?php

namespace anvi;

class Autoloader
{
    private $arPaths = [];


    /**
     * Autoloader constructor.
     */
    public function __construct($paths)
    {
        if (empty($paths)) {
            throw new \Exception('Пути для поиска классов не заданы');
        } else {
            $this->arPaths = $paths;
        }
    }


    /**
     * Запускает автозагрузку перечисленными способами
     */
    public function init()
    {
        spl_autoload_register(function ($className) {
            $pos = strrpos($className, '\\');
            if ($pos !== false) {
                $className = substr($className, $pos + 1);
            }

            foreach ($this->arPaths as $path) {
                $pathToClassFile = $path . $className . '.php';
                if (file_exists($pathToClassFile)) {
                    require_once($pathToClassFile);
                } else {
                    throw new \Exception("Класс {$className} не найден");
                }
            }
        });
    }

}