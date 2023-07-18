<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Tenant;

use Illuminate\Support\Arr;
use JalalLinuX\Thingsboard\Entities\Tenant;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Tests\TestCase;

class SaveTenantTest extends TestCase
{
    public function testCreateTenantSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());
        $defaultTenantProfileId = thingsboard($tenantUser)->tenantProfile()->getDefaultTenantProfileInfo()->id;
        $attributes = [
            'tenantProfileId' => $defaultTenantProfileId,
            'title' => $this->faker->sentence(3),
            'email' => $this->faker->unique()->safeEmail,
        ];
        $tenant = thingsboard($tenantUser)->tenant($attributes)->saveTenant();
        $tenant->deleteTenant();

        $this->assertInstanceOf(Tenant::class, $tenant);
        $this->assertInstanceOf(Id::class, $tenant->id);
        $this->assertEquals($attributes['title'], $tenant->title);
        $this->assertEquals($attributes['email'], $tenant->email);
        $this->assertEquals($attributes['tenantProfileId']->id, $tenant->tenantProfileId->id);
    }

    public function testRequiredProperty()
    {
        $user = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());

        $attributes = [
            'title' => $this->faker->sentence(3),
        ];
        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches('/title/');
        thingsboard($user)->tenant(Arr::except($attributes, 'title'))->saveTenant();
    }
}
