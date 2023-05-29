<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self DELIVERED()
 * @method static self EXPIRED()
 * @method static self FAILED()
 * @method static self QUEUED()
 * @method static self SENT()
 * @method static self SUCCESSFUL()
 * @method static self TIMEOUT()
 */
class EnumRpcStatus extends Enum
{
    protected static function values(): array
    {
        return [
            'DELIVERED' => 'DELIVERED',
            'EXPIRED' => 'EXPIRED',
            'FAILED' => 'FAILED',
            'QUEUED' => 'QUEUED',
            'SENT' => 'SENT',
            'SUCCESSFUL' => 'SUCCESSFUL',
            'TIMEOUT' => 'TIMEOUT',
        ];
    }

    protected static function labels(): array
    {
        return [
            'DELIVERED' => 'Delivered',
            'EXPIRED' => 'Expired',
            'FAILED' => 'Failed',
            'QUEUED' => 'Queued',
            'SENT' => 'Sent',
            'SUCCESSFUL' => 'Successful',
            'TIMEOUT' => 'Timeout',
        ];
    }
}
