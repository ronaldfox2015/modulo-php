<?php

namespace Bumeran\Common\Log;

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use DateTime;
use RuntimeException;

/**
 * Class CloudWatchLog
 *
 * @package Bumeran\Common\Log
 * @author Pedro Vega Asto <pakgva@gmail.com>
 * @copyright (c) 2017, Orbis
 */
class CloudWatchLog
{
    const BATCH_SIZE    = 50;
    const SIMPLE_DATE   = "Y-m-d H:i:s";
    const SIMPLE_FORMAT = "[%datetime%] : %message% %context%\n";

    private $initialized = false;
    private $client;
    private $logGroupName;
    private $logStreamName;
    private $uploadSequenceToken;
    private $retentionDays;
    private $allowInlineLineBreaks = false;
    private $ignoreEmptyContext = true;

    public function __construct(
        CloudWatchLogsClient $client,
        $logGroupName,
        $logStreamName,
        $retentionDays = 7
    ) {

        $this->client = $client;
        $this->logGroupName = $logGroupName;
        $this->logStreamName = $logStreamName;
        $this->retentionDays = $retentionDays;
    }

    private function initialize()
    {
        if ($this->initialized) {
            return;
        }

        if (! in_array($this->logGroupName, $this->getLogGroupsName(), true)) {
            $this->client->createLogGroup(['logGroupName' => $this->logGroupName]);
            $this->client->putRetentionPolicy([
                'logGroupName' => $this->logGroupName,
                'retentionInDays' => $this->retentionDays,
            ]);
        }

        if (! in_array($this->logStreamName, $this->getLogsStreams($this->logGroupName, $this->logStreamName), true)) {
            $this->client->createLogStream([
                'logGroupName' => $this->logGroupName,
                'logStreamName' => $this->logStreamName
            ]);
        }

        $this->initialized = true;
    }

    public function write($message, array $context = [])
    {
        $this->initialize();

        $events[] = [
            'message' => $this->format($message, $context),
            'timestamp' => round(microtime(true) * 1000),
        ];

        $data = [
            'logGroupName' => $this->logGroupName,
            'logStreamName' => $this->logStreamName,
            'logEvents' => $events
        ];

        if ($this->uploadSequenceToken) {
            $data['sequenceToken'] = $this->uploadSequenceToken;
        }

        $response = $this->client->putLogEvents($data);

        $this->uploadSequenceToken = $response->get('nextSequenceToken');

        return true;
    }

    public function filterLogs($filter, $limit, $startTimeAt, $endTimeAt)
    {
        $this->initialize();

        $query = array_filter([
            'logGroupName'   => $this->logGroupName,
            'filterPattern'  => $filter,
            'logStreamNames' => [$this->logStreamName],
            'startTime'      => $startTimeAt,
            'endTime'        => $endTimeAt
        ]);

        /*if ($this->uploadSequenceToken) {
            $query['nextToken'] = $this->uploadSequenceToken;
        }*/

        $request = $this->client->filterLogEvents($query);

        if (! $request->hasKey('events')) {
            return [];
        }

        return $request->get('events');
    }

    private function getLogGroupsName()
    {
        $request = $this->client->describeLogGroups(['logGroupNamePrefix' => $this->logGroupName]);

        return (array)array_column($request->get('logGroups'), 'logGroupName');
    }

    private function getLogsStreams($logGroup, $streamName = null)
    {
        $request = $this->client->describeLogStreams([
            'logGroupName' => $logGroup,
            'logStreamNamePrefix' => $streamName,
        ]);

        return array_map(
            function ($stream) use ($streamName) {
                // set sequence token
                if ($stream['logStreamName'] === $streamName && isset($stream['uploadSequenceToken'])) {
                    $this->uploadSequenceToken = $stream['uploadSequenceToken'];
                }
                return $stream['logStreamName'];
            },
            $request->get('logStreams')
        );
    }

    private function format($message, $context)
    {
        $record = [
            'message' => (string)$message,
            'context' => $context,
            'datetime' => new DateTime()
        ];

        $vars = $this->normalize($record);

        $output = static::SIMPLE_FORMAT;

        foreach ($vars['context'] as $var => $val) {
            if (false !== strpos($output, '%context.' . $var . '%')) {
                $output = str_replace('%context.' . $var . '%', $this->stringify($val), $output);
                unset($vars['context'][$var]);
            }
        }

        if ($this->ignoreEmptyContext) {
            if (empty($vars['context'])) {
                unset($vars['context']);
                $output = str_replace('%context%', '', $output);
            }
        }

        foreach ($vars as $var => $val) {
            if (false !== strpos($output, '%' . $var . '%')) {
                $output = str_replace('%' . $var . '%', $this->stringify($val), $output);
            }
        }

        if (false !== strpos($output, '%')) {
            $output = preg_replace('/%(?:context)\..+?%/', '', $output);
        }

        return $output;
    }

    public function stringify($value)
    {
        return $this->replaceNewlines($this->convertToString($value));
    }

    protected function convertToString($data)
    {
        if (null === $data || is_bool($data)) {
            return var_export($data, true);
        }

        if (is_scalar($data)) {
            return (string)$data;
        }

        return $this->toJson($data, true);
    }

    protected function replaceNewlines($str)
    {
        if ($this->allowInlineLineBreaks) {
            return $str;
        }

        return str_replace(["\r\n", "\r", "\n"], ' ', $str);
    }

    protected function normalize($data)
    {
        if (null === $data || is_scalar($data)) {
            if (is_float($data)) {
                if (is_infinite($data)) {
                    return ($data > 0 ? '' : '-') . 'INF';
                }
                if (is_nan($data)) {
                    return 'NaN';
                }
            }

            return $data;
        }

        if (is_array($data)) {
            $normalized = [];

            $count = 1;
            foreach ($data as $key => $value) {
                if ($count++ >= 1000) {
                    $normalized['...'] = 'Over 1000 items (' . count($data) . ' total), aborting normalization';
                    break;
                }
                $normalized[$key] = $this->normalize($value);
            }

            return $normalized;
        }

        if ($data instanceof \DateTime) {
            return $data->format(static::SIMPLE_DATE);
        }

        if (is_object($data)) {
            if (method_exists($data, '__toString') && ! $data instanceof \JsonSerializable) {
                $value = $data->__toString();
            } else {
                $value = $this->toJson($data, true);
            }

            return sprintf("[object] (%s: %s)", get_class($data), $value);
        }

        if (is_resource($data)) {
            return sprintf('[resource] (%s)', get_resource_type($data));
        }

        return '[unknown(' . gettype($data) . ')]';
    }

    protected function toJson($data, $ignoreErrors = false)
    {
        if ($ignoreErrors) {
            return @$this->jsonEncode($data);
        }

        $json = $this->jsonEncode($data);

        if ($json === false) {
            throw new RuntimeException(
                'JSON encoding failed: ' . json_last_error() . '. Encoding: ' . var_export($data, true)
            );
        }

        return $json;
    }

    private function jsonEncode($data)
    {
        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
