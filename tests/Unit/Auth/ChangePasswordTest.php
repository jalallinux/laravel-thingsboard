<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Auth;

use JalalLinuX\Thingsboard\Enums\ThingsboardUserAuthority;
use JalalLinuX\Thingsboard\Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    public function testSuccess()
    {
        [$targetRole, $newPassword] = [ThingsboardUserAuthority::CUSTOMER_USER(), '123456789'];

        $user = $this->thingsboardUser($targetRole);
        $result = thingsboard($user)->auth()->changePassword($user->getThingsboardPasswordAttribute(), $newPassword);
        self::assertTrue($result);

        $newUser = $this->thingsboardUser($targetRole, $user->getThingsboardEmailAttribute(), $newPassword);
        $result = thingsboard($newUser)->auth()->changePassword($newPassword, $user->getThingsboardPasswordAttribute());
        self::assertTrue($result);
    }
}
