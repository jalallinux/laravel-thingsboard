<?php

namespace JalalLinuX\Thingsboard\Casts;

use JalalLinuX\Thingsboard\Infrastructure\CustomCastBase;
use JalalLinuX\Thingsboard\Infrastructure\RuleChain\RuleNode;

class CastNode extends CustomCastBase
{
    public function setAttribute($value): ?array
    {
        if (is_null($value)) {
            return null;
        }

        if (is_array($value)) {
            $nodes = [];
            foreach ($value as $ruleNode) {
                $nodes[] = $this->castAttribute($ruleNode)->toArray();
            }

            return $nodes;
        }

        //        if ($value instanceof RuleNode) {
        //            return $value->toArray();
        //        }

        return $value;
    }

    public function castAttribute($value): ?RuleNode
    {
        return is_null($value) ? null : new RuleNode($value);
    }
}
