<?php

namespace JalalLinuX\Tntity\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JalalLinuX\Tntity\Entities\Device\DeviceApi
 *
 * @method bool postTelemetry(array $payload)
 */
class DeviceApi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'thingsboard.entity.DeviceApi';
    }
}
