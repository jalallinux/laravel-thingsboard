<?php

namespace JalalLinuX\Thingsboard\Infrastructure;

use Illuminate\Support\Carbon;
use JalalLinuX\Thingsboard\Enums\EnumTokenType;

class Token
{
    private string $accessToken;

    private string $refreshToken;

    public function __construct(string $accessToken, string $refreshToken)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
    }

    public function token(string $key = null, $default = null)
    {
        return data_get(decodeJWTToken($this->accessToken), $key, $default);
    }

    public function refreshToken(string $key = null, $default = null)
    {
        return data_get(decodeJWTToken($this->refreshToken), $key, $default);
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public static function cacheKey(string $mail, EnumTokenType $tokenType): string
    {
        return "users_{$mail}_{$tokenType->value}";
    }

    public static function update(string $mail, string $accessToken, string $refreshToken): self
    {
        $accessExpire = Carbon::createFromTimestamp(decodeJWTToken($accessToken, 'exp'))->subMinutes(5);
        $refreshExpire = Carbon::createFromTimestamp(decodeJWTToken($refreshToken, 'exp'))->subMinutes(5);

        CacheHandler::set(self::cacheKey($mail, EnumTokenType::ACCESS_TOKEN()), $accessToken, $accessExpire);
        CacheHandler::set(self::cacheKey($mail, EnumTokenType::REFRESH_TOKEN()), $refreshToken, $refreshExpire);

        return new self($accessToken, $refreshToken);
    }

    public static function forget(string $mail): bool
    {
        return CacheHandler::forget(self::cacheKey($mail, EnumTokenType::ACCESS_TOKEN()))
            && CacheHandler::forget(self::cacheKey($mail, EnumTokenType::REFRESH_TOKEN()));
    }

    public static function retrieve(string $mail): ?Token
    {
        [$accessToken, $refreshToken] = [
            CacheHandler::get(self::cacheKey($mail, EnumTokenType::ACCESS_TOKEN())),
            CacheHandler::get(self::cacheKey($mail, EnumTokenType::REFRESH_TOKEN())),
        ];

        if (is_null($accessToken) || is_null($refreshToken)) {
            return null;
        }

        return new Token($accessToken, $refreshToken);
    }
}
