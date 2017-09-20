<?php

namespace anvi\hostman;


/**
 * Класс для получения цветов для консоли
 * @package anvi\hostman
 */
class ConsoleColor
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