<?php

namespace Bumeran\Common\Domain\Model\Search;

/**
 * Interface SearchInputInterface
 *
 * @package Bumeran\Common\Domain\Model
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2016, Orbis
 */
interface SearchInputInterface
{
    /**
     * @return string
     */
    public function query();

    /**
     * @return int
     */
    public function page();

    /**
     * @return int
     */
    public function limit();

    /**
     * @param int $limit
     * @return mixed
     */
    public function setLimit($limit);

    /**
     * @return array
     */
    public function order();

    /**
     * @return array
     */
    public function filters();

    /**
     * @return boolean
     */
    public function withFacets();
}
