<?php

namespace JalalLinuX\Thingsboard\Interfaces;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;

interface ThingsboardUser
{
    public function getThingsboardEmailAttribute(): string;

    public function getThingsboardPasswordAttribute(): string;

    public function getThingsboardAuthorityAttribute(): EnumAuthority;
}
