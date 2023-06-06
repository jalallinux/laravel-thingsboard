<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Dashboard;

use Illuminate\Support\Carbon;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetServerTimeTest extends TestCase
{
    public function testStructure()
    {
        $tenantUser = $this->thingsboardUser($this->faker->randomElement(EnumAuthority::cases()));
        $serverTime = thingsboard($tenantUser)->dashboard()->getServerTime();

        $this->assertInstanceOf(Carbon::class, $serverTime);
    }
}
