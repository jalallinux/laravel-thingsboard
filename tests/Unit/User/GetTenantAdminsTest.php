<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\User;

use JalalLinuX\Thingsboard\Entities\User;
use JalalLinuX\Thingsboard\Enums\ThingsboardAuthority;
use JalalLinuX\Thingsboard\Enums\ThingsboardEntityType;
use JalalLinuX\Thingsboard\Enums\UserSortProperty;
use JalalLinuX\Thingsboard\Tests\TestCase;
use JalalLinuX\Thingsboard\ThingsboardId;
use JalalLinuX\Thingsboard\ThingsboardPaginationArguments;

class GetTenantAdminsTest extends TestCase
{
    public function testTextSearch()
    {
        $user = $this->thingsboardUser(ThingsboardAuthority::SYS_ADMIN());

        $tenantId = thingsboard()->tenant()->withUser($user)->getTenants(ThingsboardPaginationArguments::make())->data()->first()->id->id;
        $tenantUsers = thingsboard()->user()->withUser($user)->getTenantAdmins(ThingsboardPaginationArguments::make(), $tenantId);

        $tenantUsers->data()->each(fn ($device) => $this->assertInstanceOf(User::class, $device));
    }

    public function testPaginationData()
    {
        $pagination = $this->randomPagination(UserSortProperty::class);
        $user = $this->thingsboardUser(ThingsboardAuthority::SYS_ADMIN());
        $tenantId = thingsboard()->tenant()->withUser($user)->getTenants(
            ThingsboardPaginationArguments::make()
        )->data()->first()->id->id;

        $devices = thingsboard()->user(['tenantId' => new ThingsboardId($tenantId, ThingsboardEntityType::TENANT())])->withUser($user)->getTenantAdmins(
            ThingsboardPaginationArguments::make(
                page: $pagination['page'], pageSize: $pagination['pageSize'],
                sortProperty: $pagination['sortProperty'], sortOrder: $pagination['sortOrder']
            )
        );

        $this->assertEquals($pagination['page'], $devices->paginator()->currentPage());
        $this->assertEquals($pagination['pageSize'], $devices->paginator()->perPage());
        $this->assertEquals($pagination['sortOrder'], $devices->paginator()->getOptions()['sortOrder']);
        $this->assertEquals($pagination['sortProperty'], $devices->paginator()->getOptions()['sortProperty']);
    }
}
