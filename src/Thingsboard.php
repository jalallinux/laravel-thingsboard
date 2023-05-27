<?php

namespace JalalLinuX\Thingsboard;

use DateTimeInterface;
use JalalLinuX\Thingsboard\Entities\Auth;
use JalalLinuX\Thingsboard\infrastructure\CacheHandler;
use JalalLinuX\Thingsboard\Interfaces\ThingsboardUser;

/**
 * @method Entities\Auth auth(array $attributes = [])
 * @method Entities\User user(array $attributes = [])
 * @method Entities\Device device(array $attributes = [])
 * @method Entities\DeviceProfile deviceProfile(array $attributes = [])
 * @method Entities\Tenant tenant(array $attributes = [])
 * @method Entities\Customer customer(array $attributes = [])
 * @method Entities\DeviceApi deviceApi(array $attributes = [])
 */
class Thingsboard
{
    private ?ThingsboardUser $withUser;

    public function __construct(ThingsboardUser $withUser = null)
    {
        $this->withUser = $withUser;
    }

    public function __call(string $name, array $arguments)
    {
        $class = '\\JalalLinuX\\Thingsboard\\Entities\\'.ucfirst($name);

        if (isset($this->withUser)) {
            return $class::instance(...$arguments)->withUser($this->withUser);
        }

        return $class::instance(...$arguments);
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

    public static function fetchUserToken(ThingsboardUser $user, bool $flush = false)
    {
        $mail = $user->getThingsboardEmailAttribute();

        if ($flush) {
            return Auth::instance()->login($mail, $user->getThingsboardPasswordAttribute())->token;
        }

        if ($token = CacheHandler::get(CacheHandler::tokenCacheKey($mail))) {
            return $token;
        }

        return Auth::instance()->login($mail, $user->getThingsboardPasswordAttribute())->token;
    }
}
