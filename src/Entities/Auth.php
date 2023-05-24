<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\ThingsboardCacheHandler;
use JalalLinuX\Thingsboard\Tntity;

class Auth extends Tntity
{
    /**
     * Login method to get user JWT token data
     * @param string $mail
     * @param string $password
     * @return array
     * @author JalalLinuX
     * @group *
     */
    public function login(string $mail, string $password): array
    {
        $tokens = $this->api()->post('auth/login', [
            'username' => $mail, 'password' => $password,
        ])->json();

        ThingsboardCacheHandler::updateToken($mail, $tokens['token']);

        return $tokens;
    }

    /**
     * Get current User
     * @return User
     * @author JalalLinuX
     * @group *
     */
    public function getUser(): User
    {
        return new User(
            $this->api(true)->get('auth/user')->json()
        );
    }

    /**
     * Change password for current User
     * @param string $current
     * @param $new
     * @return bool
     * @author JalalLinuX
     * @group *
     */
    public function changePassword(string $current, $new): bool
    {
        $changed = $this->api(true)->post('auth/changePassword', [
            'currentPassword' => $current, 'newPassword' => $new,
        ])->successful();

        if ($changed) {
            ThingsboardCacheHandler::forgetToken($this->_thingsboardUser->getThingsboardEmailAttribute());
        }

        return $changed;
    }
}
