<?php

namespace Bumeran\Common\Notification\SNS;

use Bumeran\Common\Notification\SNS\Message\MessageInterface;
use Bumeran\Common\Notification\SNS\Platform\PlaftFormInterface;
use Aws\Sns\SnsClient;
use Exception;

/**
 * Class SNSNotificationService
 *
 * @package Bumeran\Common\Notification
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2016, Orbis
 */
class NotificationService
{
    protected $client;
    protected $platform;

    public function __construct(SnsClient $client, PlaftFormInterface $platform)
    {
        $this->client = $client;
        $this->platform = $platform;
    }

    public function createPlatformEndpoint($deviceToken, $data = [], $force = true)
    {
        try {
            $result = $this->createEndpoint($deviceToken, $data);
        } catch (Exception $exception) {
            if ($force && preg_match('/Endpoint (.*?) already exists/i', $exception->getMessage(), $match)) {
                /**
                 * En algunos casos se genera un error cuando el token ya se encuentra registrado,
                 * pero con diferentes atributos. Para estos casos se procede a eliminar el token
                 * antiguo para poder registrarlo nuevamente.
                 */
                $this->deleteEndpoint($match[1]);

                try {
                    $result = $this->createEndpoint($deviceToken, $data);
                } catch (Exception $ex) {
                    $exception = $ex;
                }
            }

            throw $exception;
        } finally {
            if (false === isset($result['EndpointArn'])) {
                return false;
            }

            return $result['EndpointArn'];
        }
    }

    private function createEndpoint($deviceToken, $data = [])
    {
        return $this->client->createPlatformEndpoint([
            'PlatformApplicationArn' => $this->platform->getTokenArn(),
            'Token' => $deviceToken,
            'CustomUserData' => isset($data['userData']) ? $data['userData'] : null,
            'Attributes' => [
                'Enabled' => 'true'
            ]
        ]);
    }

    public function deleteEndpoint($endpointArn)
    {
        $this->client->deleteEndpoint(['EndpointArn' => $endpointArn]);
    }

    public function publish($tokenArn, $data)
    {
        $result = $this->client->publish([
            'TargetArn' => $tokenArn,
            'Message' => $this->getMessage()->factory($data),
            'MessageStructure' => $this->getMessage()->getType(),
        ]);

        return $result;
    }

    public function getMessage()
    {
        return $this->platform->getMessage();
    }

    public function setMessage(MessageInterface $message)
    {
        $this->platform->setMessage($message);
    }

    public function setPlatformType($type)
    {
        $this->platform->setType($type);

        return $this;
    }
}
