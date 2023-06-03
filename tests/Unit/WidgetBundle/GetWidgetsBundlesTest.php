<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\WidgetBundle;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\Base64Image;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetWidgetsBundlesTest extends TestCase
{
    public function testTextSearch()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $widgetBundles = thingsboard($tenantUser)->widgetBundle()->getWidgetsBundles(PaginationArguments::make(textSearch: 'gauges'))->data();

        $this->assertCount(2, $widgetBundles);
        $this->assertInstanceOf(Base64Image::class, $widgetBundles->first()->image);
        $this->assertEquals('png', $widgetBundles->first()->image->extension());
        $this->assertInstanceOf(Id::class, $widgetBundles->first()->id);
        $this->assertInstanceOf(Id::class, $widgetBundles->first()->tenantId);
        $this->assertStringContainsString('gauges', $widgetBundles->first()->name);
        $this->assertStringContainsString('gauges', $widgetBundles->first()->alias);
        $this->assertStringContainsString('gauges', $widgetBundles->first()->title);
    }
}
