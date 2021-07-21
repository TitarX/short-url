<?php

namespace Application\Shorturl\Helpers;

// Подключение файла с параметрами подключения к базе данных
require_once ROOT_DIR . '/conf/config.php';

class DbHelper
{
    /**
     * Ссылка на соединение
     *
     * @return \mysqli
     */
    public static function getConnection()
    {
        $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!empty(DB_CHARSET)) {
            mysqli_set_charset($connection, 'utf8mb4');
        }
        return $connection;
    }

    /**
     * Обработка символов строк
     *
     * @param $string
     * @return string
     */
    public static function specialCharactersTransform($string)
    {
        $string = stripslashes($string);
        $string = htmlspecialchars($string);
        $string = trim($string);

        return $string;
    }
}
