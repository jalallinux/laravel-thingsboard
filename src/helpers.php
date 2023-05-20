<?php

use JalalLinuX\Tntity\Provider\ThingsboardLaravelServiceProvider;

if (! function_exists('isJsonString')) {
    function isJsonString(string $string): bool
    {
        return is_array(json_decode($string, true));
    }
}

if (! function_exists('isArrayAssoc')) {
    function isArrayAssoc(array $arr): bool
    {
        if ([] === $arr) {
            return false;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}

if (! function_exists('containerInstanceName')) {
    function containerInstanceName(string $name, string $type): string
    {
        return ThingsboardLaravelServiceProvider::CONTAINER_INSTANCE_PREFIX . ".{$type}.{$name}";
    }
}
