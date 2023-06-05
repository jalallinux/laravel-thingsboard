<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Thingsboard;
use JalalLinuX\Thingsboard\Tntity;

class DeviceApi extends Tntity
{
    protected $fillable = [
        'deviceToken',
    ];

    public function entityType(): ?EnumEntityType
    {
        return null;
    }

    /**
     * Post time-series data on behalf of device.
     * Example of the request: The request payload is a JSON document with three possible formats:
     * [
     *  {"ts":1634712287000,"values":{"temperature":26, "humidity":87}},
     *  {"ts":1634712588000,"values":{"temperature":25, "humidity":88}}
     * ]
     *
     * @param  array  $payload
     * @param  string|null  $deviceToken
     * @return bool
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group GUEST
     */
    public function postTelemetry(array $payload, string $deviceToken = null): bool
    {
        Thingsboard::validation(empty($payload), 'array_of', ['attribute' => 'payload', 'struct' => '["ts" => in millisecond-timestamp, "values" => associative-array]']);

        foreach ($payload as $row) {
            Thingsboard::validation(
                ! array_key_exists('ts', $row) || strlen($row['ts']) != 13 || ! array_key_exists('values', $row) || ! isArrayAssoc($row['values']),
                'array_of', ['attribute' => 'payload', 'struct' => '["ts" => in millisecond-timestamp, "values" => associative-array]']
            );
        }

        $deviceToken = $deviceToken ?? $this->forceAttribute('deviceToken');

        return $this->api(false, config('thingsboard.rest.exception.throw_bool_methods'))->post("v1/{$deviceToken}/telemetry", $payload)->successful();
    }

    /**
     * Post client attribute updates on behalf of device.
     * Example of the request:
     * {
     *  "stringKey":"value1",
     *  "booleanKey":true,
     *  "doubleKey":42.0,
     *  "longKey":73,
     *  "jsonKey": {
     *      "someNumber": 42,
     *      "someArray": [1,2,3],
     *      "someNestedObject": {"key": "value"}
     *  }
     * }
     * The API call is designed to be used by device firmware and requires device access token ('deviceToken').
     * It is not recommended to use this API call by third-party scripts, rule-engine or platform widgets (use 'Telemetry Controller' instead).
     *
     * @param  array  $payload
     * @param  string|null  $deviceToken
     * @return bool
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group GUEST
     */
    public function postDeviceAttributes(array $payload, string $deviceToken = null): bool
    {
        Thingsboard::validation(! isArrayAssoc($payload), 'assoc_array', ['attribute' => 'payload']);

        $deviceToken = $deviceToken ?? $this->forceAttribute('deviceToken');

        return $this->api(false, config('thingsboard.rest.exception.throw_bool_methods'))->post("v1/{$deviceToken}/attributes", $payload)->successful();
    }

    /**
     * Returns all attributes that belong to device.
     * Use optional 'clientKeys' and/or 'sharedKeys' parameter to return specific attributes.
     * Example of the result:
     * {
     *  "stringKey":"value1",
     *  "booleanKey":true,
     *  "doubleKey":42.0,
     *  "longKey":73,
     *  "jsonKey": {
     *      "someNumber": 42,
     *      "someArray": [1,2,3],
     *      "someNestedObject": {"key": "value"}
     *  }
     * }
     * The API call is designed to be used by device firmware and requires device access token ('deviceToken').
     * It is not recommended to use this API call by third-party scripts, rule-engine or platform widgets (use 'Telemetry Controller' instead).
     *
     * @param  array  $clientKeys
     * @param  array  $sharedKeys
     * @param  string|null  $deviceToken
     * @return array
     *
     * @author JalalLinuX
     *
     * @group Guest
     */
    public function getDeviceAttributes(array $clientKeys = [], array $sharedKeys = [], string $deviceToken = null): array
    {
        $deviceToken = $deviceToken ?? $this->forceAttribute('deviceToken')->id;

        return $this->api(false)->get("v1/{$deviceToken}/attributes", [
            'clientKeys' => implode(',', $clientKeys), 'sharedKeys' => implode(',', $sharedKeys),
        ])->json();
    }

    /**
     * Send the RPC request to server.
     * The request payload is a JSON document that contains 'method' and 'params'.
     * For example:
     * {"method": "sumOnServer", "params":{"a":2, "b":2}}
     * The API call is designed to be used by device firmware and requires device access token ('deviceToken').
     * It is not recommended to use this API call by third-party scripts, rule-engine or platform widgets (use 'Telemetry Controller' instead).
     *
     * @param  string  $method
     * @param  array  $params
     * @param  string|null  $deviceToken
     * @return array
     *
     * @author JalalLinuX
     *
     * @group Guest
     */
    public function postRpcRequest(string $method, array $params = [], string $deviceToken = null): array
    {
        $deviceToken = $deviceToken ?? $this->forceAttribute('deviceToken')->id;

        return $this->api(false)->post("v1/{$deviceToken}/rpc", [
            'method' => $method, 'params' => $params,
        ])->json();
    }
}
