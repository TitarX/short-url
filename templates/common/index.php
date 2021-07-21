<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>

    <meta name="keywords" content=""/>
    <meta name="description" content=""/>

    <link rel="shortcut icon" type="image/vnd.microsoft.icon" href="/assets/images/favicon/favicon.ico"/>
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicon/apple-touch-icon.png"/>
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon/favicon-32x32.png"/>
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon/favicon-16x16.png"/>
    <link rel="manifest" href="/assets/images/favicon/site.webmanifest"/>
    <link rel="mask-icon" href="/assets/images/favicon/safari-pinned-tab.svg" color="#5bbad5"/>
    <meta name="msapplication-TileColor" content="#da532c"/>
    <meta name="theme-color" content="#ffffff"/>

    <link rel="stylesheet" href="/assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="/assets/css/prefixes/common.css"/>
    <link rel="stylesheet" href="/assets/css/prefixes/footer-bottom.css"/>
    <title>Сервис по сокращению ссылок</title>

    <?php
    // Подключение файла с мета-тегами заголовка
    require_once "$page_content_dir_path/head.php";
    ?>
</head>
<body>
<nav id="main-navigation" class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/"><img src="/assets/images/logo/shorturl-logo-60.png" alt="Shorturl"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse"
                data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <?php
                require_once ROOT_DIR . '/data/UserHelper.php';
                if (\Application\Shorturl\Helpers\UserHelper::isUserLogged()) {
                    require_once 'user_menu.php';
                } else {
                    require_once 'anonim_menu.php';
                }
                ?>
            </ul>
        </div>
    </div>
</nav>

<header class="header">
    <div class="container">

    </div>
</header>

<main class="main">
    <div class="container">
        <div class="row">
            <?php
            // Подключение файла с содержимом страницы
            require_once "$page_content_dir_path/content.php";
            ?>
        </div>
    </div>
</main>

<footer class="footer bg-dark text-white">
    <div class="container py-3">
        <div class="row">
            <div class="col text-center">
                &copy; SHORTURL &mdash; Сервис по сокращению ссылок
            </div>
        </div>
    </div>
</footer>

<script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/assets/js/popper.min.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap-confirmation.min.js"></script>
<script type="text/javascript" src="/assets/js/common.js"></script>

<?php
// Подключение файла с содержимом для нижней части страницы
require_once "$page_content_dir_path/foot.php";
?>
</body>
</html>
