<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Enums\ThingsboardEntityType;
use JalalLinuX\Thingsboard\Interfaces\PasswordPolicy;
use JalalLinuX\Thingsboard\ThingsboardCacheHandler;
use JalalLinuX\Thingsboard\Tntity;

class Auth extends Tntity
{
    public function entityType(): ?ThingsboardEntityType
    {
        return null;
    }

    /**
     * Login method to get user JWT token data
     *
     * @author JalalLinuX
     *
     * @group *
     */
    public function login(string $mail, string $password): array
    {
        $tokens = $this->api(false)->post('auth/login', [
            'username' => $mail, 'password' => $password,
        ])->json();

        ThingsboardCacheHandler::updateToken($mail, $tokens['token']);

        return $tokens;
    }

    /**
     * Get current User
     *
     * @author JalalLinuX
     *
     * @group *
     */
    public function getUser(): User
    {
        return new User(
            $this->api()->get('auth/user')->json()
        );
    }

    /**
     * Change password for current User
     *
     * @author JalalLinuX
     *
     * @group *
     */
    public function changePassword(string $current, $new): bool
    {
        $changed = $this->api()->post('auth/changePassword', [
            'currentPassword' => $current, 'newPassword' => $new,
        ])->successful();

        if ($changed) {
            ThingsboardCacheHandler::forgetToken($this->_thingsboardUser->getThingsboardEmailAttribute());
        }

        return $changed;
    }

    /**
     * Get the current User password policy
     *
     * @author JalallinuX
     *
     * @group GUEST
     */
    public function getUserPasswordPolicy(): PasswordPolicy
    {
        return PasswordPolicy::fromArray($this->api(false)->get('noauth/userPasswordPolicy')->json());
    }
}
