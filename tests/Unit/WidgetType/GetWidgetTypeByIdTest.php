<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\WidgetType;

use JalalLinuX\Thingsboard\Entities\WidgetType;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\WidgetType\Descriptor;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetWidgetTypeByIdTest extends TestCase
{
    public function testStructure()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $bundleAlias = thingsboard($tenantUser)->widgetBundle()->getAllWidgetsBundles();
        $bundleAlias = $bundleAlias[$this->faker->numberBetween(0, count($bundleAlias) - 1)]->alias;
        $widgetTypeId = collect(thingsboard($tenantUser)->widgetType()->getBundleWidgetTypesInfos($bundleAlias))->random()->id->id;
        $widgetType = thingsboard($tenantUser)->widgetType()->getWidgetTypeById($widgetTypeId);

        $this->assertInstanceOf(WidgetType::class, $widgetType);
        $this->assertEquals($widgetTypeId, $widgetType->id->id);
        $this->assertInstanceOf(Descriptor::class, $widgetType->descriptor);
    }
}
