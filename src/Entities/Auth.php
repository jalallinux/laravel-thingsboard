<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\CacheHandler;
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

        CacheHandler::updateToken($mail, $tokens['token']);

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
            CacheHandler::forgetToken($this->_thingsboardUser->getThingsboardEmailAttribute());
        }

        return $changed;
    }
}
