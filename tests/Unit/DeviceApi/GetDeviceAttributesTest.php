<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\DeviceApi;

use JalalLinuX\Thingsboard\Tests\TestCase;

class GetDeviceAttributesTest extends TestCase
{
    public function testCorrectDeviceToken()
    {
        $deviceToken = $this->faker->randomElement([
            'A1_TEST_TOKEN', 'A2_TEST_TOKEN', 'A3_TEST_TOKEN', 'B1_TEST_TOKEN', 'C1_TEST_TOKEN',
        ]);
        $attributes = thingsboard()->deviceApi()->getDeviceAttributes($deviceToken);
        $this->assertIsArray($attributes);
    }

    public function testInvalidDeviceToken()
    {
        $this->expectExceptionCode(401);

        $attributes = thingsboard()->deviceApi()->getDeviceAttributes($this->faker->slug);
        $this->assertIsArray($attributes);
    }
}
