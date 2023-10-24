<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Edge;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class UnAssignEdgeFromCustomerTest extends TestCase
{
    public function testStructure()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $customerId = thingsboard($tenantUser)->customer()->getCustomers(PaginationArguments::make(textSearch: 'Customer'))->collect()->random()->id->id;
        $edgeId = thingsboard($tenantUser)->edge()->saveEdge("- {$this->faker->sentence(3)}")->id->id;

        $edge = thingsboard($tenantUser)->edge()->assignEdgeToCustomer($customerId, $edgeId);

        $this->assertEquals($edgeId, $edge->id->id);
        $this->assertEquals(EnumEntityType::CUSTOMER()->value, $edge->customerId->entityType);
        $this->assertEquals($customerId, $edge->customerId->id);

        $edge = thingsboard($tenantUser)->edge()->unassignEdgeFromCustomer($edgeId);

        $this->assertEquals($edgeId, $edge->id->id);
        $this->assertEquals(config('thingsboard.default.customer_id'), $edge->customerId->id);

        $edge->deleteEdge();
    }
}
