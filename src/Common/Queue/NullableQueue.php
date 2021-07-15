<?php

namespace Bumeran\Common\Queue;

/**
 * Class NullableQueue
 *
 * @package Bumeran\Common\Queue
 * @author Pedro Vega Asto <pakgva@gmail.com>
 * @copyright (c) 2017, Orbis
 */
class NullableQueue implements QueueInterface
{
    /**
     * {@inheritdoc}
     */
    public function sendMessage($message, $delay = 0)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function receivesMessages($limit)
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function releaseMessage($messageId)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteMessage($messageId)
    {
        return true;
    }
}
