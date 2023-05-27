<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Enums\ThingsboardEntityType;
use JalalLinuX\Thingsboard\Interfaces\PasswordPolicy;
use JalalLinuX\Thingsboard\ThingsboardCacheHandler;
use JalalLinuX\Thingsboard\ThingsboardToken;
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
    public function login(string $mail, string $password): ThingsboardToken
    {
        $tokens = $this->api(false)->post('auth/login', [
            'username' => $mail, 'password' => $password,
        ]);

        ThingsboardCacheHandler::updateToken($mail, $tokens->json('token'));

        return new ThingsboardToken($tokens);
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

    /**
     * Checks the activation token and updates corresponding user password in the database.
     * Now the user may start using his password to login.
     * The response already contains the JWT activation and refresh tokens, to simplify the user activation flow and avoid asking user to input password again after activation.
     * If token is valid, returns the object that contains JWT access and refresh tokens.
     * If token is not valid, returns '404 Bad Request'.
     *
     * @author JalalLinuX
     *
     * @group GUEST
     */
    public function activateUser(string $activateToken, string $password, bool $sendActivationMail = false): ThingsboardToken
    {
        return new ThingsboardToken(
            $this->api(false)->post('noauth/activate?sendActivationMail='.($sendActivationMail ? 'true' : 'false'), [
                'activateToken' => $activateToken,
                'password' => $password,
            ])
        );
    }
}
