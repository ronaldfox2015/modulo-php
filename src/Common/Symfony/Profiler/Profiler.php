<?php

namespace Bumeran\Common\Symfony\Profiler;

use Bumeran\Common\Symfony\Profiler\Storage\MemcacheProfilerStorage;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Profiler\FileProfilerStorage;
use Symfony\Component\HttpKernel\Profiler\Profiler as DefaultProfiler;
use Symfony\Component\HttpKernel\Profiler\ProfilerStorageInterface;

/**
 * Class Profiler
 *
 * @package Bumeran\Common\Symfony\Profiler
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2017, Orbis
 */
class Profiler extends DefaultProfiler
{
    /**
     * Profiler storage Map
     *
     * @var array
     */
    static private $storages = [
        'memcache' => MemcacheProfilerStorage::class,
        'file'     => FileProfilerStorage::class,
    ];

    public function __construct(
        ProfilerStorageInterface $storage,
        LoggerInterface $logger = null,
        $class = null,
        $dsn = null,
        $user = null,
        $password = null,
        $lifetime = null
    ) {

        if ($dsn) {
            $storageClass = $class ?: $this->storage($dsn);

            $storage = new $storageClass($dsn, $user, $password, $lifetime);
        }

        parent::__construct($storage, $logger);
    }

    private function storage($dsn)
    {
        $storageClass = strtolower(explode('://', $dsn)[0]);

        if (! isset(static::$storages[$storageClass])) {
            throw new InvalidArgumentException("Profiler storage '$storageClass' is not supported");
        }

        return static::$storages[$storageClass];
    }
}
