<?php

namespace Bumeran\Common;

/**
 * Class Assertion
 *
 * @package   Bumeran\Common
 * @author    Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2016, Orbis
 */
class Assertion extends \Assert\Assertion
{
    static protected $exceptionClass = 'Bumeran\Common\Exception\AssertException';
}
