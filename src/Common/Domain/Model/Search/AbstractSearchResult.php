<?php

namespace Bumeran\Common\Domain\Model\Search;

/**
 * Class AbstractSearchResult
 *
 * @package Bumeran\Common\Domain\Model\Search
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2016, Orbis
 */
abstract class AbstractSearchResult implements SearchResultInterface
{
    protected $facets;
    protected $total;
    protected $pages;
    protected $options = [];
    private $items;

    /** @var  SearchIterator */
    protected $iterator;

    public function __construct(
        array $items,
        $total,
        $pages,
        array $facets = []
    ) {

        $this->items = $items;
        $this->facets = $facets;
        $this->total = $total;
        $this->pages = $pages;
    }

    /**
     * {@inheritdoc}
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * {@inheritdoc}
     */
    public function getFacets()
    {
        return $this->facets;
    }

    /**
     * {@inheritdoc}
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * {@inheritdoc}
     */
    public function setIterator(SearchIterator $iterator)
    {
        $iterator->setItems($this->items);
        $iterator->setOptions($this->options); // inherit options

        $this->iterator = $iterator;
    }

    /**
     * {@inheritdoc}
     */
    public function getItems()
    {
        return $this->iterator;
    }

    /**
     * {@inheritdoc}
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options = [])
    {
        $this->options = $options;

        $this->iterator->setOptions($options);
    }
}
