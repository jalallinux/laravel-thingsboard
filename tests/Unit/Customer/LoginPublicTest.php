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
        $publicId = thingsboard($tenantUser)->customer()->getCustomers(
            PaginationArguments::make(0, 100, EnumCustomerSortProperty::TITLE(), EnumSortOrder::DESC(), 'Public')
        )->data()->first()->id->id;

        $token = thingsboard()->customer()->loginPublic($publicId);

        $this->assertInstanceOf(Token::class, $token);
        $this->assertEquals($publicId, $token->decode(EnumTokenType::ACCESS_TOKEN(), 'sub'));
        $this->assertTrue($token->decode(EnumTokenType::ACCESS_TOKEN(), 'isPublic'));
    }
}
