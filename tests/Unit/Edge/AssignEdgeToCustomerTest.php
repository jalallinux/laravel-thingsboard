<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Edge;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class AssignEdgeToCustomerTest extends TestCase
{
    public function testStructure()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $customerId = thingsboard($tenantUser)->customer()->getCustomers(PaginationArguments::make())->collect()->random()->id->id;
        $edgeId = thingsboard($tenantUser)->edge()->saveEdge("- {$this->faker->sentence(3)}")->id->id;

        $edge = thingsboard($tenantUser)->edge()->assignEdgeToCustomer($customerId, $edgeId);
        thingsboard($tenantUser)->edge()->unassignEdgeFromCustomer($edgeId);
        thingsboard($tenantUser)->edge()->deleteEdge($edgeId);

        $this->assertEquals($edgeId, $edge->id->id);

        $this->assertEquals(EnumEntityType::CUSTOMER()->value, $edge->customerId->entityType);
        $this->assertEquals($customerId, $edge->customerId->id);
    }
}
