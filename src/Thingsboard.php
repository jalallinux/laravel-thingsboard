<?php

namespace JalalLinuX\Tntity;

/**
 * @method \JalalLinuX\Tntity\Facade\Device device(array $attributes = [])
 * @method \JalalLinuX\Tntity\Facade\DeviceApi deviceApi(array $attributes = [])
 */
class Thingsboard
{
    public function __call(string $name, array $arguments)
    {
        $class = '\\JalalLinuX\\Tntity\\Facade\\'.ucfirst($name);

        return $class::make(...$arguments);
    }
}
