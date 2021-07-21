<div class="col text-center py-5">
    <?php
    if (!empty($_POST['user-name']) && !empty($_POST['user-email']) && !empty($_POST['user-password'])): ?>
        <?php
        require_once ROOT_DIR . '/data/User.php';
        require_once ROOT_DIR . '/data/UserHelper.php';

        // Создание объекта с данными пользователя
        $user = new \Application\Shorturl\User\User($_POST['user-name'], $_POST['user-email'], $_POST['user-password']);

        // Регистрация пользователя
        $result = \Application\Shorturl\Helpers\UserHelper::userRegistration($user);

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
    endif; ?>

    <form name="user-registration" method="post">
        <div class="form-group">
            <input type="text" class="form-control" id="user-name" name="user-name" placeholder="Ваше имя"
                   required/>
        </div>
        <div class="form-group">
            <input type="email" class="form-control" id="user-email" name="user-email" placeholder="Ваш email-адрес"
                   required/>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" id="user-password" name="user-password"
                   placeholder="Пароль вашей будущей учётной записи" required/>
        </div>
        <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
    </form>
</div>
