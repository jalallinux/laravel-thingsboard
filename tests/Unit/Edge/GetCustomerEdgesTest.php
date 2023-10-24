<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Edge;

use JalalLinuX\Thingsboard\Entities\Edge;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEdgeSortProperty;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetCustomerEdgesTest extends TestCase
{
    public function testStructure()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $customerId = thingsboard($tenantUser)->customer()->getCustomers(PaginationArguments::make())->collect()->random()->id->id;
        $edgeId = thingsboard($tenantUser)->edge()->saveEdge("- {$this->faker->sentence(3)}")->id->id;

        thingsboard($tenantUser)->edge()->assignEdgeToCustomer($customerId, $edgeId);

        $edges = thingsboard($tenantUser)->edge()->getCustomerEdges(
            $customerId, PaginationArguments::make()
        );

        $edges->collect()->each(function ($edge) {
            $this->assertInstanceOf(Edge::class, $edge);
        });

        thingsboard($tenantUser)->edge()->deleteEdge($edgeId);
    }

    public function testPaginationData()
    {
        $pagination = $this->randomPagination(EnumEdgeSortProperty::class);
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $customerId = thingsboard($tenantUser)->customer()->getCustomers(PaginationArguments::make())->collect()->random()->id->id;
        $edgeId = thingsboard($tenantUser)->edge()->saveEdge("- {$this->faker->sentence(3)}")->id->id;

        thingsboard($tenantUser)->edge()->assignEdgeToCustomer($customerId, $edgeId);
        $edges = thingsboard()->edge()->withUser($tenantUser)->getCustomerEdges($customerId, $pagination);

        $this->assertEquals($pagination->page, $edges->currentPage());
        $this->assertEquals($pagination->pageSize, $edges->perPage());
        $this->assertEquals($pagination->sortOrder, $edges->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $edges->getOptions()['sortProperty']);

        thingsboard($tenantUser)->edge()->deleteEdge($edgeId);
    }
}
