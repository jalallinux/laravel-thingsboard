<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Admin;

use JalalLinuX\Thingsboard\Entities\AdminUpdates;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Tests\TestCase;

class CheckUpdatesTest extends TestCase
{
    public function testCheckUpdatesSuccess()
    {
        $adminUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());
        $checkUpdates = thingsboard($adminUser)->adminUpdates()->checkUpdates();
        $this->assertIsBool($checkUpdates['updateAvailable']);
        $this->assertInstanceOf(AdminUpdates::class, $checkUpdates);
    }
}
