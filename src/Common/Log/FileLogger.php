<?php

namespace Bumeran\Common\Log;

/**
 * Class FileLogger
 *
 * @package Bumeran\Common\Log
 * @author Pedro Vega Asto <pakgva@gmail.com>
 * @copyright (c) 2017, Orbis
 */
class FileLogger implements LoggerInterface
{
    protected $fileName;

    /**
     * {@inheritdoc}
     */
    public function write($message, array $context = [])
    {
        file_put_contents($this->fileName, isset($context['xml']) ? $context['xml'] : $context, FILE_APPEND);
    }

    /**
     * {@inheritdoc}
     */
    public function search($criteria, $limit, $order = null)
    {
        // TODO: Implement search() method.
    }
}
