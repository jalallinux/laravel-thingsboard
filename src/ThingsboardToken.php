<?php

namespace JalalLinuX\Thingsboard;

use Illuminate\Http\Client\Response;

class ThingsboardToken
{
    public ?string $scope;
    public string $token;
    public string $refreshToken;

    public function __construct(Response $response)
    {
        $this->scope = $response->json('scope');
        $this->token = $response->json('token');
        $this->refreshToken = $response->json('refreshToken');
    }

    public function token(string $key = null, $default = null)
    {
        return data_get(decodeJWTToken($this->token), $key, $default);
    }

    public function refreshToken(string $key = null, $default = null)
    {
        return data_get(decodeJWTToken($this->refreshToken), $key, $default);
    }
}
