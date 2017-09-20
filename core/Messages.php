<?php

namespace anvi\hostman;

use anvi\hostman\ConsoleColor;


class Messages
{
    private static $messages = [];

    private static $errors = [];


    /**
     * Возвращает ошибку по коду
     * @param $code
     * @return mixed
     * @throws \Exception
     */
    public static function getError($code)
    {
        if (!empty($code)) {
            if (isset(self::$errors[$code])) {
                return self::$errors[$code];
            } else {
                throw new \Exception("Ошибка с кодом {$code} не найдена");
            }
        } else {
            throw new \Exception("Должен быть указан код ошибки");
        }
    }

    /**
     * Возвращает сообщение по коду
     * @param $code
     * @return mixed
     * @throws \Exception
     */
    public static function getMessage($code)
    {
        if (!empty($code)) {
            if (isset(self::$messages[$code])) {
                return self::$messages[$code];
            } else {
                throw new \Exception("Сообщение с кодом {$code} не найдено");
            }

        } else {
            throw new \Exception("Должен быть указан код сообщения");
        }
    }


    /**
     * Задает массив сообщений
     * @param $arMessages
     * @return bool
     */
    public static function setMessages($arMessages)
    {
        self::$messages = $arMessages;
        return true;
    }


    /**
     * Задает массив ошибок
     * @param $arErrors
     * @return bool
     */
    public static function setErrors($arErrors)
    {
        self::$errors = $arErrors;
        return true;
    }

}