<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

session_destroy();

sleep(1);

header('Location: /');