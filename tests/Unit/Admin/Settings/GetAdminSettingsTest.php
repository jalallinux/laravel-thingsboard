<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Admin\Settings;

use JalalLinuX\Thingsboard\Enums\EnumAdminSettingsKey;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetAdminSettingsTest extends TestCase
{
    public function testGetAdminSettingsSuccess()
    {
        $adminUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());
        $settings = thingsboard($adminUser)->adminSettings()->getAdminSettings(EnumAdminSettingsKey::GENERAL());
        $this->assertEquals($settings['key'], EnumAdminSettingsKey::GENERAL());
        $this->assertIsArray($settings['id']);
        $this->assertInstanceOf(Id::class, $settings['tenantId']);
    }
}
