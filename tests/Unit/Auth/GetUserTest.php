<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Auth;

use JalalLinuX\Thingsboard\Entities\User;
use JalalLinuX\Thingsboard\Enums\ThingsboardEntityType;
use JalalLinuX\Thingsboard\Enums\ThingsboardUserRole;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetUserTest extends TestCase
{
    public function testWithoutUser()
    {
        $this->expectExceptionCode(401);
        thingsboard()->auth()->getUser();
    }

    public function testCorrectUser()
    {
        $user = thingsboard()->auth()->withUser($this->thingsboardUser(fake()->randomElement(ThingsboardUserRole::cases())))->getUser();

        $this->assertInstanceOf(User::class, $user);
        $this->assertTrue(ThingsboardEntityType::USER()->equals($user->id->entityType));
        $this->assertNotEmpty($user->name);
        $this->assertNotEmpty($user->createdTime);
    }
}
