<?php

namespace App\Service;

use Swift_Mailer;
use Swift_Message;

class MailService
{
    /** @var Swift_Mailer */
    private $mailer;

    public function __construct()
    {
        $this->mailer = new Swift_Mailer(new \Swift_SmtpTransport(
            $_ENV['SMTP_HOST'],
            $_ENV['SMTP_PORT']
        ));
    }

    public function send(Swift_Message $msg)
    {
        $this->mailer->send($msg);
    }
}
