<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Admin;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetSystemInfoTest extends TestCase
{
    public function testGetSystemInfoSuccess()
    {
        $adminUser =  $this->thingsboardUser(EnumAuthority::SYS_ADMIN());
        $systemInfos = thingsboard($adminUser)->adminSystemInfo()->getSystemInfo();
        $this->assertIsBool($systemInfos['monolith']);
        $this->assertIsArray($systemInfos['systemData']);
    }
}
