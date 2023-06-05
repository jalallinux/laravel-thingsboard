<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\WidgetBundle;

use JalalLinuX\Thingsboard\Entities\WidgetBundle;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\Base64Image;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetWidgetsBundleByIdTest extends TestCase
{
    public function testCorrectUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $widgetBundleId = thingsboard($tenantUser)->widgetBundle()->getAllWidgetsBundles()[0]->id->id;
        $widgetBundle = thingsboard($tenantUser)->widgetBundle()->getWidgetsBundleById($widgetBundleId);

        $this->assertInstanceOf(WidgetBundle::class, $widgetBundle);
        $this->assertInstanceOf(Base64Image::class, $widgetBundle->image);
        $this->assertTrue($widgetBundle->systematic);
        $this->assertEquals('png', $widgetBundle->image->extension());
    }
}
