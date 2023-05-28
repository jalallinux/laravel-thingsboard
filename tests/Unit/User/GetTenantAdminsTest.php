<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\User;

use JalalLinuX\Thingsboard\Entities\User;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumUserSortProperty;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetTenantAdminsTest extends TestCase
{
    public function testTextSearch()
    {
        $user = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());

        $tenantId = thingsboard()->tenant()->withUser($user)->getTenants(PaginationArguments::make())->data()->first()->id->id;
        $tenantUsers = thingsboard()->user()->withUser($user)->getTenantAdmins(PaginationArguments::make(), $tenantId);

        $tenantUsers->data()->each(fn ($user) => $this->assertInstanceOf(User::class, $user));
    }

    public function testPaginationData()
    {
        $pagination = $this->randomPagination(EnumUserSortProperty::class);
        $user = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());
        $tenantId = thingsboard()->tenant()->withUser($user)->getTenants(
            PaginationArguments::make()
        )->data()->first()->id->id;

        $devices = thingsboard()->user(['tenantId' => new Id($tenantId, EnumEntityType::TENANT())])->withUser($user)->getTenantAdmins($pagination);

        $this->assertEquals($pagination->page, $devices->paginator()->currentPage());
        $this->assertEquals($pagination->pageSize, $devices->paginator()->perPage());
        $this->assertEquals($pagination->sortOrder, $devices->paginator()->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $devices->paginator()->getOptions()['sortProperty']);
    }
}
