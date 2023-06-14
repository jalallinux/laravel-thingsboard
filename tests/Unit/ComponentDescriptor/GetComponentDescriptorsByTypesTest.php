<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\ComponentDescriptor;

use JalalLinuX\Thingsboard\Entities\ComponentDescriptor;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumComponentDescriptorType;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetComponentDescriptorsByTypesTest extends TestCase
{
    public function testGetComponentDescriptorsByTypesSuccess()
    {
        $adminUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $components = thingsboard($adminUser)->componentDescriptor()->getComponentDescriptorsByTypes([
            EnumComponentDescriptorType::FILTER(),
            EnumComponentDescriptorType::ENRICHMENT(),
            EnumComponentDescriptorType::TRANSFORMATION(),
            EnumComponentDescriptorType::ACTION(),
            EnumComponentDescriptorType::EXTERNAL(),
            EnumComponentDescriptorType::FLOW(),
        ]);

        array_map(fn($component) => $this->assertInstanceOf(ComponentDescriptor::class, $component), $components);
        array_map(function(ComponentDescriptor $component) {
            $this->assertArrayHasKey('clazz', $component->toArray());
        }, $components);

    }

    public function testEmptyComponentTypesArray()
    {
        $adminUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches("/componentTypes/");
        thingsboard($adminUser)->componentDescriptor()->getComponentDescriptorsByTypes([]);
    }
}
