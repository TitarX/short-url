<?php

namespace Application\Shorturl\Helpers;

require_once ROOT_DIR . '/data/DbHelper.php';
require_once ROOT_DIR . '/data/UserHelper.php';
require_once ROOT_DIR . '/data/MiscHelper.php';

class ShorturlHelper
{
    /**
     * Получение данный ссылки по коду
     *
     * @param $code
     * @return array|string
     */
    public static function getUrlByCode($code)
    {
        $return_result = array(
            'status' => '',
            'messages' => array()
        );

        $return_url = '';

        $code = DbHelper::specialCharactersTransform($code);

        $connection = DbHelper::getConnection();
        if ($connection) {
            if ($result = mysqli_query($connection, "SELECT id, url, status, disabled_date FROM shorturl WHERE code LIKE '$code'")) {
                $result_array = mysqli_fetch_array($result);
                if ($result_array['status']) {
                    if (empty($result_array['disabled_date']) || $result_array['disabled_date'] > time()) {
                        $return_url = $result_array['url'];
                    } else {
                        $return_result = array(
                            'status' => 'warning',
                            'messages' => array(
                                'Ссылка не действительна'
                            )
                        );
                    }
                } else {
                    $return_result = array(
                        'status' => 'warning',
                        'messages' => array(
                            'Ссылка не действительна'
                        )
                    );
                }
            } else {
                $return_result = array(
                    'status' => 'error',
                    'messages' => array(
                        'Ссылка не найдена'
                    )
                );
            }
        } else {
            $return_result['status'] = 'error';
            $return_result['messages'] = array(
                'Не удалось установить соединение с базой данных'
            );
        }

        mysqli_close($connection);

        if (empty($return_url)) {
            return $return_result;
        } else {
            return $return_url;
        }
    }

    /**
     * Получение данный ссылки по id
     *
     * @param $link_id
     * @return array|null
     */
    public static function getShorturlDataById($link_id)
    {
        $return_result = array(
            'status' => '',
            'messages' => array()
        );

        $result_array = array();

        $link_id = DbHelper::specialCharactersTransform($link_id);

        $connection = DbHelper::getConnection();
        if ($connection) {
            if ($result = mysqli_query($connection, "SELECT url, code, disabled_date, status FROM shorturl WHERE id = '$link_id'")) {
                $result_array = mysqli_fetch_array($result);
                if ($result_array['status'] != '1') {
                    $result_array = array();

                    $return_result = array(
                        'status' => 'warning',
                        'messages' => array(
                            'Ссылка не действительна'
                        )
                    );
                }
            } else {
                $return_result = array(
                    'status' => 'error',
                    'messages' => array(
                        'Ссылка не найдена'
                    )
                );
            }
        } else {
            $return_result['status'] = 'error';
            $return_result['messages'] = array(
                'Не удалось установить соединение с базой данных'
            );
        }

        mysqli_close($connection);

        if (empty($result_array)) {
            return $return_result;
        } else {
            return $result_array;
        }
    }

    /**
     * Изменение данных существующей ссылки
     *
     * @param $shorturl_id
     * @param $code
     * @param $disabled_date
     * @return array
     */
    public static function changeShorturl($shorturl_id, $code, $disabled_date)
    {
        $return_result = array(
            'status' => '',
            'messages' => array()
        );

        $shorturl_id = DbHelper::specialCharactersTransform($shorturl_id);
        $code = DbHelper::specialCharactersTransform($code);
        $disabled_date = DbHelper::specialCharactersTransform($disabled_date);

        if (iconv_strlen($code) < 3 || iconv_strlen($code) > 16) {
            $code = '';

            $return_result = array(
                'status' => 'warning',
                'messages' => array(
                    'Код ссылки должен быть от 3 до 16 символов'
                )
            );
        }

        $is_code_unique = self::checkUniquenessCode($code, $shorturl_id);
        if (!$is_code_unique) {
            $code = '';

            $return_result = array(
                'status' => 'warning',
                'messages' => array(
                    'Код ссылки должен быть уникальным в системе'
                )
            );
        }

        if ($disabled_date < 0) {
            $disabled_date = 0;
        }

        if (!empty($code)) {
            $connection = DbHelper::getConnection();
            if ($connection) {
                if (mysqli_query($connection, "UPDATE shorturl SET code = '$code', disabled_date = '$disabled_date' WHERE id = '$shorturl_id'")) {
                    $return_result['status'] = 'info';
                    $return_result['messages'] = array(
                        'Ссылка успешно изменена'
                    );
                } else {
                    $return_result['status'] = 'error';
                    $return_result['messages'] = array(
                        'Не удалось изменить ссылку'
                    );
                }
            } else {
                $return_result['status'] = 'error';
                $return_result['messages'] = array(
                    'Не удалось установить соединение с базой данных'
                );
            }

            mysqli_close($connection);
        }

        return $return_result;
    }

