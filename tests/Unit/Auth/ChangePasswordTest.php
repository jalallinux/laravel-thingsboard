<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Auth;

use JalalLinuX\Thingsboard\Enums\ThingsboardUserRole;
use JalalLinuX\Thingsboard\Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    public function testSuccess()
    {
        [$targetRole, $newPassword] = [ThingsboardUserRole::CUSTOMER_USER(), fake()->password];

        $user = $this->thingsboardUser($targetRole);
        $result = thingsboard()->auth()->withUser($user)->changePassword($user->getThingsboardPasswordAttribute(), $newPassword);
        self::assertTrue($result);

        $newUser = $this->thingsboardUser($targetRole, $user->getThingsboardEmailAttribute(), $newPassword);
        $result = thingsboard()->auth()->withUser($newUser)->changePassword($newPassword, $user->getThingsboardPasswordAttribute());
        self::assertTrue($result);
    }
}
