<div class="col text-center py-5">
    <?php
    require_once ROOT_DIR . '/data/ShorturlHelper.php';

    $code = str_replace('/', '', $_SERVER['REQUEST_URI']);
    $orig_url = \Application\Shorturl\Helpers\ShorturlHelper::getUrlByCode($code);
    ?>

    <?php
    if (is_array($orig_url)): ?>
        <?php
        if (isset($orig_url['status'])) {
            switch ($orig_url['status']) {
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
            foreach ($orig_url['messages'] as $message): ?>
                <p><?php
                    print $message; ?></p>
            <?php
            endforeach; ?>
        </div>
    <?php
    else: ?>
        <?php
        header("Location: $orig_url"); ?>
    <?php
    endif; ?>
</div>
