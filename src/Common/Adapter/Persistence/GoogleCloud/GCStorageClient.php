<?php


namespace Bumeran\Common\Adapter\Persistence\GoogleCloud;

use Google\Cloud\Storage\StorageClient;

class GCStorageClient
{
    /** @var string */
    protected $bucket;

    /** @var string */
    protected $cdn;

    /** @var string */
    protected $folder;

    /** @var StorageClient */
    protected $client;

    protected $level;

    protected $permits;

    /**
     * S3Repository constructor.
     *
     * @param StorageClient $client
     * @param array $config
     */
    public function __construct(array $config)
    {
        $storage = new StorageClient(
            [

                'projectId' => $config['google_project_id']
            ]
        );
        $this->level = 'publicRead';
        $this->permits = [
            'iamConfiguration' => [
                'uniformBucketLevelAccess' => [
                    'enabled' => false
                ],
            ]
        ];

        if (!empty($config['google_key_path'])) {
            $this->level = 'publicRead';
            $this->permits = [
                'predefinedAcl' => $this->level
            ];
            $storage = new StorageClient(
                [
                    'keyFilePath' => $config['google_key_path'],
                    'projectId' => $config['google_project_id']
                ]
            );
        }

        $this->bucket = $storage->bucket($config['bucket']);
        $this->folder = $config['folder'];
    }

    /**
     * @param string $key
     * @return string
     */
    public function getFolderName($key)
    {
        if ($this->folder) {
            $key = $this->folder . '/' . $key;
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
        $this->permits['name'] = $this->getFolderName($name) . '/' . basename($path);
        return $this->bucket->upload(
            fopen($path, 'r'),
            $this->permits
        );
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


        return [];
    }
}
