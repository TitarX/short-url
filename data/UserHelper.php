<?php

namespace Application\Shorturl\Helpers;

use Application\Shorturl\User;

require_once ROOT_DIR . '/data/User.php';
require_once ROOT_DIR . '/data/DbHelper.php';
require_once ROOT_DIR . '/data/MailHelper.php';

class UserHelper
{
    /**
     * Регистрация нового пользователя
     *
     * @param User\User $user
     * @return array
     */
    public static function userRegistration(User\User $user)
    {
        $return_result = array(
            'status' => '',
            'messages' => array()
        );

        $connection = DbHelper::getConnection();
        if ($connection) {
            $user_name = DbHelper::specialCharactersTransform($user->getName());
            $user_email = DbHelper::specialCharactersTransform($user->getEmail());
            $user_password = $user->getPassword();
            $hash_user_password = hash('SHA512/256', $user_password);

            $result_array = '';
            if ($result = mysqli_query($connection, "SELECT id FROM user WHERE email LIKE '$user_email'")) {
                $result_array = mysqli_fetch_array($result);
            }

            if (!empty($result_array['id'])) {
                $return_result['status'] = 'error';
                $return_result['messages'] = array(
                    'Пользователь с данным email-адресом уже зарегистрирован'
                );
            } else {
                $current_time = time();
                $result = mysqli_query($connection, "INSERT INTO user (name, email, pass, reg_date) VALUES ('$user_name', '$user_email', '$hash_user_password', '$current_time')");

                if ($result) {
                    $return_result['status'] = 'info';
                    $return_result['messages'] = array(
                        'Пользователь успешно создан',
                        'На указанный email-адрес отправлено письмо с регистрационными данными',
                        'Можете войти, использую ваши регистрационные данные'
                    );

                    $emailMessage = 'Вы успешно зарегистрировались в сервисе сокращения ссылок';
                    $emailMessage .= PHP_EOL;
                    $emailMessage .= PHP_EOL;
                    $emailMessage .= 'Используйте для входа ваш email-адрес и следующий пароль:';
                    $emailMessage .= PHP_EOL;
                    $emailMessage .= $user_password;

                    MailHelper::sendMail($user_email, 'Регистрация в сервисе сокращения ссылок', $emailMessage);
                } else {
                    $return_result['status'] = 'error';
                    $return_result['messages'] = array(
                        'Не удалось зарегистровать пользователя'
                    );
                }
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
     * Авторизация пользователя
     *
     * @param User\User $user
     * @return array
     */
    public static function userLogin(User\User $user)
    {
        $return_result = array(
            'status' => '',
            'message' => ''
        );

        $connection = DbHelper::getConnection();
        if ($connection) {
            $user_email = DbHelper::specialCharactersTransform($user->getEmail());
            $user_password = $user->getPassword();
            $hash_user_password = hash('SHA512/256', $user_password);

            $result_array = '';
            if ($result = mysqli_query($connection, "SELECT id FROM user WHERE email LIKE '$user_email' AND pass LIKE '$hash_user_password'")) {
                $result_array = mysqli_fetch_array($result);
            }

            if (empty($result_array)) {
                $return_result['status'] = 'error';
                $return_result['messages'] = array(
                    'Пользователя с указанными данными не существует'
                );
            } else {
                $user_id = $result_array['id'];
                $ticket = hash('SHA512/256', mt_rand(999, 999999));
                mysqli_query($connection, "UPDATE user SET ticket = '$ticket' WHERE id = '$user_id'");

                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['id'] = $user_id;
                $_SESSION['ticket'] = $ticket;

                $return_result['status'] = 'info';
                $return_result['messages'] = array(
                    'Вы успешно вошли'
                );
            }

            mysqli_free_result($result);
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
     * Проверка, авторизован ли пользователь
     *
     * @param $user_id
     * @return array
     */
    public static function isUserLogged()
    {
        $return_result = false;

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id']) && isset($_SESSION['ticket'])) {
            $user_id = DbHelper::specialCharactersTransform($_SESSION['id']);
            $connection = DbHelper::getConnection();
            if ($connection) {
                if ($result = mysqli_query($connection, "SELECT ticket FROM user WHERE id = '$user_id'")) {
                    $result_array = mysqli_fetch_array($result);
                    $user_ticket = $result_array['ticket'];

                    if ($user_ticket == $_SESSION['ticket']) {
                        $return_result = true;
                    }
                }
            }

            mysqli_close($connection);
        }

        return $return_result;
    }

    /**
     * Проверка, имеется ли в системе пользователь с переданным email-адресом
     *
     * @param $user_email
     * @return bool
     */
    public static function isUserExistsByEmail($user_email)
    {
        $return_result = false;

        $user_email = DbHelper::specialCharactersTransform($user_email);

        $connection = DbHelper::getConnection();
        if ($connection) {
            if ($result = mysqli_query($connection, "SELECT id FROM user WHERE email = '$user_email'")) {
                $result_array = mysqli_fetch_array($result);
                if (!empty($result_array['id'])) {
                    $return_result = true;
                }
            }
        }

        mysqli_close($connection);

        return $return_result;
    }

    /**
     * Создание нового пароля пользователя
     *
     * @param $user_email
     */
    public static function createNewPasswordForUserEmail($user_email)
    {
        $new_password = self::passwordGenerator(3, 8);
        $new_password_hash = hash('SHA512/256', $new_password);

        $connection = DbHelper::getConnection();
        if ($connection) {
            mysqli_query($connection, "UPDATE user SET pass = '$new_password_hash' WHERE email = '$user_email'");
        }

        mysqli_close($connection);

        $emailMessage = 'Вы запросили смену пароля для вашей учётной записи в сервисе сокращения ссылок';
        $emailMessage .= PHP_EOL;
        $emailMessage .= PHP_EOL;
        $emailMessage .= 'Новый пароль:';
        $emailMessage .= PHP_EOL;
        $emailMessage .= $new_password;

        return MailHelper::sendMail($user_email, 'Новый пароль для вашей учётной записи в сервисе сокращения ссылок', $emailMessage);
    }

    /**
     * Генерация пароля
     *
     * @param $minLength
     * @param $maxLength
     * @return string
     */
    public static function passwordGenerator($minLength, $maxLength)
    {
        $chars = 'qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP';
        $chars_length = mb_strlen($chars) - 1;
        $password_length = mt_rand($minLength, $maxLength);

        $new_password = '';
        for ($i = 0; $i < $password_length; $i++) {
            $char_index = mt_rand(0, $chars_length);
            $new_password .= $chars[$char_index];
        }

        return $new_password;
    }
}
