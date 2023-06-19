<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Customer;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumCustomerSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumSortOrder;
use JalalLinuX\Thingsboard\Enums\EnumTokenType;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Infrastructure\Token;
use JalalLinuX\Thingsboard\Tests\TestCase;

class LoginPublicTest extends TestCase
{
    public function testStructure()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $publicCustomer = thingsboard($tenantUser)->customer()->getCustomers(
            PaginationArguments::make(0, 100, EnumCustomerSortProperty::TITLE(), EnumSortOrder::DESC(), 'Public')
        )->collect()->first();

        if (! is_null($publicCustomer)) {
            $token = thingsboard()->customer()->loginPublic($publicCustomer->id->id);

            $this->assertInstanceOf(Token::class, $token);
            $this->assertEquals($publicCustomer->id->id, $token->decode(EnumTokenType::ACCESS_TOKEN(), 'sub'));
            $this->assertTrue($token->decode(EnumTokenType::ACCESS_TOKEN(), 'isPublic'));
        } else {
            $this->assertNull($publicCustomer);
        }
    }
}
