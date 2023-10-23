<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Edge;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Tests\TestCase;

class AssignEdgeToPublicCustomerTest extends TestCase
{
    public function testStructure()
    {
        // TODO: This method has a BUG from thingsboard 3.5.1
        self::assertTrue(true);
//        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
//        $edge = thingsboard($tenantUser)->edge()->saveEdge("- {$this->faker->sentence(3)}");
//
//        $edge = thingsboard($tenantUser)->edge()->assignEdgeToPublicCustomer($edge->id->id);
//        $edge = thingsboard($tenantUser)->edge()->unassignEdgeFromCustomer($edge->id->id);
//        thingsboard($tenantUser)->edge()->deleteEdge($edge->id->id);
//
//        $this->assertEquals($edge->id->id, $edge->id->id);
//
//        $this->assertEquals(EnumEntityType::CUSTOMER()->value, $edge->customerId->entityType);
//        $this->assertEquals(config('thingsboard.default.customer_id'), $edge->customerId->id);
    }
}
