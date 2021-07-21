<div class="col text-center py-5">
    <?php
    if (!empty($_POST['user-email'])): ?>
        <?php
        require_once ROOT_DIR . '/data/UserHelper.php';
        require_once ROOT_DIR . '/data/MailHelper.php';

        $message = '';
        if (\Application\Shorturl\Helpers\UserHelper::isUserExistsByEmail($_POST['user-email'])) {
            \Application\Shorturl\Helpers\UserHelper::createNewPasswordForUserEmail($_POST['user-email']);

            $alert_type = 'success';
            $message = 'Пароль для учётной записи успешно изменён и отправлен на указанный email-адрес';
        } else {
            $alert_type = 'warning';
            $message = 'Пользователь с указанным email-адресом в системе не существует';
        }
        ?>

        <div class="alert alert-<?php
        print $alert_type; ?>" role="alert">
            <?php
            print $message; ?>
        </div>
    <?php
    endif; ?>

    <form name="password-recovery" method="post">
        <div class="form-group required">
            <input type="email" class="form-control" id="user-email" name="user-email" placeholder="Ваш email-адрес"
                   required/>
        </div>
        <button type="submit" class="btn btn-primary">Восстановить</button>
    </form>
</div>
