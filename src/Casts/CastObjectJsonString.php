<?php

namespace JalalLinuX\Thingsboard\Casts;

use Vkovic\LaravelCustomCasts\CustomCastBase;

class CastObjectJsonString extends CustomCastBase
{
    public function setAttribute($value)
    {
        if (empty($value)) {
            return new \stdClass();
        }

        if (is_string($value)) {
            $value = json_decode($value, true);
            return empty($value) ? new \stdClass() : $value;
        }

        return $value;
    }

    public function castAttribute($value)
    {
        return is_null($value) ? new \stdClass() : $value;
    }
}
