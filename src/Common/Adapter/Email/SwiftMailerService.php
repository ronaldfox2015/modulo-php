<?php

namespace Bumeran\Common\Adapter\Email;

use Swift_Mailer as SwiftMailer;
use Swift_Message as SwiftMessage;

/**
 * Class SwiftMailerService
 *
 * @package Bumeran\Common\Adapter\Email
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2016, Orbis
 */
class SwiftMailerService implements EmailServiceInterface
{
    protected $mailer;

    public function __construct(SwiftMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * {@inheritdoc}
     */
    public function sendEmail($from, $to, $subject, $body, $type = 'text/html', $partial = false)
    {
        $message = SwiftMessage::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body)
            ->setContentType($type);

        $this->mailer->send($message);
    }
}
