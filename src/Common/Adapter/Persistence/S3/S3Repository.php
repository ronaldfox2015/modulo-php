<?php

namespace Bumeran\Common\Adapter\Persistence\S3;

use Aws\Result;
use Aws\S3\S3Client;

/**
 * Class S3Repository
 *
 * @package Bumeran\Common\Adapter\Persistence\S3
 * @author Pedro Vega <pakgva@gmail.com>
 * @copyright (c) 2016, Orbis
 */
class S3Repository
{
    /** @var string */
    protected $bucket;

    /** @var string */
    protected $cdn;

    /** @var string */
    protected $folder;

    /** @var S3Client */
    protected $client;

    /**
     * S3Repository constructor.
     *
     * @param S3Client $client
     * @param array    $config
     */
    public function __construct(S3Client $client, array $config)
    {
        $this->client = $client;
        $this->bucket = $config['bucket'];
        $this->folder = $config['folder'];
    }

    /**
     * @param string $key
     * @return string
     */
    public function getFolderName($key)
    {
        if ($this->folder) {
            $key = rtrim($this->folder, '/') . '/' . $key;
        }

        return $key;
    }

    /**
     * @param string $folder
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;
    }

    /**
     * @param string $name
     * @param string $path
     * @return Result
     */
    public function upload($name, $path)
    {
        $result = $this->client->putObject(
            [
                'Bucket' => $this->getBucket(),
                'Key' => $this->getFolderName($name) . '/' . basename($path),
                'Body' => fopen($path, 'r'),
                'ACL' => Permission::PUBLIC_READ
            ]
        );

        return $result;
    }

    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * @param string $key
     * @return Result
     */
    public function delete($key)
    {
        $result = $this->client->deleteObject(
            [
                'Bucket' => $this->bucket,
                'Key' => $key
            ]
        );

        return $result;
    }
}
