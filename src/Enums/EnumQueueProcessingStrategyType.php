<?php

namespace JalalLinuX\Thingsboard\Enums;

/**
 * @method static self RETRY_ALL()
 * @method static self RETRY_FAILED()
 * @method static self RETRY_FAILED_AND_TIMED_OUT()
 * @method static self RETRY_TIMED_OUT()
 * @method static self SKIP_ALL_FAILURES()
 * @method static self SKIP_ALL_FAILURES_AND_TIMED_OUT()
 */
class EnumQueueProcessingStrategyType extends BaseEnum
{
    protected static function values(): array
    {
        return [
            'RETRY_ALL' => 'RETRY_ALL',
            'RETRY_FAILED' => 'RETRY_FAILED',
            'RETRY_FAILED_AND_TIMED_OUT' => 'RETRY_FAILED_AND_TIMED_OUT',
            'RETRY_TIMED_OUT' => 'RETRY_TIMED_OUT',
            'SKIP_ALL_FAILURES' => 'SKIP_ALL_FAILURES',
            'SKIP_ALL_FAILURES_AND_TIMED_OUT' => 'SKIP_ALL_FAILURES_AND_TIMED_OUT',
        ];
    }

    protected static function labels(): array
    {
        return [
            'RETRY_ALL' => 'Retry all',
            'RETRY_FAILED' => 'Retry failed',
            'RETRY_FAILED_AND_TIMED_OUT' => 'Retry failed and timed out',
            'RETRY_TIMED_OUT' => 'Retry timed out',
            'SKIP_ALL_FAILURES' => 'Skip all failures',
            'SKIP_ALL_FAILURES_AND_TIMED_OUT' => 'Skip all failures and timed out',
        ];
    }
}
