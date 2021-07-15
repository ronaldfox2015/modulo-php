<?php

namespace Bumeran\Common\Adapter\Persistence\Solr;

use Bumeran\Common\Util\Strings;
use Solarium\Client;

/**
 * Class SolrRepository
 *
 * @package Bumeran\Common\Adapter\Persistence\Solr
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2016, Orbis
 */
class SolrRepository
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getSelect(array $options = [])
    {
        return $this->client->createSelect($options);
    }

    public function getClient()
    {
        return $this->client;
    }

    public function suggesterBy($where, $limit = null, $order = null)
    {
        $key = key($where);
        $value = Strings::sanitizeSearch($where[$key]);

        $select = $this->getSelect();
        $select->setQuery("$key:(*$value*)");

        $limit && $select->setStart(0)->setRows($limit);
        $order && $select->addSorts($order);


        return $this->getClient()->suggester($select);
    }
}
