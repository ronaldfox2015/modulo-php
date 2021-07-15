<?php

namespace Bumeran\Common\Notification\SNS\Message;

/**
 * Class IOSMessage
 *
 * @package Bumeran\Common\Notification\SNS\Message
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2016, Orbis
 */
class IOSMessage implements MessageInterface
{
    /**
     * {@inheritdoc}
     */
    public function factory($data)
    {
        return json_encode([
            'default'      => $data['message'],
            'APNS_SANDBOX' => json_encode([
                'aps' => [
                    'category' => $data['type'],
                    'alert' => $data['message'],
                    'badge' => $data['badge'],
                    'sound' => 'mySound.caf',
                    'data'  => $data['data']
                ]
            ]),
            // Production Mode
            'APNS' => json_encode([
                'aps' => [
                    'category' => $data['type'],
                    'alert'    => $data['message'],
                    'badge'    => $data['badge'],
                    'sound'    => 'mySound.caf',
                    'data'     => $data['data']
                ]
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
