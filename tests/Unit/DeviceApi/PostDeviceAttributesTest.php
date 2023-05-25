<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\DeviceApi;

use JalalLinuX\Thingsboard\Tests\TestCase;

class PostDeviceAttributesTest extends TestCase
{
    public function testCorrectPayload()
    {
        $deviceToken = $this->faker->randomElement([
            'A1_TEST_TOKEN', 'A2_TEST_TOKEN', 'A3_TEST_TOKEN', 'B1_TEST_TOKEN', 'C1_TEST_TOKEN',
        ]);
        $result = thingsboard()->deviceApi()->postDeviceAttributes([
            'test-temperature' => $this->faker->numerify('##'),
            'test-humidity' => $this->faker->numerify('##'),
        ], $deviceToken);
        $this->assertTrue($result);
    }

    public function testInvalidDeviceToken()
    {
        $this->expectExceptionCode(401);
        thingsboard()->deviceApi()->postDeviceAttributes([
            'test-temperature' => $this->faker->numerify('##'),
            'test-humidity' => $this->faker->numerify('##'),
        ], $this->faker->slug);
    }

    public function testInvalidPayload()
    {
        $deviceToken = $this->faker->randomElement([
            'A1_TEST_TOKEN', 'A2_TEST_TOKEN', 'A3_TEST_TOKEN', 'B1_TEST_TOKEN', 'C1_TEST_TOKEN',
        ]);
        $this->expectExceptionCode(500);
        thingsboard()->deviceApi()->postDeviceAttributes(['key'], $deviceToken);
    }
}
