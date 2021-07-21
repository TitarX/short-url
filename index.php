<?php

// Вывод сообщений об ошибках
error_reporting(-1);
$conf['error_level'] = 2;
ini_set('display_errors', true);
ini_set('display_startup_errors', true);

// Определение констант
define('ROOT_DIR', __DIR__);
define('CONF_DIR', ROOT_DIR . '/conf');
define('PAGES_DIR', ROOT_DIR . '/pages');
define('TEMPLATES_DIR', ROOT_DIR . '/templates');

// Подключение файла маршрутизации
require_once 'rout/Router.php';

use Application\Shorturl\Rout;

// Определение и направление по маршруту
Rout\Router::go($_SERVER['REQUEST_URI']);
