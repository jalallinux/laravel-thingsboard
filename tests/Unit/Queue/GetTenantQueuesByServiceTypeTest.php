<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Queue;

use JalalLinuX\Thingsboard\Entities\Queue;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumQueueProcessingStrategyType;
use JalalLinuX\Thingsboard\Enums\EnumQueueServiceType;
use JalalLinuX\Thingsboard\Enums\EnumQueueSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumQueueSubmitStrategy;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetTenantQueuesByServiceTypeTest extends TestCase
{
    public function testPaginationData()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $pagination = $this->randomPagination(EnumQueueSortProperty::class, 1, 20);

        $queues = thingsboard($user)->queue()->getTenantQueuesByServiceType(
            $pagination, EnumQueueServiceType::TB_RULE_ENGINE()
        );

        $this->assertEquals($pagination->page, $queues->currentPage());
        $this->assertEquals($pagination->pageSize, $queues->perPage());
        $this->assertEquals($pagination->sortOrder, $queues->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $queues->getOptions()['sortProperty']);

        $queues->collect()->each(function (Queue $queue) {
            $this->assertInstanceOf(Queue::class, $queue);
            $this->assertInstanceOf(EnumQueueSubmitStrategy::class, $queue->submitStrategy);
            $this->assertInstanceOf(EnumQueueProcessingStrategyType::class, $queue->processingStrategy);
        });
    }
}
