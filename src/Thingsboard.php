<?php

namespace JalalLinuX\Tntity;

use JalalLinuX\Tntity\Facade\DeviceApi;

class Thingsboard
{
    public function deviceApi(array $attributes = []): DeviceApi
    {
        return DeviceApi::make($attributes);
    }
}
