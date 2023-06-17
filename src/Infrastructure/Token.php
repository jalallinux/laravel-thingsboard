<?php

namespace JalalLinuX\Thingsboard\Infrastructure;

use JalalLinuX\Thingsboard\Thingsboard;

class Token
{
    public string $accessToken;

    public ?string $refreshToken;

    public function __construct(string $accessToken, string $refreshToken = null)
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
        Thingsboard::exception(!isset($this->refreshToken), 'not_set', [
            'attribute' => 'refresh token'
        ]);
        return data_get(decodeJWTToken($this->refreshToken), $key, $default);
    }
}
