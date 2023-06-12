<?php

namespace JalalLinuX\Thingsboard\Casts;

use JalalLinuX\Thingsboard\Infrastructure\RuleChain\RuleChainConnection;
use Vkovic\LaravelCustomCasts\CustomCastBase;

class CastRuleChainConnection extends CustomCastBase
{
    public function setAttribute($value): ?array
    {
        if (is_null($value)) {
            return null;
        }

        if ($value instanceof RuleChainConnection) {
            return $value->toArray();
        }

        return $value;
    }

    public function castAttribute($value): ?RuleChainConnection
    {
        return is_null($value) ? null : new RuleChainConnection($value);
    }
}
