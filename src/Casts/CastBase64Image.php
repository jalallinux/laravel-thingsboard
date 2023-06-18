<?php

namespace JalalLinuX\Thingsboard\Casts;

use JalalLinuX\Thingsboard\Infrastructure\Base64Image;
use JalalLinuX\Thingsboard\Infrastructure\CustomCastBase;

class CastBase64Image extends CustomCastBase
{
    public function setAttribute($value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        if ($value instanceof Base64Image) {
            return (string) $value;
        }

        return $value;
    }

    public function castAttribute($value): ?Base64Image
    {
        return is_null($value) ? null : new Base64Image($value);
    }
}
