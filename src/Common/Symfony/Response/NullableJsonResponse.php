<?php

namespace Bumeran\Common\Symfony\Response;

/**
 * Class NullableJsonResponse
 *
 * @package Bumeran\Common\Symfony\Response
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2017, Orbis
 */
class NullableJsonResponse extends JsonResponse
{
    protected $nullableField = '';

    /**
     * {@inheritdoc}
     */
    protected function prepareData($data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->prepareData($value);
            } else {
                $data[$key] = isset($value) ? $value : $this->nullableField;
            }
        }

        return $data;
    }
}
