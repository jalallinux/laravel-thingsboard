<?php

namespace JalalLinuX\Thingsboard\Interfaces;

use JalalLinuX\Thingsboard\Enums\UserRole;

interface ThingsboardUser
{
    public function getThingsboardEmailAttribute(): string;

    public function getThingsboardPasswordAttribute(): string;

    public function getThingsboardRoleAttribute(): UserRole;
}
