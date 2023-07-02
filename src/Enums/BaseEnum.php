<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

abstract class BaseEnum extends Enum
{
    public function __construct($value = null)
    {
        if (! is_null($value)) {
            parent::__construct($value);
        }
    }

    public function setAttribute($value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        if ($value instanceof BaseEnum) {
            return $value->value;
        }

        return (string) $value;
    }

    public function castAttribute($value): ?BaseEnum
    {
        return is_null($value) ? null : static::from((string) $value);
    }
}
