<?php

namespace Bumeran\Common\Notification\SNS\Platform;

use Bumeran\Common\Notification\SNS\Exception\InvalidArgumentException;
use Bumeran\Common\Notification\SNS\Message\MessageInterface;

/**
 * Class ChainPlatform
 *
 * @package Bumeran\Common\Notification\SNS\Platform
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2016, Orbis
 */
class ChainPlatform implements PlaftFormInterface
{
    protected $queue = [];
    protected $type;

    public function __construct(array $platforms = [])
    {
        $this->queue = $platforms;
    }

    public function addPlatform(PlaftFormInterface $plaftForm)
    {
        $this->queue[] = $plaftForm;
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenArn()
    {
        return $this->getPlatform()->getTokenArn();
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->getPlatform()->getMessage();
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType($type)
    {
        if (!PlatformType::isValidValue($type)) {
            throw new InvalidArgumentException("Platform is not supported");
        }

        $this->type = $type;
    }

    /**
     * @return PlaftFormInterface
     * @throws InvalidArgumentException
     */
    private function getPlatform()
    {
        foreach ($this->queue as $platform) {
            if ($platform->getType() == $this->getType()) {
                return $platform;
            }
        }

        throw new InvalidArgumentException(sprintf("Invalid platform type '%s'", $this->getType()));
    }

    /**
     * {@inheritdoc}
     */
    public function setMessage(MessageInterface $message)
    {
        $this->getPlatform()->setMessage($message);
    }
}
