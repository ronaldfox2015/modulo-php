<?php

namespace Bumeran\Common\Domain\Model;

use JsonSerializable;

/**
 * Class Entity
 *
 * @package   Bumeran\Common\Domain\Model
 * @author    Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2016, Orbis
 */
class Entity extends IdentifiedDomainObject implements JsonSerializable
{
    public function toArray()
    {
        return get_object_vars($this);
    }

    public function toString()
    {
        return implode(', ', $this->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
