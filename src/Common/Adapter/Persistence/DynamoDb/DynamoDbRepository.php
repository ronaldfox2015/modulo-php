<?php

namespace Bumeran\Common\Adapter\Persistence\DynamoDb;

use Bumeran\Common\Exception\Repository\ErrorException;
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;

/**
 * Class DynamoDbRepository
 *
 * @package Bumeran\Common\Adapter\Persistence\DynamoDb
 * @author Pedro Vega Asto <pakgva@gmail.com>
 * @copyright (c) 2017, Orbis
 */
class DynamoDbRepository
{
    const SYMBOL_EXPRESSION = '/^([+]{2}|[-]{2})([0-9]*[.])?[0-9]+$/';

    protected $marshaller;
    protected $client;
    protected $tableName;

    /**
     * DynamoDbRepository constructor.
     *
     * @param DynamoDbClient $client Dynamodb Client
     * @param string $tableName Nombre de la Tabla
     */
    public function __construct(DynamoDbClient $client, $tableName = null)
    {
        $this->client = $client;
        $this->marshaller = new Marshaler();

        if ($tableName) {
            $this->setTableName($tableName);
        }
    }

    /**
     * @param string $tableName
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    /**
     * @return mixed
     * @throws ErrorException
     */
    protected function getTableName()
    {
        if (! $this->tableName) {
            throw new ErrorException("No está definido el nombre de la tabla");
        }
        return $this->tableName;
    }

    public function getItem(array $where)
    {
        return $this->getItemByColumn($where);
    }

    public function getItemByColumn(array $where)
    {
        $result = $this->client->getItem([
            'TableName' => $this->getTableName(),
            'Key' => $this->processWhere($where),
        ]);

        if (! $result->hasKey('Item')) {
            return null;
        }

        return $this->marshaller->unmarshalItem($result->get('Item'));
    }

    public function putItem($data)
    {
        $result = $this->client->putItem([
            'TableName' => $this->getTableName(),
            'Item' => $this->marshaller->marshalItem($data)
        ]);

        if (! $result->hasKey('Item')) {
            return null;
        }

        return $this->marshaller->unmarshalItem($result->get('Item'));
    }

    public function updateItem(array $where, array $dataUpdate, $updateExpression = null)
    {
        $updateProcess = [
            'TableName' => $this->getTableName(),
            'Key' => $this->processWhere($where),
            'ExpressionAttributeValues' => $this->expressionAttributeValues($dataUpdate),
            'ExpressionAttributeNames' => $this->expressionAttributeNames($dataUpdate),
            'UpdateExpression' => ($updateExpression) ? $updateExpression : $this->generateUpdateExpression($dataUpdate)
        ];

        $this->client->updateItem($updateProcess);

        return true;
    }

    public function deleteItem(array $where)
    {
        $this->client->deleteItem([
            'TableName' => $this->getTableName(),
            'Key' => $this->processWhere($where),
        ]);

        return true;
    }

    private function generateUpdateExpression(array $dataUpdate)
    {
        $return = "set";
        $countName = 1;
        $countValue = 1;
        foreach ($dataUpdate as $key => $value) {
            $nameAttribute = "";
            foreach (explode('.', $key) as $val) {
                $nameAttribute .= "#NA{$countName}.";
                $countName++;
            }
            $nameAttribute = rtrim($nameAttribute, '.');

            if (preg_match(static::SYMBOL_EXPRESSION, $value)) {
                $symbol = substr($value, 0, 1);
                $return .= " {$nameAttribute}= {$nameAttribute} {$symbol} :val{$countValue},";
            } else {
                $return .= " {$nameAttribute} = :val{$countValue},";
            }
            $countValue++;
        }
        return rtrim(trim($return), ',');
    }

    private function expressionAttributeValues(array $dataUpdate)
    {
        $return = [];
        $count = 1;
        foreach ($dataUpdate as $value) {
            $val = $value;
            if (preg_match(static::SYMBOL_EXPRESSION, $value)) {
                $val = floatval(substr($value, 2));
            }
            $return[':val'.$count++] = $this->marshaller->marshalValue($val);
        }
        return $return;
    }

    private function expressionAttributeNames(array $dataUpdate)
    {
        $return = [];
        $count = 1;
        foreach ($dataUpdate as $key => $value) {
            if (! is_string($key)) {
                throw new ErrorException("expressionAttributeNames, KEY must be string");
            }
            foreach (explode('.', $key) as $val) {
                $return['#NA'.$count++] = trim($val);
            }
        }
        return $return;
    }

    private function processWhere(array $where)
    {
        $return = [];
        foreach ($where as $key => $value) {
            if (! is_string($key)) {
                throw new ErrorException("process WHERE, KEY must be string");
            }
            $return[$key] = $this->marshaller->marshalValue($value);
        }
        return $return;
    }
}
