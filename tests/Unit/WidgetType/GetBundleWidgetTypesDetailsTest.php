<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\WidgetType;

use JalalLinuX\Thingsboard\Entities\WidgetType;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetBundleWidgetTypesDetailsTest extends TestCase
{
    public function testStructure()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $bundleAlias = thingsboard($tenantUser)->widgetBundle()->getAllWidgetsBundles();
        $bundleAlias = $bundleAlias[$this->faker->numberBetween(0, count($bundleAlias) - 1)]->alias;
        $widgetTypes = thingsboard($tenantUser)->widgetType()->getBundleWidgetTypesDetails($bundleAlias);

        $this->assertGreaterThanOrEqual(1, count($widgetTypes));
        collect($widgetTypes)->each(function ($widgetType) use ($bundleAlias) {
            $this->assertInstanceOf(WidgetType::class, $widgetType);
            $this->assertInstanceOf(Id::class, $widgetType->id);
            $this->assertInstanceOf(Id::class, $widgetType->tenantId);
            $this->assertEquals($bundleAlias, $widgetType->bundleAlias);
        });
    }
}
