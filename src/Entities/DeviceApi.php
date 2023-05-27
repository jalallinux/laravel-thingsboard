<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Enums\ThingsboardEntityType;
use JalalLinuX\Thingsboard\Tntity;

class DeviceApi extends Tntity
{
    protected $fillable = [
        'deviceToken',
    ];

    public function entityType(): ?ThingsboardEntityType
    {
        return null;
    }

    /**
     * Post time-series data
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group GUEST
     */
    public function postTelemetry(array $payload, string $deviceToken = null): bool
    {
        if (empty($payload)) {
            throw $this->exception('method argument must be array of ["ts" => in millisecond-timestamp, "values" => in associative array]');
        }

        foreach ($payload as $row) {
            throw_if(
                ! array_key_exists('ts', $row) || strlen($row['ts']) != 13 || ! array_key_exists('values', $row) || ! isArrayAssoc($row['values']),
                $this->exception('method argument must be array of "ts" in millisecond-timestamp, "values" in associative array.')
            );
        }

        $deviceToken = $deviceToken ?? $this->forceAttribute('deviceToken');

        return $this->api(false)->post("/v1/{$deviceToken}/telemetry", $payload)->successful();
    }

    /**
     * Post attributes
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group GUEST
     */
    public function postDeviceAttributes(array $payload, string $deviceToken = null): bool
    {
        throw_if(
            ! isArrayAssoc($payload),
            $this->exception('method argument must be associative array.')
        );

        $deviceToken = $deviceToken ?? $this->forceAttribute('deviceToken');

        return $this->api(false)->post("/v1/{$deviceToken}/attributes", $payload)->successful();
    }
}
