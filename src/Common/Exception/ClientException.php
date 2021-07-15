<?php

namespace Bumeran\Common\Exception;

/**
 * Class ClientException
 *
 * @package Bumeran\Common\Exception
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2016, Orbis
 */
class ClientException extends Exception
{
    protected $defaultMessage = 'Ocurrio un error. No se recibieron parametros o son incorrectos';

    public function __construct($message = '', $code = 400, $previous = null)
    {
        parent::__construct($message?: $this->defaultMessage, $code, $previous);
    }
}
