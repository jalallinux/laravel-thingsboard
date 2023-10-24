<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Edge;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Tests\TestCase;

class IsEdgesSupportEnabledTest extends TestCase
{
    public function testStructure()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $result = thingsboard($tenantUser)->edge()->isEdgesSupportEnabled();
        $this->assertIsBool($result);
    }
}
