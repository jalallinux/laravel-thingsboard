<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Auth;

use JalalLinuX\Thingsboard\Enums\UserRole;
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
        $user = $this->thingsboardUser(fake()->randomElement(UserRole::cases()));
        $tokens = thingsboard()->auth()->login($user->getThingsboardEmailAttribute(), $user->getThingsboardPasswordAttribute());

        $this->assertIsArray($tokens);
    }
}
