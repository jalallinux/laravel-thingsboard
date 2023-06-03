<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\WidgetBundle;

use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Entities\WidgetBundle;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Tests\TestCase;

class SaveAndDeleteWidgetsBundleTest extends TestCase
{
    public function testSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $widgetBundle = thingsboard($tenantUser)->widgetBundle()->saveWidgetsBundle($title = rtrim($this->faker->sentence(3), '.'));

        $this->assertInstanceOf(WidgetBundle::class, $widgetBundle);
        $this->assertEquals(Str::slug($title, '_'), $widgetBundle->alias);
        $this->assertEquals($title, $widgetBundle->name);
        $this->assertEquals($title, $widgetBundle->title);
        $this->assertFalse($widgetBundle->systematic);

        $deleted = $widgetBundle->deleteWidgetsBundle();
        $this->assertTrue($deleted);
    }
}
