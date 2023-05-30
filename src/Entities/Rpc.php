<?php

namespace JalalLinuX\Thingsboard\Entities;

use DateTime;
use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumRpcSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumRpcStatus;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginatedResponse;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property Id $id
 * @property DateTime $createdTime
 * @property Id $tenantId
 * @property Id $deviceId
 * @property int $expirationTime
 * @property array $request
 * @property array $response
 * @property EnumRpcStatus $status
 * @property array $additionalInfo
 */
class Rpc extends Tntity
{
    protected $fillable = [
        'id',
        'tenantId',
        'deviceId',
        'method',
        'params',
        'timeout',
        'expirationTime',
        'persistent',
        'retries',
        'request',
        'response',
        'status',
        'additionalInfo',
    ];

    protected $casts = [
        'id' => Id::class,
        'tenantId' => Id::class,
        'deviceId' => Id::class,
        'status' => EnumRpcStatus::class,
        'params' => 'array',
        'timeout' => 'int',
        'expirationTime' => 'timestamp',
        'persistent' => 'bool',
        'retries' => 'int',
        'request' => 'array',
        'response' => 'array',
        'additionalInfo' => 'array',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct(array_merge(self::defaultAttributes(), $attributes));
    }

    public function entityType(): ?EnumEntityType
    {
        return EnumEntityType::RPC();
    }

    public function defaultAttributes(): array
    {
        return self::config('rest.rpc.default_attributes');
    }

    /**
     * Sends the one-way remote-procedure call (RPC) request to device.
     * The RPC call is A JSON that contains the method name ('method'), parameters ('params') and multiple optional fields.
     * See example below. We will review the properties of the RPC call one-by-one below.
     * {
     *  "method": "setGpio",
     *  "persistent": false,
     *  "timeout": 5000,
     *  "params": {
     *      "pin": 7,
     *      "value": 1
     *  }
     * }
     *
     * * Server-side RPC structure
     *  The body of server-side RPC request consists of multiple fields:
     *  - method - mandatory, name of the method to distinct the RPC calls. For example, "getCurrentTime" or "getWeatherForecast". The value of the parameter is a string.
     *  - params - mandatory, parameters used for processing of the request. The value is a JSON. Leave empty JSON "{}" if no parameters needed.
     *  - timeout - optional, value of the processing timeout in milliseconds. The default value is 10000 (10 seconds). The minimum value is 5000 (5 seconds).
     *  - expirationTime - optional, value of the epoch time (in milliseconds, UTC timezone). Overrides timeout if present.
     *  - persistent - optional, indicates persistent RPC. The default value is "false".
     *  - retries - optional, defines how many times persistent RPC will be re-sent in case of failures on the network and/or device side.
     *  - additionalInfo - optional, defines metadata for the persistent RPC that will be added to the persistent RPC events.
     *
     * * RPC Result
     * In case of persistent RPC, the result of this call is 'rpcId' UUID. In case of lightweight RPC, the result of this call is either 200 OK if the message was sent to device, or 504 Gateway Timeout if device is offline.
     * Available for users with 'TENANT_ADMIN' or 'CUSTOMER_USER' authority.
     *
     *
     *
     *
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function sendOneWay(string $deviceId, string $method, array $params): bool
    {
        throw_if(
            ! Str::isUuid($deviceId),
            $this->exception('method "deviceId" argument must be a valid uuid.'),
        );

        $payload = $this->fill(['method' => $method, 'params' => $params])->toArray();

        return $this->api(handleException: self::config('rest.exception.throw_bool_methods'))->post("rpc/oneway/{$deviceId}", $payload)->successful();
    }

    /**
     * Sends the two-way remote-procedure call (RPC) request to device.
     * The RPC call is A JSON that contains the method name ('method'), parameters ('params') and multiple optional fields.
     * See example below. We will review the properties of the RPC call one-by-one below.
     * {
     *  "method": "setGpio",
     *  "persistent": false,
     *  "timeout": 5000,
     *  "params": {
     *      "pin": 7,
     *      "value": 1
     *  }
     * }
     *
     * * Server-side RPC structure
     *  The body of server-side RPC request consists of multiple fields:
     *  - method - mandatory, name of the method to distinct the RPC calls. For example, "getCurrentTime" or "getWeatherForecast". The value of the parameter is a string.
     *  - params - mandatory, parameters used for processing of the request. The value is a JSON. Leave empty JSON "{}" if no parameters needed.
     *  - timeout - optional, value of the processing timeout in milliseconds. The default value is 10000 (10 seconds). The minimum value is 5000 (5 seconds).
     *  - expirationTime - optional, value of the epoch time (in milliseconds, UTC timezone). Overrides timeout if present.
     *  - persistent - optional, indicates persistent RPC. The default value is "false".
     *  - retries - optional, defines how many times persistent RPC will be re-sent in case of failures on the network and/or device side.
     *  - additionalInfo - optional, defines metadata for the persistent RPC that will be added to the persistent RPC events.
     *
     * * RPC Result
     * In case of persistent RPC, the result of this call is 'rpcId' UUID. In case of lightweight RPC, the result of this call is either 200 OK if the message was sent to device, or 504 Gateway Timeout if device is offline.
     * Available for users with 'TENANT_ADMIN' or 'CUSTOMER_USER' authority.
     *
     *
     *
     *
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function sendTwoWay(string $method, array $params, string $deviceId = null): bool
    {
        $deviceId = $deviceId ?? $this->forceAttribute('deviceId');

        throw_if(
            ! Str::isUuid($deviceId),
            $this->exception('method "deviceId" argument must be a valid uuid.'),
        );

        $payload = $this->fill(['method' => $method, 'params' => $params])->toArray();

        return $this->api(handleException: self::config('rest.exception.throw_bool_methods'))->post("rpc/twoway/{$deviceId}", $payload)->successful();
    }

    /**
     * Get information about the status of the RPC call.
     *
     *
     * @return self
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function getPersistentRequest(string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id');

        throw_if(
            ! Str::isUuid($id),
            $this->exception('method "id" argument must be a valid uuid.'),
        );

        $rpc = $this->api()->get("rpc/persistent/{$id}")->json();

        return tap($this, fn () => new self($rpc));
    }

    /**
     * Deletes the persistent RPC request.
     *
     *
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function deletePersistentRequest(string $id = null): bool
    {
        $id = $id ?? $this->forceAttribute('id');

        throw_if(
            ! Str::isUuid($id),
            $this->exception('method "id" argument must be a valid uuid.'),
        );

        return $this->api(handleException: self::config('rest.exception.throw_bool_methods'))->delete("rpc/persistent/{$id}")->successful();
    }

    /**
     * Allows to query RPC calls for specific device using pagination.
     *
     *
     *
     *
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function getPersistentRequests(PaginationArguments $paginationArguments, string $deviceId = null, EnumRpcStatus $rpcStatus = null): PaginatedResponse
    {
        $paginationArguments->validateSortProperty(EnumRpcSortProperty::class);

        $deviceId = $deviceId ?? $this->forceAttribute('deviceId');

        throw_if(
            ! Str::isUuid($deviceId),
            $this->exception('method "deviceId" argument must be a valid uuid.'),
        );

        $response = $this->api()->get("rpc/persistent/device/{$deviceId}", $paginationArguments->queryParams([
            'rpcStatus' => $rpcStatus,
        ]));

        return $this->paginatedResponse($response, $paginationArguments);
    }
}
