<?php

namespace JalalLinuX\Thingsboard\Infrastructure;

use DateTimeInterface;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Carbon;

class CacheHandler
{
    public static function driver(): Repository
    {
        return cache()->driver(config('thingsboard.cache.driver'));
    }

    public static function prefix(): string
    {
        return config('thingsboard.cache.prefix');
    }

    public static function set(string $key, $value, DateTimeInterface $ttl = null): bool
    {
        return self::driver()->put(self::prefix().$key, $value, $ttl);
    }

    public static function get(string $key)
    {
        return self::driver()->get(self::prefix().$key);
    }

    public static function forget(string $key): bool
    {
        return self::driver()->forget(self::prefix().$key);
    }

    public static function tokenCacheKey(string $mail): string
    {
        return "users_{$mail}_token";
    }

    public static function updateToken(string $mail, string $token): bool
    {
        $expire = Carbon::createFromTimestamp(decodeJWTToken($token, 'exp'))->subMinutes(5);

        return self::set(self::tokenCacheKey($mail), $token, $expire);
    }

    public static function forgetToken(string $mail): bool
    {
        return self::forget(self::tokenCacheKey($mail));
    }
}
