<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Admin\Settings;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Tests\TestCase;

class SendTestMailTest extends TestCase
{
    public function testGetAdminSettingsSuccess()
    {
        $adminUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());
        $result = thingsboard($adminUser)->adminSettings()->sendTestMail( [
            "mailFrom" => $this->faker->safeEmail,
            "smtpProtocol" => "smtp",
            "smtpHost" => "sandbox.smtp.mailtrap.io",
            "smtpPort" => "2525",
            "timeout" => "10000",
            "enableTls" => false,
            "username" => "21378119e30462",
            "password" => "f12f504525a36d",
            "tlsVersion" => "TLSv1.2",
            "enableProxy" => false,
            "showChangePassword" => false
        ]);

        $this->assertTrue($result);
    }
}
