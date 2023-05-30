<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Auth;

use JalalLinuX\Thingsboard\Infrastructure\PasswordPolicy;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetUserPasswordPolicyTest extends TestCase
{
    public function testSuccess()
    {
        $policies = thingsboard()->auth()->getUserPasswordPolicy();
        $this->assertInstanceOf(PasswordPolicy::class, $policies);
    }
}
