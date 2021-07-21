<div class="col text-center py-5">
    <?php
    if (!empty($_POST['add-url'])): ?>
        <?php
        require_once ROOT_DIR . '/data/ShorturlHelper.php';

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $add_result = \Application\Shorturl\Helpers\ShorturlHelper::addNewUrl($_SESSION['id'], $_POST['add-url']);

        if (isset($add_result['status'])) {
            switch ($add_result['status']) {
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
        }
        ?>

        <div class="alert alert-<?php
        print $alert_type; ?>" role="alert">
            <?php
            foreach ($add_result['messages'] as $message): ?>
                <p><?php
                    print $message; ?></p>
            <?php
            endforeach; ?>
        </div>
    <?php
    endif; ?>

    <form name="add-url" method="post">
        <div class="form-group form-buttons required">
            <input type="text" class="form-control" id="add-url" name="add-url"
                   placeholder="URL" required/>
        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>
</div>

<?php
if (isset($_POST['add-url'])) {
    unset($_POST['add-url']);
} ?>
