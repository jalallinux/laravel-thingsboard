<?php

namespace JalalLinuX\Thingsboard\Traits;

use JalalLinuX\Thingsboard\Infrastructure\Token;
use JalalLinuX\Thingsboard\Thingsboard;

trait ThingsboardUser
{
    public function getThingsboardTokenAttribute(): Token
    {
        return Thingsboard::fetchUserToken($this);
    }
}
