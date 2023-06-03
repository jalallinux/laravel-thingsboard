<?php

namespace JalalLinuX\Thingsboard\Casts;

use JalalLinuX\Thingsboard\Exceptions\Exception;
use JalalLinuX\Thingsboard\Infrastructure\TenantProfileData\Configuration\Configuration;
use JalalLinuX\Thingsboard\Infrastructure\TenantProfileData\ProfileData;
use JalalLinuX\Thingsboard\Infrastructure\TenantProfileData\QueueConfiguration\QueueConfiguration;
use Vkovic\LaravelCustomCasts\CustomCastBase;

class CastTenantProfileDataConfiguration extends CustomCastBase
{
    public function setAttribute($value): ?array
    {
        if (is_null($value)) {
            return null;
        }

        if ($value instanceof ProfileData) {
            return $value->toArray();
        }

        throw_if(! is_array($value) || ! array_key_exists('configuration', $value), new Exception('Attribute must have configuration key in array.'));

        return $value;
    }

    public function castAttribute($value): ?ProfileData
    {
        return is_null($value) ? null : new ProfileData(
            new Configuration($value['configuration']),
            is_null(@$value['queueConfiguration']) ? null : new QueueConfiguration($value['queueConfiguration'])
        );
    }
}
