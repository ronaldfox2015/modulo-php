<?php

namespace Bumeran\Common\Domain\Model;

use Psr\Log\LoggerInterface;

/**
 * Class DomainEventManager
 *
 * @package Bumeran\Common\Domain\Model
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2017, Orbis
 */
class DomainEventManager
{
    protected $manager;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->manager = DomainEventPublisher::instance();
        $this->logger = $logger;
    }

    public function subscribe($domainEvent)
    {
        $this->manager->subscribe($domainEvent);
    }

    public function unsubscribe($domainEvent)
    {
        $this->manager->unsubscribe($domainEvent);
    }

    public function publish($aDomainEvent)
    {
        $this->manager->publish($aDomainEvent);
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
