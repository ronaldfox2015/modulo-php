<?php

namespace Bumeran\Common\Domain\Model;

interface DomainEvent
{
    /**
     * @return int
     */
    public function getEventVersion();

    /**
     * @return \DateTime
     */
    public function getOccurredOn();
}
