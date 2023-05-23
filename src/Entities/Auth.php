<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\ThingsboardCacheHandler;
use JalalLinuX\Thingsboard\Tntity;

class Auth extends Tntity
{
    /**
     * @author JalalLinuX
     *
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
     * @author JalalLinuX
     *
     * @group *
     */
    public function me(): User
    {
        return new User(
            $this->api(true)->get('auth/user')->json()
        );
    }

    /**
     * @author JalalLinuX
     *
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
