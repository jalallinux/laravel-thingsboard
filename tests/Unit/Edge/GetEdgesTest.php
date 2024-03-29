<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Edge;

use Illuminate\Pagination\LengthAwarePaginator;
use JalalLinuX\Thingsboard\Entities\Edge;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEdgeSortProperty;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetEdgesTest extends TestCase
{
    public function testStructure()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $edges = thingsboard($user)->edge()->getEdges(PaginationArguments::make());

        self::assertInstanceOf(LengthAwarePaginator::class, $edges);

        $edges->collect()->each(fn ($edge) => $this->assertInstanceOf(Edge::class, $edge));
    }

    public function testPaginationData()
    {
        $sortProperty = $this->faker->randomElement(array_diff(EnumEdgeSortProperty::cases(), [EnumEdgeSortProperty::CUSTOMER_TITLE()]));
        $pagination = $this->randomPagination([$sortProperty]);
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $edges = thingsboard($user)->edge()->getEdges($pagination);

        $this->assertEquals($pagination->page, $edges->currentPage());
        $this->assertEquals($pagination->pageSize, $edges->perPage());
        $this->assertEquals($pagination->sortOrder, $edges->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $edges->getOptions()['sortProperty']);
    }
}
