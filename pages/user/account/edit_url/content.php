<div class="col text-center py-5">
    <?php
    require_once ROOT_DIR . '/data/ShorturlHelper.php';

    $alert_type = 'dark';

    $edit_link_result = array();
    if (!empty($_POST['change-button'])) {
        $new_disabled_date = strtotime($_POST['link-edit-disabled-date']);
        $edit_link_result = \Application\Shorturl\Helpers\ShorturlHelper::changeShorturl($_POST['change-button'], $_POST['link-edit-code'], $new_disabled_date);
    }
    ?>

    <?php
    if (isset($edit_link_result['messages'])): ?>
        <?php
        switch ($edit_link_result['status']) {
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
            foreach ($edit_link_result['messages'] as $message): ?>
                <p><?php
                    print $message; ?></p>
            <?php
            endforeach; ?>
        </div>
    <?php
    endif; ?>

    <?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $link_result = array();
    if (!empty($_SESSION['edit_link_id'])) {
        $link_result = \Application\Shorturl\Helpers\ShorturlHelper::getShorturlDataById($_SESSION['edit_link_id']);
    }

    $alert_type = 'dark';
    ?>

    <?php
    if (isset($link_result['messages'])): ?>
        <?php
        switch ($link_result['status']) {
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
            foreach ($link_result['messages'] as $message): ?>
                <p><?php
                    print $message; ?></p>
            <?php
            endforeach; ?>
        </div>
    <?php
    endif; ?>

    <?php
    $link_url = '';
    if (!empty($link_result['url'])) {
        $link_url = $link_result['url'];
    }

    $link_code = '';
    if (!empty($link_result['code'])) {
        $link_code = $link_result['code'];
    }

    $link_disabled_date = '';
    if (!empty($link_result['disabled_date']) && $link_result['disabled_date'] > 0) {
        $link_disabled_date = date('d.m.Y', $link_result['disabled_date']);
    }
    ?>

    <form name="link-edit" method="post">
        <div class="form-group required row d-flex align-content-center justify-content-center">
            <label for="link-edit-url" class="col-2 text-right">Ссылка</label>
            <input type="text" class="form-control col-10" id="link-edit-url" name="link-edit-url"
                   value="<?php
                   print $link_url; ?>" disabled/>
        </div>
        <div class="form-group required row d-flex align-content-center justify-content-center">
            <label for="link-edit-code" class="col-2 text-right">Код</label>
            <input type="text" class="form-control col-10" id="link-edit-code" name="link-edit-code"
                   value="<?php
                   print $link_code; ?>" required/>
        </div>
        <div class="form-group required row d-flex align-content-center justify-content-center">
            <label for="link-edit-disabled-date" class="col-2 text-right">Деактивация</label>
            <input type="text" class="form-control col-10" id="link-edit-disabled-date" name="link-edit-disabled-date"
                   value="<?php
                   print $link_disabled_date; ?>"/>
        </div>
        <button type="submit" class="btn btn-primary"
                name="change-button"<?php
        print (empty($_SESSION['edit_link_id']) ? ' disabled' : ' value="' . $_SESSION['edit_link_id'] . '"'); ?>>
            Изменить
        </button>
    </form>
</div>

<?php
if (isset($_POST['change-button'])) {
    unset($_POST['change-button']);
} ?>
