<?php

namespace Bumeran\Common\Symfony\Response;

use Symfony\Component\HttpFoundation\JsonResponse as BaseResponse;

/**
 * Class JsonResponse
 *
 * @package Bumeran\Common\Symfony\Response
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2017, Orbis
 */
class JsonResponse extends BaseResponse
{
    public function __construct(
        $data,
        $status = 200,
        array $headers = [],
        $json = false
    ) {

        $data = $this->prepareData($data);

        parent::__construct($data, $status, $headers, $json);
    }

    protected function prepareData($data)
    {
        return $data;
    }
}
