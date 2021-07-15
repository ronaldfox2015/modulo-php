<?php

namespace Bumeran\Common\Adapter\Persistence\Doctrine;

/**
 * DoctrineRepositoryInterface Interface
 *
 * @package Bumeran\Common\Adapter\Persistence\Doctrine
 * @author Jose Guillermo <jguillermo@outlook.com>
 * @copyright (c) 2017, Orbis
 */
interface RepositoryInterface
{
    /**
     * @return mixed
     */
    public function flush();
}