    /**
     * Получение списка ссылок пользователя
     *
     * @param $user_id
     * @return array|null
     */
    public static function getUrlsByUser($user_id)
    {
        $return_result = array(
            'status' => '',
            'messages' => array()
        );

        $user_id = DbHelper::specialCharactersTransform($user_id);

        $connection = DbHelper::getConnection();
        if ($connection) {
            if ($result = mysqli_query($connection, "SELECT id, url, code, disabled_date FROM shorturl WHERE user_id = '$user_id' AND status = '1'")) {
                $result_array = mysqli_fetch_all($result);
            } else {
                $return_result['status'] = 'warning';
                $return_result['messages'] = array(
                    'Ссылок не найдено'
                );
            }
        } else {
            $return_result['status'] = 'error';
            $return_result['messages'] = array(
                'Не удалось установить соединение с базой данных'
            );
        }

        mysqli_close($connection);

        if (empty($return_result['status'])) {
            return $result_array;
        } else {
            return $return_result;
        }
    }

    /**
     * Добавление новой ссылки
     *
     * @param $user_id
     * @param $new_url
     * @return array
     */
    public static function addNewUrl($user_id, $new_url)
    {
        $return_result = array(
            'status' => '',
            'messages' => array()
        );

        $user_id = DbHelper::specialCharactersTransform($user_id);
        $new_url = DbHelper::specialCharactersTransform($new_url);

        // Проверка ссылки на валидность
        if (get_headers($new_url) === false) {
            $return_result['status'] = 'error';
            $return_result['messages'] = array(
                'Ссылка не валидна'
            );
        } else {
            $connection = DbHelper::getConnection();
            if ($connection) {
                $new_code = self::uniqueCodeGenerator();
                $disabled_date = '0';
                $status = 1;

                $result = mysqli_query(
                    $connection,
                    "INSERT INTO shorturl (url, code, disabled_date, status, user_id) VALUES ('$new_url', '$new_code', '$disabled_date', '$status', '$user_id')"
                );

                if ($result) {
                    $shorturl_url = MiscHelper::getSiteUrl();
                    $shorturl_url .= $new_code;

                    $return_result['status'] = 'info';
                    $return_result['messages'] = array(
                        'Ссылка успешно добавлена',
                        "Сокращённый URL: \"$shorturl_url\""
                    );
                } else {
                    $return_result['status'] = 'error';
                    $return_result['messages'] = array(
                        'Не удалось добавить ссылку'
                    );
                }
            } else {
                $return_result['status'] = 'error';
                $return_result['messages'] = array(
                    'Не удалось установить соединение с базой данных'
                );
            }

            mysqli_close($connection);
        }

        return $return_result;
    }

    /**
     * Удаление ссылки
     *
     * @param $shorturl_id
     * @return array
     */
    public static function removeShorturl($shorturl_id)
    {
        $return_result = array(
            'status' => '',
            'messages' => array()
        );

        $shorturl_id = DbHelper::specialCharactersTransform($shorturl_id);

        $connection = DbHelper::getConnection();
        if ($connection) {
            $result = mysqli_query($connection, "UPDATE shorturl SET status = '0' WHERE id = '$shorturl_id'");

            if ($result) {
                $return_result['status'] = 'info';
                $return_result['messages'] = array(
                    'Ссылка успешно удалена'
                );
            } else {
                $return_result['status'] = 'error';
                $return_result['messages'] = array(
                    'Не удалось удалить ссылку'
                );
            }
        } else {
            $return_result['status'] = 'error';
            $return_result['messages'] = array(
                'Не удалось установить соединение с базой данных'
            );
        }

        mysqli_close($connection);

        return $return_result;
    }

    /**
     * Генерация кода для создания нового сокращённого URL
     *
     * @return string
     */
    public static function uniqueCodeGenerator()
    {
        $new_code = '';
        $count = 0;

        do {
            $new_code = UserHelper::passwordGenerator(8, 8);
            $is_code_unique = self::checkUniquenessCode($new_code);

            if (!$is_code_unique) {
                $new_code = '';
            }

            $count++;
        } while (empty($new_code) || $count < 100);

        return $new_code;
    }

    /**
     * Проверка нового кода для ссылки на уникальность
     *
     * @param $code
     * @return bool|\mysqli_result
     */
    public static function checkUniquenessCode($code, $shorturl_id = 0)
    {
        $connection = DbHelper::getConnection();

        $result = true;
        if ($result = mysqli_query($connection, "SELECT id FROM shorturl WHERE code LIKE '$code'")) {
            $result_array = mysqli_fetch_array($result);
            if (!empty($result_array['id']) && $result_array['id'] != $shorturl_id) {
                $result = false;
            }
        }

        mysqli_close($connection);

        return $result;
    }
}
