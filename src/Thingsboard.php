<?php

namespace JalalLinuX\Tntity;

/**
 * @method Facades\Entities\Device device(array $attributes = [])
 * @method Facades\Entities\DeviceApi deviceApi(array $attributes = [])
 */
class Thingsboard
{
    public function __call(string $name, array $arguments)
    {
        $class = '\\JalalLinuX\\Tntity\\Facades\\Entities\\'.ucfirst($name);

        return $class::make(...$arguments);
    }
}
