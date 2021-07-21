<div class="col text-center py-5">
    <?php

    // Возврат статус-кода 404 (Не найдено)
    header("HTTP/1.0 404 Not Found");

    print 'Запрашиваемая страница "' . $_SERVER['REQUEST_URI'] . '" не найдена';

    ?>
</div>
