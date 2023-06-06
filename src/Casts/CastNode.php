<?php

namespace JalalLinuX\Thingsboard\Casts;

use JalalLinuX\Thingsboard\Infrastructure\RuleChain\Node;
use Vkovic\LaravelCustomCasts\CustomCastBase;

class CastNode extends CustomCastBase
{
    public function setAttribute($value): ?array
    {
        if (is_null($value)) {
            return null;
        }

        if ($value instanceof Node) {
            return $value->toArray();
        }

        return $value;
    }

    public function castAttribute($value): ?Node
    {
        return is_null($value) ? null : new Node($value);
    }
}
