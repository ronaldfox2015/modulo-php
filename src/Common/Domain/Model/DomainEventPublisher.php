<?php

namespace Bumeran\Common\Domain\Model;

/**
 * Class DomainEventPublisher
 *
 * @package Bumeran\Common\Domain\Model
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2016, Orbis
 */
class DomainEventPublisher
{
    /**
     * @var DomainEventPublisher
     */
    private static $instance = null;

    /**
     * @var DomainEventSubscriber[]
     */
    private $subscribers = [];
    private $id = 0;

    public static function instance()
    {
        if (null === static::$instance) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    public function __clone()
    {
        throw new \BadMethodCallException('Clone is not supported');
    }

    public function subscribe(DomainEventSubscriber $aDomainEventSubscriber)
    {
        $this->subscribers[$this->id++] = $aDomainEventSubscriber;

        return $this;
    }

    public function unsubscribe($id)
    {
        unset($this->subscribers[$id]);
    }

    public function publish(DomainEvent $aDomainEvent)
    {
        foreach ($this->subscribers as $aSubscriber) {
            if ($aSubscriber->isSubscribedTo($aDomainEvent)) {
                $aSubscriber->handle($aDomainEvent);
            }
        }
    }
}
