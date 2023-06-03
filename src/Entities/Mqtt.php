<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Thingsboard;
use PhpMqtt\Client\Contracts\Repository;
use PhpMqtt\Client\MqttClient;
use Psr\Log\LoggerInterface;

class Mqtt
{
    private string $accessToken;

    public function __construct(string $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    private function mqtt(string $protocol = MqttClient::MQTT_3_1, Repository $repository = null, LoggerInterface $logger = null): MqttClient
    {
        $mqtt = new MqttClient(
            config('thingsboard.mqtt.host'), config('thingsboard.mqtt.port'), $this->accessToken,
            $protocol ?? config('thingsboard.mqtt.protocol'), $repository ?? config('thingsboard.mqtt.repository'), $logger
        );

        $mqtt->connect(Thingsboard::connectionSetting($this->accessToken));
        return $mqtt;
    }

    public function telemetry(array $payload, string $topic = null): void
    {
        Thingsboard::validation(empty($payload), 'array_of', ['attribute' => 'payload', 'struct' => '["ts" => in millisecond-timestamp, "values" => associative-array]']);

        foreach ($payload as $row) {
            Thingsboard::validation(
                !array_key_exists('ts', $row) || strlen($row['ts']) != 13 || !array_key_exists('values', $row) || !isArrayAssoc($row['values']),
                'array_of', ['attribute' => 'payload', 'struct' => '["ts" => in millisecond-timestamp, "values" => associative-array]']
            );
        }

        $this->mqtt()->publish($topic ?? config('thingsboard.mqtt.topics.telemetry'), json_encode($payload));
    }

    public function attribute(array $payload, string $topic = null): void
    {
        Thingsboard::validation(! isArrayAssoc($payload), 'assoc_array', ['attribute' => 'payload']);

        $this->mqtt()->publish($topic ?? config('thingsboard.mqtt.topics.attribute'), json_encode($payload));
    }

    public function subscribeRequest(string $requestId = null)
    {
        $topic = is_null($requestId) ? config('thingsboard.mqtt.topics.request') : rtrim(config('thingsboard.mqtt.topics.request'), '+') . $requestId;

        dd($topic);

    }

    public function subscribeRpc(string $requestId = null)
    {
        $topic = is_null($requestId) ? config('thingsboard.mqtt.topics.request') : rtrim(config('thingsboard.mqtt.topics.request'), '+') . $requestId;

        dd($topic);

    }
}
