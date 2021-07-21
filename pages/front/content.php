<?php
require_once ROOT_DIR . '/data/MiscHelper.php'; ?>

<div class="col text-center py-5">
    <?php
    if (!empty($_POST['shorturl-code'])): ?>
        <?php
        require_once ROOT_DIR . '/data/ShorturlHelper.php';
        $url_result = \Application\Shorturl\Helpers\ShorturlHelper::getUrlByCode($_POST['shorturl-code']);
        ?>

        <?php
        if (is_array($url_result)): ?>
            <?php
            switch ($url_result['status']) {
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
                foreach ($url_result['messages'] as $message): ?>
                    <p><?php
                        print $message; ?></p>
                <?php
                endforeach; ?>
            </div>
        <?php
        else: ?>
            <?php
            header("Location: $url_result"); ?>
        <?php
        endif; ?>
    <?php
    endif; ?>

    <form name="shorturl-nav" method="post">
        <div id="shorturl-code-wrapper" class="form-group required">
            <label for="exampleInputEmail1"><?php
                print \Application\Shorturl\Helpers\MiscHelper::getSiteUrl(); ?></label>
            <input type="text" class="form-control" id="shorturl-code" name="shorturl-code" placeholder="" required/>
        </div>
        <button type="submit" class="btn btn-primary">Перейти</button>
    </form>
</div>
