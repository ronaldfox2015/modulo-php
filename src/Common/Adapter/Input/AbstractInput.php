<?php

namespace Bumeran\Common\Adapter\Input;

use InvalidArgumentException;

/**
 * Class AbstractInput
 *
 * @package Bumeran\Common\Adapter\Input
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2016, Orbis
 */
abstract class AbstractInput
{
    public $properties = [];

    /**
     * @return array
     */
    public function getArrayData()
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        if (null == $this->properties) {
            $this->properties = get_object_vars($this);
        }

        return $this->properties;
    }

    public function __call($name, array $arguments = null)
    {
        if (($properties = $this->getArrayData()) && array_key_exists($name, $properties)) {
            return $properties[$name];
        }

        return null;
    }

    public function __set($name, $value)
    {
        throw new InvalidArgumentException("Invalid property '$name' with value '$value'");
    }
}
