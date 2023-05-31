<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumTelemetryScope;
use JalalLinuX\Thingsboard\Tntity;

class Telemetry extends Tntity
{

    public function entityType(): ?EnumEntityType
    {
        return null;
    }

    protected $fillable = [
        'deviceId',
        'scope'
    ];

    protected $casts = [
        'deviceId' => CastId::class,
        'scope' => EnumTelemetryScope::class,
    ];

    /**
     * Creates or updates the device attributes based on device id and specified attribute scope.
     * The request payload is a JSON object with key-value format of attributes to create or update.
     * For example:
     * {
     * "stringKey":"value1",
     * "booleanKey":true,
     * "doubleKey":42.0,
     * "longKey":73,
     * "jsonKey": {
     * "someNumber": 42,
     * "someArray": [1,2,3],
     * "someNestedObject": {"key": "value"}
     * }
     * }
     *
     * @param array $payload
     *
     * @param EnumTelemetryScope $scope
     *
     * @param string|null $deviceId
     *
     * @return bool
     *
     * @throws \Throwable
     *
     * @author Sabiee
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function saveDeviceAttributes(array $payload, EnumTelemetryScope $scope, string $deviceId = null): bool
    {
        throw_if(
            $scope === EnumTelemetryScope::CLIENT_SCOPE(),
            $this->exception('method "scope" can\'t be client scope'),
        );

        if (empty($payload)) {
            throw $this->exception('method "payload" cannot be empty');
        }

        $deviceId = $deviceId ?? $this->forceAttribute('deviceId')->id;

        throw_if(
            !Str::isUuid($deviceId),
            $this->exception('method "deviceId" argument must be a valid uuid.'),
        );

        return $this->api(self::config('rest.exception.throw_bool_methods'))->post("plugins/telemetry/{$deviceId}/{$scope}", $payload)->successful();
    }

    /**
     * Delete device attributes using provided Device Id, scope and a list of keys.
     * Referencing a non-existing Device Id will cause an error
     *
     * @param EnumTelemetryScope $scope
     *
     * @param array $keys
     *
     * @param string|null $deviceId
     *
     * @return bool
     *
     * @throws \Exception
     *
     * @author Sabiee
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function deleteDeviceAttributes(EnumTelemetryScope $scope, array $keys, string $deviceId = null): bool
    {
        if (empty($keys)) {
            throw $this->exception('method "keys" argument cannot be empty!');
        }
        $keys = implode(',', $keys);

        $deviceId = $deviceId ?? $this->forceAttribute('deviceId')->id;

        return $this->api(self::config('rest.exception.throw_bool_methods'))->bodyFormat('query')
            ->delete("plugins/telemetry/{$deviceId}/{$scope}", ['keys' => $keys])->successful();
    }
}
