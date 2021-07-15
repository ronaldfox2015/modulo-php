<?php

namespace Bumeran\Common\Queue;

/**
 * Interface QueueInterface
 *
 * @package Bumeran\Common\Queue
 * @author Pedro Vega Asto <pakgva@gmail.com>
 * @copyright (c) 2017, Orbis
 */
interface QueueInterface
{
    public function sendMessage($message, $delay = 0);
    public function receivesMessages($limit);
    public function releaseMessage($messageId);
    public function deleteMessage($messageId);
}
