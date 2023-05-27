<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Auth;

use JalalLinuX\Thingsboard\Entities\User;
use JalalLinuX\Thingsboard\Enums\ThingsboardEntityType;
use JalalLinuX\Thingsboard\Enums\ThingsboardAuthority;
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
        $user = thingsboard($this->thingsboardUser($this->faker->randomElement(ThingsboardAuthority::cases())))->auth()->getUser();

        $this->assertInstanceOf(User::class, $user);
        $this->assertTrue(ThingsboardEntityType::USER()->equals($user->id->entityType));
        $this->assertNotEmpty($user->name);
        $this->assertNotEmpty($user->createdTime);
    }
}
