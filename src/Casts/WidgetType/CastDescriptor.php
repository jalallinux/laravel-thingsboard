<?php

namespace JalalLinuX\Thingsboard\Casts\WidgetType;

use JalalLinuX\Thingsboard\Infrastructure\CustomCastBase;
use JalalLinuX\Thingsboard\Infrastructure\WidgetType\Descriptor;

class CastDescriptor extends CustomCastBase
{
    public function setAttribute($value): ?array
    {
        if (is_null($value)) {
            return null;
        }

        if ($value instanceof Descriptor) {
            return $value->toArray();
        }

        return $value;
    }

    public function castAttribute($value): ?Descriptor
    {
        return is_null($value) ? null : new Descriptor($value);
    }
}
