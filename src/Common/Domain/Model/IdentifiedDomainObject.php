<?php

namespace Bumeran\Common\Domain\Model;

use Bumeran\Common\Assertion;

/**
 * Class IdentifiedDomainObject
 *
 * @package   Bumeran\Common\Domain\Model
 * @author    Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2016, Orbis
 */
class IdentifiedDomainObject extends Assertion
{
    protected $id = -1;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}
