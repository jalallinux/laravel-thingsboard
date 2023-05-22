<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Tntity;

class DeviceApi extends Tntity
{
    protected $fillable = [
        'deviceToken',
    ];

    protected $casts = [
        'deviceToken' => 'string',
    ];

    public function postTelemetry(array $payload): bool
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

        return $this->api()->post("/v1/{$this->forceAttribute('deviceToken')}/telemetry", $payload)->successful();
    }

    public function postAttributes(array $payload): bool
    {
        throw_if(
            ! isArrayAssoc($payload),
            $this->exception('method argument must be associative array.')
        );

        return $this->api()->post("/v1/{$this->forceAttribute('deviceToken')}/attributes", $payload)->successful();
    }
}
