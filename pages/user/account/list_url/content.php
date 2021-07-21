<div class="col text-center py-5">
    <form name="list-url" method="post">
        <?php
        require_once ROOT_DIR . '/data/ShorturlHelper.php';
        require_once ROOT_DIR . '/data/MiscHelper.php';
        ?>

        <?php
        if (isset($_POST['edit'])) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['edit_link_id'] = $_POST['edit'];
            header('Location: /edit_url.htm');
        }
        ?>

        <?php
        if (isset($_POST['remove'])): ?>
            <?php
            $remove_result = \Application\Shorturl\Helpers\ShorturlHelper::removeShorturl($_POST['remove']);
            $alert_type = 'dark';
            ?>

            <?php
            if (isset($remove_result['status'])): ?>
                <?php
                switch ($remove_result['status']) {
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
                    foreach ($remove_result['messages'] as $message): ?>
                        <p><?php
                            print $message; ?></p>
                    <?php
                    endforeach; ?>
                </div>
            <?php
            endif; ?>
        <?php
        endif; ?>

        <?php
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $user_urls = \Application\Shorturl\Helpers\ShorturlHelper::getUrlsByUser($_SESSION['id']);
        $site_url = \Application\Shorturl\Helpers\MiscHelper::getSiteUrl();

        $alert_type = 'dark';
        ?>

        <?php
        if (isset($user_urls['status'])): ?>
            <?php
            switch ($user_urls['status']) {
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
                foreach ($user_urls['messages'] as $message): ?>
                    <p><?php
                        print $message; ?></p>
                <?php
                endforeach; ?>
            </div>
        <?php
        else: ?>
            <?php
            foreach ($user_urls as $user_url): ?>
                <div class="py-3 url-item<?php
                print ((!empty($user_url[3]) && $user_url[3] < time()) ? ' shorturl-disabled' : ''); ?>">
                    <div class="row py-1 url-item-row url-item-url">
                        <div class="col col-2 url-item-col url-label">
                            Ссылка:
                        </div>
                        <div class="col col-10 url-item-col url-value">
                            <?php
                            print $user_url[1]; ?>
                        </div>
                    </div>
                    <div class="row py-1 url-item-row url-item-shurl">
                        <div class="col col-2 url-item-col url-label">
                            Сокращение:
                        </div>
                        <div class="col col-10 url-item-col url-value">
                            <?php
                            print $site_url . $user_url[2]; ?>
                        </div>
                    </div>
                    <div class="row py-1 url-item-row url-item-code">
                        <div class="col col-2 url-item-col url-label">
                            Код:
                        </div>
                        <div class="col col-10 url-item-col url-value">
                            <?php
                            print $user_url[2]; ?>
                        </div>
                    </div>
                    <div class="row py-1 url-item-row url-item-time">
                        <div class="col col-2 url-item-col url-label">
                            Деактивация:
                        </div>
                        <div class="col col-10 url-item-col url-value">
                            <?php
                            if (is_numeric($user_url[3]) && $user_url[3] == 0) {
                                print 'Неограниченно';
                            } else {
                                print date('d.m.Y', $user_url[3]);
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row url-item-row url-item-controls">
                        <div class="col col-6 url-item-edit">
                            <button type="submit" class="btn" name="edit" value="<?php
                            print $user_url[0]; ?>">
                                Изменить
                            </button>
                        </div>
                        <div class="col col-6 url-item-remove">
                            <button type="submit" class="btn" name="remove" value="<?php
                            print $user_url[0]; ?>"
                                    data-toggle="confirmation" data-title="Удалить ссылку?" data-btn-ok-label="Да"
                                    data-btn-cancel-label="Нет">
                                Удалить
                            </button>
                        </div>
                    </div>
                </div>
            <?php
            endforeach; ?>
        <?php
        endif; ?>
    </form>
</div>
