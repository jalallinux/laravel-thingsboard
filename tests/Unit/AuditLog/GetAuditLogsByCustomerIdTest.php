<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\AuditLog;

use Illuminate\Pagination\LengthAwarePaginator;
use JalalLinuX\Thingsboard\Entities\AuditLog;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetAuditLogsByCustomerIdTest extends TestCase
{
    public function testCorrectLoginLog()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $customerId = thingsboard($tenantUser)->customer()->getCustomers(PaginationArguments::make())->collect()->first()->id->id;
        $logs = thingsboard($tenantUser)->auditLog()->getAuditLogsByCustomerId($customerId, PaginationArguments::make());

        $this->assertInstanceOf(LengthAwarePaginator::class, $logs);
        $logs->collect()->each(function ($log) {
            $this->assertInstanceOf(AuditLog::class, $log);
            $this->assertGreaterThan($log->createdTime, now()->getPreciseTimestamp(3));
        });
    }
}
