<?php

namespace JalalLinuX\Thingsboard\Enums;

/**
 * @method static self CREATED_TIME()
 * @method static self EXPIRATION_TIME()
 * @method static self REQUEST()
 * @method static self RESPONSE()
 */
class EnumRpcSortProperty extends BaseEnum
{
    protected static function values(): array
    {
        return [
            'CREATED_TIME' => 'createdTime',
            'EXPIRATION_TIME' => 'expirationTime',
            'REQUEST' => 'request',
            'RESPONSE' => 'response',
        ];
    }

    protected static function labels(): array
    {
        return [
            'CREATED_TIME' => 'Created time',
            'EXPIRATION_TIME' => 'Expiration time',
            'REQUEST' => 'Request',
            'RESPONSE' => 'Response',
        ];
    }
}
