<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\User;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Interfaces\ThingsboardUser;
use JalalLinuX\Thingsboard\Tests\TestCase;

class DefaultUserTest extends TestCase
{
    public function testStructure()
    {
        $role = $this->faker->randomElement(EnumAuthority::cases());
        $user = thingsboard()->user()->defaultUser($role);

        $this->assertInstanceOf(ThingsboardUser::class, $user);
        $this->assertEquals($role->value, $user->getThingsboardAuthorityAttribute()->value);
    }
}
