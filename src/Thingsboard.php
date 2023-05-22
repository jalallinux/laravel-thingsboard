<?php

namespace JalalLinuX\Tntity;

use DateTimeInterface;

/**
 * @method Facades\Entities\Auth auth(array $attributes = [])
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

    public static function cache(string $key, $value = null, DateTimeInterface $ttl = null)
    {
        $key = config('thingsboard.cache.prefix').$key;
        $cache = cache()->driver(config('thingsboard.cache.driver'));

        if (is_null($value)) {
            return $cache->get($key);
        }

        return $cache->put($key, $value, $ttl);
    }
}
