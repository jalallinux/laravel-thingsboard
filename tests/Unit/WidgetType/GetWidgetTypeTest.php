<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\WidgetType;

use JalalLinuX\Thingsboard\Entities\WidgetType;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetWidgetTypeTest extends TestCase
{
    public function testStructure()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $bundleAlias = thingsboard($tenantUser)->widgetBundle()->getAllWidgetsBundles();
        $bundleAlias = $bundleAlias[$this->faker->numberBetween(0, count($bundleAlias) - 1)]->alias;
        $widgetAlias = thingsboard($tenantUser)->widgetType()->getBundleWidgetTypesInfos($bundleAlias);
        $widgetAlias = $widgetAlias[$this->faker->numberBetween(0, count($widgetAlias) - 1)]->alias;

        $widgetType = thingsboard($tenantUser)->widgetType()->getWidgetType($bundleAlias, $widgetAlias);

        $this->assertInstanceOf(WidgetType::class, $widgetType);
        $this->assertInstanceOf(Id::class, $widgetType->id);
        $this->assertInstanceOf(Id::class, $widgetType->tenantId);
        $this->assertEquals($bundleAlias, $widgetType->bundleAlias);
    }
}
