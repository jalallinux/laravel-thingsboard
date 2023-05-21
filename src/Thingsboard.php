<?php

namespace JalalLinuX\Tntity;

/**
 * @method Facade\Device\Device device(array $attributes = [])
 * @method Facade\Device\DeviceApi deviceApi(array $attributes = [])
 */
class Thingsboard
{
    public function __call(string $name, array $arguments)
    {
        $class = '\\JalalLinuX\\Tntity\\Facade\\'.ucfirst($name);

        return $class::make(...$arguments);
    }
}
