<?php

namespace Bumeran\Common\Exception;

/**
 * Class ServerErrorException
 *
 * @package Bumeran\Common\Exception\Entity
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @author Pedro Vega <pakgva@gmail.com>
 */
class ServerException extends Exception
{
    protected $defaultMessage = 'Ocurrio un error interno. Porfavor vuelva a intentarlo';

    public function __construct($message = null, $code = 500, $previous = null)
    {
        parent::__construct($message ?: $this->defaultMessage, $code, $previous);
    }
}
