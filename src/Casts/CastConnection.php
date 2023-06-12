<?php

namespace JalalLinuX\Thingsboard\Casts;

use JalalLinuX\Thingsboard\Infrastructure\RuleChain\Connection;
use Vkovic\LaravelCustomCasts\CustomCastBase;

class CastConnection extends CustomCastBase
{
    public function setAttribute($value): ?array
    {
        if (is_null($value)) {
            return null;
        }

        if ($value instanceof Connection) {
            return $value->toArray();
        }

        return $value;
    }

    public function castAttribute($value): ?Connection
    {
        return is_null($value) ? null : new Connection($value);
    }
}
