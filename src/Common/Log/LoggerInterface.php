<?php

namespace Bumeran\Common\Log;

/**
 * Interface LoggerInterface
 *
 * @package Bumeran\Common\Log
 * @author Pedro Vega Asto <pakgva@gmail.com>
 * @copyright (c) 2017, Orbis
 */
interface LoggerInterface
{
    /**
     * Write event log
     *
     * @param $message
     * @param array $context
     * @return mixed
     */
    public function write($message, array $context = []);

    /**
     * Filter events logs / only for debug purposes
     *
     * @param $criteria
     * @param int $limit
     * @param mixed $order
     * @return array
     */
    public function search($criteria, $limit, $order = null);
}
