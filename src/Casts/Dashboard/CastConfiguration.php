<?php

namespace JalalLinuX\Thingsboard\Casts\Dashboard;

use JalalLinuX\Thingsboard\Infrastructure\CustomCastBase;
use JalalLinuX\Thingsboard\Infrastructure\Dashboard\Configuration;

class CastConfiguration extends CustomCastBase
{
    public function setAttribute($value): ?array
    {
        if (is_null($value)) {
            return null;
        }

        if ($value instanceof Configuration) {
            return $value->toArray();
        }

        return $value;
    }

    public function castAttribute($value): ?Configuration
    {
        return is_null($value) ? null : new Configuration($value);
    }
}
