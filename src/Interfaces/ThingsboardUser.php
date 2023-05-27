<?php

namespace JalalLinuX\Thingsboard\Interfaces;

use JalalLinuX\Thingsboard\Enums\ThingsboardAuthority;

interface ThingsboardUser
{
    public function getThingsboardEmailAttribute(): string;

    public function getThingsboardPasswordAttribute(): string;

    public function getThingsboardRoleAttribute(): ThingsboardAuthority;
}
