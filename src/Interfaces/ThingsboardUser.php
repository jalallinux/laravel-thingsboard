<?php

namespace JalalLinuX\Tntity\Interfaces;

interface ThingsboardUser
{
    public function getThingsboardEmailAttribute(): string;

    public function getThingsboardPasswordAttribute(): string;
}
