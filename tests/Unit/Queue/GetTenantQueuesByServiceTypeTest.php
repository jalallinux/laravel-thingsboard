<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Queue;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumQueueServiceType;
use JalalLinuX\Thingsboard\Enums\EnumQueueSortProperty;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetTenantQueuesByServiceTypeTest extends TestCase
{
    public function testPaginationData()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $pagination = $this->randomPagination(EnumQueueSortProperty::class);

        $queues = thingsboard($user)->queue()->getTenantQueuesByServiceType(
            $pagination, $this->faker->randomElement(EnumQueueServiceType::cases())
        );

        $this->assertEquals($pagination->page, $queues->currentPage());
        $this->assertEquals($pagination->pageSize, $queues->perPage());
        $this->assertEquals($pagination->sortOrder, $queues->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $queues->getOptions()['sortProperty']);
    }
}
