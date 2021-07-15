<?php

namespace Bumeran\Common\Adapter\Persistence\MongoDb;

use InvalidArgumentException;
use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Database;

/**
 * Class MongoDbRepository
 *
 * @package Bumeran\Common\Adapter\Persistence\MongoDb
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2016, Orbis
 */
abstract class MongoDbRepository
{
    protected $client;
    protected $database;
    protected $collection;

    public function __construct(array $config = [])
    {
        $options = array_merge([
            'host' => 'localhost',
            'port' => 27017,
            'user' => null,
            'password' => null,
            'database' => null,
            'timeout' => 30
        ], array_filter($config));

        $params = $this->getConnectionParams($options);

        $this->client = new Client('mongodb://' . $options['host'] . ':' . $options['port'] . $params, [
            'username' => $options['user'],
            'password' => $options['password'],
        ]);

        if (isset($options['database'])) {
            $this->database = $this->client->selectDatabase($options['database']);
        }

        $this->initialize();
    }

    abstract protected function initialize();

    public function setCollection($collectionName, array $options = [])
    {
        if (null === $this->database) {
            throw new InvalidArgumentException('MongoDB: Select a database');
        }

        $this->collection = $this->client->selectCollection($this->database, $collectionName, $options);

        return $this->collection;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * @return Database
     */
    public function getDatabase()
    {
        return $this->database;
    }

    private function getConnectionParams($options)
    {
        $params = [];
        $params['connectTimeoutMS'] = $options['timeout'];

        $arr = [];
        foreach ($params as $key => $value) {
            $arr[] = $key . '=' . $value;
        }

        return '?' . implode('&', $arr);
    }
}
