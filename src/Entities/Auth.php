<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\CacheHandler;
use JalalLinuX\Thingsboard\Infrastructure\PasswordPolicy;
use JalalLinuX\Thingsboard\Infrastructure\Token;
use JalalLinuX\Thingsboard\Tntity;

class Auth extends Tntity
{
    public function entityType(): ?EnumEntityType
    {
        return null;
    }

    /**
     * Login method used to authenticate user and get JWT token data.
     * Value of the response token field can be used as X-Authorization header value
     * @param  string  $mail
     * @param  string  $password
     * @return Token
     * @author JalalLinuX
     * @group *
     */
    public function login(string $mail, string $password): Token
    {
        $tokens = $this->api(false)->post('auth/login', [
            'username' => $mail, 'password' => $password,
        ]);

        CacheHandler::updateToken($mail, $tokens->json('token'));

        return new Token($tokens);
    }

    /**
     * Get the information about the User which credentials are used to perform this REST API call.
     * @return User
     * @author JalalLinuX
     * @group *
     */
    public function getUser(): User
    {
        return new User(
            $this->api()->get('auth/user')->json()
        );
    }

    /**
     * Change the password for the User which credentials are used to perform this REST API call.
     * Be aware that previously generated JWT tokens will be still valid until they expire.
     * @param  string  $current
     * @param $new
     * @return bool
     * @author JalalLinuX
     * @group *
     */
    public function changePassword(string $current, $new): bool
    {
        $changed = $this->api(handleException: self::config('rest.exception.throw_bool_methods'))->post('auth/changePassword', [
            'currentPassword' => $current, 'newPassword' => $new,
        ])->successful();

        if ($changed) {
            CacheHandler::forgetToken($this->_thingsboardUser->getThingsboardEmailAttribute());
        }

        return $changed;
    }

    /**
     * API call to get the password policy for the password validation form(s).
     * @return PasswordPolicy
     * @author JalallinuX
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
     * @param  string  $activateToken
     * @param  string  $password
     * @param  bool  $sendActivationMail
     * @return Token
     * @author JalalLinuX
     * @group GUEST
     */
    public function activateUser(string $activateToken, string $password, bool $sendActivationMail = false): Token
    {
        return new Token(
            $this->api(false)->post('noauth/activate?sendActivationMail='.($sendActivationMail ? 'true' : 'false'), [
                'activateToken' => $activateToken,
                'password' => $password,
            ])
        );
    }
}
