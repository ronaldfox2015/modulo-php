<?php

namespace Bumeran\Common\Adapter\Email;

/**
 * Interface EmailServiceInterface
 *
 * @package Bumeran\Common\Adapter\Email
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2016, Orbis
 */
interface EmailServiceInterface
{
    /**
     * Send email to user
     *
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param mixed $body
     * @param string $type
     * @param bool $partial
     * @return bool
     */
    public function sendEmail($from, $to, $subject, $body, $type = null, $partial = false);
}
