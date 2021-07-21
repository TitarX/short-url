<div class="col text-center py-5">
    <?php

    // Возврат статус-кода 403 (Доступ запрещён)
    header("HTTP/1.0 403 Forbidden");

    print 'Доступ к странице "' . $_SERVER['REQUEST_URI'] . '" запрещён';

    ?>
</div>
