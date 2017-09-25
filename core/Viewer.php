<?php

namespace anvi\hostman;

/**
 * Класс содержащий функции для вывода в консоль сообщений
 * @package anvi\hostman
 */
class Viewer
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
     * Возвращает строку для вывода окрашенный в нужный цвет и делает перевод строки
     * @return string
     * @internal param string $message
     * @internal param string $color
     * @internal param int $offset
     */
    public static function getMessString()
    {
        $args = func_get_args();

        switch (func_num_args()) {
            case 1:
                if (!is_string($args[0])) {
                    throw new \InvalidArgumentException('1й аргумент message должен быть строкой');
                }

                $message = $args[0];
                break;
            case 2:
                if (!is_string($args[0])) {
                    throw new \InvalidArgumentException('1й аргумент message должен быть строкой');
                }
                $message = $args[0];

                if (is_string($args[1]) || is_int($args[1])) {
                    if (is_string($args[1])) {
                        $color = $args[1];
                    } elseif (is_int($args[1])) {
                        $offset = $args[1];
                    }
                } else {
                    throw new \InvalidArgumentException('2й аргумент color/offset должен быть строкой/числом');
                }
                break;
            case 3:
                if (!is_string($args[0])) {
                    throw new \InvalidArgumentException('1й аргумент message должен быть строкой');
                }
                $message = $args[0];

                if (!is_string($args[1])) {
                    throw new \InvalidArgumentException('2й аргумент color должен быть строкой');
                }
                $color = $args[1];

                if (!is_int($args[2])) {
                    throw new \InvalidArgumentException('3й аргумент offset должен быть числом');
                }
                $offset = $args[2];
                break;
            default:
                throw new \ArgumentCountError('Передано неверное количество параметров. Необходимо 1, 2 или 3');
                break;
        }

        if (!isset($color)) {
            $color = 'default';
        }

        if (!isset($offset)) {
            $offset = 0;
        }

        $message = self::getColorCode($color) . $message . self::getColorCode('default');
        for ($i = 0; $i < $offset + 1; $i++) {
            $message .= PHP_EOL;
        }

        return $message;
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