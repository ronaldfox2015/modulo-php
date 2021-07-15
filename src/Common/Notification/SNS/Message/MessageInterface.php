<?php

namespace Bumeran\Common\Notification\SNS\Message;

/**
 * Interface MessageInterface
 *
 * @package Bumeran\Common\Notification\SNS\Message
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2016, Orbis
 */
interface MessageInterface
{
    /**
     * Message factory from data
     *
     * @param $data
     * @return mixed
     */
    public function factory($data);

    /**
     * Return message type
     *
     * @return string
     */
    public function getType();
}
