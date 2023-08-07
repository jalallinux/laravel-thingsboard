<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Admin\Settings;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Tests\TestCase;

class SaveAdminSettingsTest extends TestCase
{
    public function testSaveAdminSettingsSuccess()
    {
        $adminUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());
        $name = $this->faker->unique()->slug;
        $settings = thingsboard($adminUser)->adminSettings()->saveAdminSettings($name, [
            'TEST_STRING' => $this->faker->sentence,
            'TEST_NUMERIC' => $this->faker->numerify,
            'TEST_BOOL' => $this->faker->boolean,
            'TEST_ARRAY' => $this->faker->rgbColorAsArray,
        ]);

        $this->assertEquals($name, $settings->key);
        $this->assertIsArray($settings->id);
        $this->assertInstanceOf(Id::class, $settings->tenantId);
        $this->assertArrayHasKey('TEST_STRING', $settings->jsonValue);
        $this->assertArrayHasKey('TEST_NUMERIC', $settings->jsonValue);
        $this->assertArrayHasKey('TEST_BOOL', $settings->jsonValue);
        $this->assertArrayHasKey('TEST_ARRAY', $settings->jsonValue);
    }

    public function testUpdateAdminSettingsSuccess()
    {
        $adminUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());
        $settings = thingsboard($adminUser)->adminSettings()->getAdminSettings('general');

        $settings = $settings->saveAdminSettings('general', array_merge($settings->jsonValue, [
            'TEST_KEY' => $this->faker->sentence,
        ]));

        $this->assertEquals('general', $settings->key);
        $this->assertIsArray($settings->id);
        $this->assertInstanceOf(Id::class, $settings->tenantId);
        $this->assertArrayHasKey('TEST_KEY', $settings->jsonValue);

        $values = $settings->jsonValue;
        unset($values['TEST_KEY']);
        $settings->saveAdminSettings('general', $values);
    }

    public function testSaveAdminSettingsExists()
    {
        $this->expectExceptionCode(400);
        $this->expectExceptionMessageMatches('/exists/');

        $adminUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());
        thingsboard($adminUser)->adminSettings()->saveAdminSettings('general', [
            'TEST_STRING' => $this->faker->sentence,
            'TEST_NUMERIC' => $this->faker->numerify,
            'TEST_BOOL' => $this->faker->boolean,
            'TEST_ARRAY' => $this->faker->rgbColorAsArray,
        ]);
    }
}
