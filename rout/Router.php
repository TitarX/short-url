<?php

namespace Application\Shorturl\Rout;

require_once ROOT_DIR . '/data/UserHelper.php';

use Application\Shorturl\Helpers\UserHelper;

class Router
{
    // Маршруты страниц
    private static $routes = array(
        '/' => 'front',
        '/login.htm' => 'user/login',
        '/logout.htm' => 'user/logout',
        '/registration.htm' => 'user/registration',
        '/recovery.htm' => 'user/recovery',
        '/list_url.htm' => 'user/account/list_url',
        '/add_url.htm' => 'user/account/add_url',
        '/edit_url.htm' => 'user/account/edit_url',
        '/remove_url.htm' => 'user/account/remove_url'
    );

    // Маршрут страницы 404 (Не найдено)
    private static $rout_404 = 'service/404';

    // Маршрут страницы 403 (Доступ запрещён)
    private static $rout_403 = 'service/403';

    // Маршрут страницы пользователя с короткими адресами
    private static $rout_shorturl = 'shorturl';

    /**
     * Определение и направление по маршруту
     *
     * @param $request_uri
     */
    public static function go($request_uri)
    {
        // Если запрошена страница
        if (preg_match('/\.htm$/uis', $request_uri) === 1 || $request_uri == '/') {
            // Если маршрут существует, иначе страница не найдена
            if (array_key_exists($request_uri, self::$routes)) {
                // Если запрошена страница аккаунта
                if (strstr(self::$routes[$request_uri], 'account') !== false) {
                    // Если пользователь авторизован, иначе доступ запрещён
                    if (UserHelper::isUserLogged()) {
                        require_once PAGES_DIR . '/' . self::$routes[$request_uri] . '/index.php';
                    } else {
                        require_once PAGES_DIR . '/' . self::$rout_403 . '/index.php';
                    }
                } else {
                    require_once PAGES_DIR . '/' . self::$routes[$request_uri] . '/index.php';
                }
            } else {
                require_once PAGES_DIR . '/' . self::$rout_404 . '/index.php';
            }
        } else {
            // Если запрошен короткий адрес, иначе страница не найдена
            if (preg_match('/^\/[0-9a-zA-Z]{3,16}$/uis', $request_uri) === 1) {
                require_once PAGES_DIR . '/' . self::$rout_shorturl . '/index.php';
            } else {
                require_once PAGES_DIR . '/' . self::$rout_404 . '/index.php';
            }
        }
    }
}
