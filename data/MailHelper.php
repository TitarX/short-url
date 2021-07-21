<?php

namespace Application\Shorturl\Helpers;

class MailHelper
{
    public static function sendMail($emailTo, $emailSubject, $emailMessage)
    {
        $emailFrom = SITE_EMAIL;
        $emailHeaders = "From: $emailFrom" . "\r\n" .
            "Reply-To: $emailFrom" . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        return mail($emailTo, $emailSubject, $emailMessage, $emailHeaders);
    }
}
