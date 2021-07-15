<?php

namespace Bumeran\Common\Domain\Model\Search;

use ArrayAccess;
use Countable;
use Iterator;
use JsonSerializable;

/**
 * Interface SearchIterator
 *
 * @package Bumeran\Common\Domain\Model\Search
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2016, Orbis
 */
interface SearchIterator extends ArrayAccess, Iterator, Countable, JsonSerializable
{
    public function setItems($items);
    public function setOptions(array $options = []);
    public function getOptions($name = null);
    public function parse($item);
}
