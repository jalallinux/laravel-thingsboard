<?php

use JalalLinuX\Thingsboard\Thingsboard;

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

if (! function_exists('thingsboard')) {
    function thingsboard(): Thingsboard
    {
        return new Thingsboard;
    }
}

if (! function_exists('decodeJWTToken')) {
    function decodeJWTToken(string $jwtToken, string $key = null, $default = null)
    {
        $sections = explode('.', $jwtToken);
        if (count($sections) != 3) {
            return null;
        }
        [$headb64, $bodyb64, $cryptob64] = $sections;
        $input = $bodyb64;
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padLength = 4 - $remainder;
            $input .= str_repeat('=', $padLength);
        }
        $input = (base64_decode(strtr($input, '-_', '+/')));

        if (version_compare(PHP_VERSION, '5.4.0', '>=') && ! (defined('JSON_C_VERSION') && PHP_INT_SIZE > 4)) {
            $obj = json_decode($input, true, 512, JSON_BIGINT_AS_STRING);
        } else {
            $max_int_length = strlen((string) PHP_INT_MAX) - 1;
            $json_without_bigints = preg_replace('/:\s*(-?\d{'.$max_int_length.',})/', ': "$1"', $input);
            $obj = json_decode($json_without_bigints, true);
        }

        return data_get($obj, $key, $default);
    }
}
