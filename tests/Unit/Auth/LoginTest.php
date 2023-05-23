<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Auth;

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
        $tokens = thingsboard()->auth()->login(config('thingsboard.rest.admin.mail'), config('thingsboard.rest.admin.pass'));
        $this->assertIsArray($tokens);
    }
}
