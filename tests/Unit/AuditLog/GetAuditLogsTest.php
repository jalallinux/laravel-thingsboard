<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\AuditLog;

use JalalLinuX\Thingsboard\Entities\AuditLog;
use JalalLinuX\Thingsboard\Enums\EnumAuditLogActionStatus;
use JalalLinuX\Thingsboard\Enums\EnumAuditLogActionType;
use JalalLinuX\Thingsboard\Enums\EnumAuditLogSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumSortOrder;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetAuditLogsTest extends TestCase
{
    public function testCorrectLoginLog()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        thingsboard()->auth()->login($tenantUser->getThingsboardEmailAttribute(), $tenantUser->getThingsboardPasswordAttribute());

        sleep(2);
        $loginLog = thingsboard($tenantUser)->auditLog()->getAuditLogs(
            PaginationArguments::make(0, 1, EnumAuditLogSortProperty::CREATED_TIME(), EnumSortOrder::DESC()), now()->subMinute()
        )->data()->first();

        $this->assertInstanceOf(AuditLog::class, $loginLog);
        $this->assertEquals(EnumAuditLogActionType::LOGIN(), $loginLog->actionType);
        $this->assertGreaterThan($loginLog->createdTime, now()->getPreciseTimestamp(3));
        $this->assertEquals($tenantUser->getThingsboardEmailAttribute(), $loginLog->userName);
        $this->assertEquals(EnumAuditLogActionStatus::SUCCESS(), $loginLog->actionStatus);
    }

    public function testFailedLoginLog()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        try {
            thingsboard()->auth()->login($tenantUser->getThingsboardEmailAttribute(), $tenantUser->getThingsboardPasswordAttribute());
            thingsboard()->auth()->login($tenantUser->getThingsboardEmailAttribute(), $this->faker->password);
        } catch (\Exception $exception) {
        }

        sleep(1);
        $loginLog = thingsboard($tenantUser)->auditLog()->getAuditLogs(
            PaginationArguments::make(0, 1, EnumAuditLogSortProperty::CREATED_TIME(), EnumSortOrder::DESC()), now()->subMinute()
        )->data()->first();

        $this->assertInstanceOf(AuditLog::class, $loginLog);
        $this->assertEquals(EnumAuditLogActionType::LOGIN(), $loginLog->actionType);
        $this->assertGreaterThan($loginLog->createdTime, now()->getPreciseTimestamp(3));
        $this->assertEquals($tenantUser->getThingsboardEmailAttribute(), $loginLog->userName);
        $this->assertEquals(EnumAuditLogActionStatus::FAILURE(), $loginLog->actionStatus);
    }
}
