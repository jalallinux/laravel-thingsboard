<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Tntity;

class Auth extends Tntity
{
    public function login(string $mail, string $password): array
    {
        return $this->api()->post('auth/login', [
            'username' => $mail, 'password' => $password,
        ])->json();
    }

    public function me(): User
    {
        return new User(
            $this->api(true)->get('auth/user')->json()
        );
    }

    public function changePassword(string $current, $new): bool
    {
        return $this->api()->post('auth/changePassword', [
            'currentPassword' => $current, 'newPassword' => $new,
        ])->successful();
    }
}
