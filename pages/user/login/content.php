<div class="col text-center py-5">
    <?php
    if (!empty($_POST['user-email']) && !empty($_POST['user-password'])): ?>
        <?php
        require_once ROOT_DIR . '/data/User.php';
        require_once ROOT_DIR . '/data/UserHelper.php';

        // Создание объекта с данными пользователя
        $user = new \Application\Shorturl\User\User('', $_POST['user-email'], $_POST['user-password']);

        // Авторизация пользователя
        $result = \Application\Shorturl\Helpers\UserHelper::userLogin($user);

        switch ($result['status']) {
            case 'info':
                $alert_type = 'success';
                break;

            case 'warning':
                $alert_type = 'warning';
                break;

            case 'error':
                $alert_type = 'danger';
                break;

            default:
                $alert_type = 'dark';
        }
        ?>

        <div class="alert alert-<?php
        print $alert_type; ?>" role="alert">
            <?php
            foreach ($result['messages'] as $message): ?>
                <p><?php
                    print $message; ?></p>
            <?php
            endforeach; ?>
        </div>

        <?php
        if ($result['status'] == 'info') {
            sleep(1);
            header('Location: /list_url.htm');
        }
        ?>
    <?php
    endif; ?>

    <form name="user-login" method="post">
        <div class="form-group required">
            <input type="email" class="form-control" id="user-email" name="user-email" placeholder="Ваш email-адрес"
                   required/>
        </div>
        <div class="form-group form-buttons required">
            <input type="password" class="form-control" id="user-password" name="user-password"
                   placeholder="Пароль вашей учётной записи" required/>
        </div>
        <button type="submit" class="btn btn-primary">Войти</button>
        <a href="/recovery.htm" class="btn btn-warning">Забыли пароль?</a>
    </form>
</div>
