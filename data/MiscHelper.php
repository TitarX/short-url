<?php

namespace Application\Shorturl\Helpers;

class MiscHelper
{
    public static function getSiteUrl()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domain_name = $_SERVER['HTTP_HOST'] . '/';

        return $protocol . $domain_name;
    }
}
