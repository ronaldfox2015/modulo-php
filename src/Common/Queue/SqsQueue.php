<?php

namespace Bumeran\Common\Queue;

use Aws\Exception\AwsException;
use Aws\Sqs\SqsClient;
use Exception;

/**
 * Class SqsQueue
 *
 * @package Bumeran\Common\Queue
 * @author Pedro Vega Asto <pakgva@gmail.com>
 * @copyright (c) 2017, Orbis
 */
class SqsQueue implements QueueInterface
{
    protected $client;
    protected $queue;
    protected $initialized;
    protected $queueUrl;

    public function __construct(SqsClient $client, $queue)
    {
        $this->client = $client;
        $this->queue  = $queue;

        $this->initialize();
    }

    private function initialize()
    {
        if ($this->initialized) {
            return;
        }

        // First, validate if queue exists
        $result = $this->client->getQueueUrl(['QueueName' => $this->queue]);

        $this->queueUrl = $result->get('QueueUrl');

        if (! isset($this->queueUrl)) {
            $result = $this->client->createQueue(['QueueName' => $this->queue]);
            $this->queueUrl = $result->get('QueueUrl');
        }

        $this->initialized = true;
    }

    /**
     * {@inheritdoc}
     */
    public function sendMessage($message, $delay = 0)
    {
        try {
            $this->client->sendMessage([
                'QueueUrl'     => $this->queueUrl,
                'MessageBody'  => json_encode($message),
                'DelaySeconds' => $delay
            ]);

            return true;
        } catch (AwsException $exception) {
            return new Exception('Error sending message Sqs', 500, $exception);
        }
    }

    public function setQueue($queue)
    {
        $this->queue = $queue;
        $this->initialized = false;
        $this->initialize();
    }

    /**
     * {@inheritdoc}
     */
    public function receivesMessages($limit)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function releaseMessage($messageId)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function deleteMessage($messageId)
    {
    }
}
