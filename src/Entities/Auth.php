<?php

namespace JalalLinuX\Tntity\Entities;

class Auth extends Tntity
{
    public function login(string $mail, string $password): array
    {
        return $this->api()->post("auth/login", [
            'username' => $mail, 'password' => $password
        ])->json();
    }
}
