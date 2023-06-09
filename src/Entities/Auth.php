<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\PasswordPolicy;
use JalalLinuX\Thingsboard\Infrastructure\Token;
use JalalLinuX\Thingsboard\Thingsboard;
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
     *
     * @param  string|null  $mail
     * @param  string|null  $password
     * @return Token
     *
     * @author JalalLinuX
     *
     * @group
     */
    public function login(string $mail = null, string $password = null): Token
    {
        Thingsboard::exception((is_null($mail) || is_null($password)) && ! isset($this->_thingsboardUser), 'invalid_credentials');
        [$mail, $password] = [
            $mail ?? @$this->_thingsboardUser->getThingsboardEmailAttribute(),
            $password ?? @$this->_thingsboardUser->getThingsboardPasswordAttribute(),
        ];
        $response = $this->api(false)->post('auth/login', [
            'username' => $mail,
            'password' => $password,
        ]);

        Token::update($mail, $response->json('token'), $response->json('refreshToken'));

        return new Token($response->json('token'), $response->json('refreshToken'));
    }

    /**
     * Get the information about the User which credentials are used to perform this REST API call.
     *
     * @return User
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
     * Change the password for the User which credentials are used to perform this REST API call.
     * Be aware that previously generated JWT tokens will be still valid until they expire.
     *
     * @param  string  $current
     * @param $new
     * @return bool
     *
     * @author JalalLinuX
     *
     * @group *
     */
    public function changePassword(string $current, $new): bool
    {
        $changed = $this->api(handleException: config('thingsboard.rest.exception.throw_bool_methods'))->post('auth/changePassword', [
            'currentPassword' => $current, 'newPassword' => $new,
        ])->successful();

        if ($changed) {
            Token::forget($this->_thingsboardUser->getThingsboardEmailAttribute());
        }

        return $changed;
    }

    /**
     * API call to get the password policy for the password validation form(s).
     *
     * @return PasswordPolicy
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
     * @param  string  $activateToken
     * @param  string  $password
     * @param  bool  $sendActivationMail
     * @return Token
     *
     * @author JalalLinuX
     *
     * @group GUEST
     */
    public function activateUser(string $activateToken, string $password, bool $sendActivationMail = false): Token
    {
        $response = $this->api(false)->post('noauth/activate?sendActivationMail='.($sendActivationMail ? 'true' : 'false'), [
            'activateToken' => $activateToken,
            'password' => $password,
        ]);

        return new Token($response->json('token'), $response->json('refreshToken'));
    }
}
