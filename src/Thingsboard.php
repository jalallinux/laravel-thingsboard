<?php

namespace JalalLinuX\Tntity;

use JalalLinuX\Tntity\Facade\DeviceApi;

/**
 * @method DeviceApi deviceApi(array $attributes = [])
 */
class Thingsboard
{
    public function __call(string $name, array $arguments)
    {
        $class = '\\JalalLinuX\\Tntity\\Facade\\'.ucfirst($name);

        return $class::make(...$arguments);
    }
}
