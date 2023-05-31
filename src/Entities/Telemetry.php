<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Support\Str;
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
        'scope',
    ];

    protected $casts = [
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
     * @param  array  $payload
     * @param  EnumTelemetryScope  $scope
     * @param  string  $deviceId
     * @return bool
     *
     * @throws \Throwable
     *
     * @author Sabiee
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function saveDeviceAttributes(array $payload, EnumTelemetryScope $scope, string $deviceId): bool
    {
        throw_if(
            $scope === EnumTelemetryScope::CLIENT_SCOPE(),
            $this->exception('method "scope" can\'t be client scope'),
        );

        if (empty($payload)) {
            throw $this->exception('method "payload" cannot be empty');
        }

        throw_if(
            ! Str::isUuid($deviceId),
            $this->exception('method "deviceId" argument must be a valid uuid.'),
        );

        return $this->api(self::config('rest.exception.throw_bool_methods'))->post("plugins/telemetry/{$deviceId}/{$scope}", $payload)->successful();
    }

    /**
     * Delete device attributes using provided Device Id, scope and a list of keys.
     * Referencing a non-existing Device Id will cause an error
     *
     * @param  EnumTelemetryScope  $scope
     * @param  array  $keys
     * @param  string  $deviceId
     * @return bool
     *
     * @throws \Throwable
     *
     * @author Sabiee
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function deleteDeviceAttributes(EnumTelemetryScope $scope, array $keys, string $deviceId): bool
    {
        if (empty($keys)) {
            throw $this->exception('method "keys" argument cannot be empty!');
        }

        throw_if(
            ! Str::isUuid($deviceId),
            $this->exception('method "deviceId" argument must be a valid uuid.'),
        );

        $keys = implode(',', $keys);

        return $this->api(self::config('rest.exception.throw_bool_methods'))->bodyFormat('query')
            ->delete("plugins/telemetry/{$deviceId}/{$scope}", ['keys' => $keys])->successful();
    }

    /**
     * Creates or updates the entity attributes based on Entity Id and the specified attribute scope.
     * List of possible attribute scopes depends on the entity type:
     * SERVER_SCOPE - supported for all entity types;
     * SHARED_SCOPE - supported for devices.
     * The request payload is a JSON object with key-value format of attributes to create or update.
     * For example:
     *
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
     * Referencing a non-existing entity Id or invalid entity type will cause an error.
     *
     * @author Sabiee
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     *
     * @param  array  $payload
     * @param  EnumEntityType  $entityType
     * @param  EnumTelemetryScope  $scope
     * @param  string  $entityId
     * @return bool
     *
     * @throws \Throwable
     */
    public function saveEntityAttributesV1(array $payload, EnumEntityType $entityType, string $entityId, EnumTelemetryScope $scope): bool
    {
        if (empty($payload)) {
            throw $this->exception('method "payload" cannot be empty');
        }

        throw_if(
            $scope === EnumTelemetryScope::CLIENT_SCOPE(),
            $this->exception('method "scope" can\'t be client scope'),
        );

        throw_if(
            ! Str::isUuid($entityId),
            $this->exception('method "entityId" argument must be a valid uuid.'),
        );

        return $this->api(self::config('rest.exception.throw_bool_methods'))->post("plugins/telemetry/{$entityType}/{$entityId}/{$scope}", $payload)->successful();
    }

    /**
     * Delete entity attributes using provided Entity Id, scope and a list of keys.
     * Referencing a non-existing entity Id or invalid entity type will cause an error.
     *
     * @author Sabiee
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function deleteEntityAttributes(EnumEntityType $entityType, string $entityId, EnumTelemetryScope $scope, array $keys): bool
    {
        if (empty($keys)) {
            throw $this->exception('method "keys" argument cannot be empty!');
        }

        throw_if(
            ! Str::isUuid($entityId),
            $this->exception('method "entityId" argument must be a valid uuid.'),
        );

        $keys = implode(',', $keys);

        return $this->api(self::config('rest.exception.throw_bool_methods'))->bodyFormat('query')
            ->delete("plugins/telemetry/{$entityType}/{$entityId}/{$scope}", ['keys' => $keys])->successful();
    }
}
