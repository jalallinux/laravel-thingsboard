<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Auth;

use JalalLinuX\Thingsboard\Entities\User;
use JalalLinuX\Thingsboard\Enums\ThingsboardUserRole;
use JalalLinuX\Thingsboard\Tests\TestCase;

class MeTest extends TestCase
{
    public function testWithoutUser()
    {
        $this->expectExceptionCode(401);
        thingsboard()->auth()->me();
    }

    public function testCorrectUser()
    {
        $user = thingsboard()->auth()->withUser($this->thingsboardUser(fake()->randomElement(ThingsboardUserRole::cases())))->me();

        $this->assertInstanceOf(User::class, $user);
        $this->assertNotEmpty($user->id);
        $this->assertNotEmpty($user->name);
        $this->assertNotEmpty($user->createdTime);
    }
}
