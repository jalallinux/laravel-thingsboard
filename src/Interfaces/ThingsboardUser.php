<?php

namespace JalalLinuX\Thingsboard\Interfaces;

interface ThingsboardUser
{
    public function getThingsboardEmailAttribute(): string;

    public function getThingsboardPasswordAttribute(): string;
}
