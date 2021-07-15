<?php

namespace Bumeran\Common\Exception;

/**
 * Class InvalidArgumentsException
 *
 * @package Bumeran\Common\Exception
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2016, Orbis
 */
class InvalidArgumentsException extends Exception
{
    public function __construct($message, $code = 400, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
