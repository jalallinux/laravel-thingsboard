<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Tntity;

class Auth extends Tntity
{
    /**
     * @param string $mail
     * @param string $password
     * @return array
     * @author JalalLinuX
     * @group *
     */
    public function login(string $mail, string $password): array
    {
        return $this->api()->post('auth/login', [
            'username' => $mail, 'password' => $password,
        ])->json();
    }

    /**
     * @return User
     * @author JalalLinuX
     * @group *
     */
    public function me(): User
    {
        return new User(
            $this->api(true)->get('auth/user')->json()
        );
    }

    /**
     * @param string $current
     * @param $new
     * @return bool
     * @author JalalLinuX
     * @group *
     */
    public function changePassword(string $current, $new): bool
    {
        return $this->api(true)->post('auth/changePassword', [
            'currentPassword' => $current, 'newPassword' => $new,
        ])->successful();
    }
}
