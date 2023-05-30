<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Auth;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\Token;
use JalalLinuX\Thingsboard\Tests\TestCase;

class LoginTest extends TestCase
{
    public function testNonExistsCredentials()
    {
        $this->expectExceptionCode(401);
        thingsboard()->auth()->login('non-exists-email', 'invalid-password');
    }

    public function testCorrectCredentials()
    {
        $user = $this->thingsboardUser($this->faker->randomElement(EnumAuthority::cases()));
        $tokens = thingsboard()->auth()->login($user->getThingsboardEmailAttribute(), $user->getThingsboardPasswordAttribute());

        $this->assertInstanceOf(Token::class, $tokens);
    }
}
