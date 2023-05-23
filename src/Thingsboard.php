<?php

namespace JalalLinuX\Thingsboard;

use DateTimeInterface;
use Illuminate\Support\Carbon;
use JalalLinuX\Thingsboard\Entities\Auth;
use JalalLinuX\Thingsboard\Interfaces\ThingsboardUser;

/**
 * @method Entities\Auth auth(array $attributes = [])
 * @method Entities\User user(array $attributes = [])
 * @method Entities\Device device(array $attributes = [])
 * @method Entities\DeviceApi deviceApi(array $attributes = [])
 */
class Thingsboard
{
    public function __call(string $name, array $arguments)
    {
        $class = '\\JalalLinuX\\Thingsboard\\Entities\\'.ucfirst($name);

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

    public static function fetchUserToken(ThingsboardUser $user)
    {
        $mail = $user->getThingsboardEmailAttribute();
        if ($token = Thingsboard::cache("users_{$mail}_token")) {
            return $token;
        }
        $token = Auth::instance()->login($mail, $user->getThingsboardPasswordAttribute())['token'];
        $expire = Carbon::createFromTimestamp(decodeJWTToken($token, 'exp'))->subMinutes(5);
        Thingsboard::cache("users_{$mail}_token", $token, $expire);

        return last(explode(' ', $token));
    }
}
