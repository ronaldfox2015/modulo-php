<?php

namespace Bumeran\Common\Notification\SNS\Message;

/**
 * Class AndroidMessage
 *
 * @package Bumeran\Common\Notification\SNS\Message
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2016, Orbis
 */
class AndroidMessage implements MessageInterface
{
    /**
     * {@inheritdoc}
     */
    public function factory($data)
    {
        $data['data']['type']  = $data['type'];
        $data['data']['title'] = $data['title'];
        $data['data']['body']  = $data['message'];
        $data['data']['badge'] = $data['badge'];

        return json_encode([
            'default' => $data['title'],
            'GCM' => json_encode([
                'data' => $data['data']
            ])
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'json';
    }
}
