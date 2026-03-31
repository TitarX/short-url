<?php

namespace Application\Shorturl\Helpers;

class MiscHelper
{
    public static function getSiteUrl(): string
    {
        $domain_name = $_SERVER['HTTP_HOST'];
        $domain_name_with_https = "https://$domain_name";
        $protocol = (
            !empty($_SERVER['HTTPS'])
            && $_SERVER['HTTPS'] !== 'off'
            || $_SERVER['SERVER_PORT'] == 443
            || str_contains($_SERVER['HTTP_REFERER'], $domain_name_with_https)
        ) ? 'https://' : 'http://';

        return $protocol . $domain_name . '/';
    }
}
