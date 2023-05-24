<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\DeviceApi;

use JalalLinuX\Thingsboard\Tests\TestCase;

class PostTelemetryTest extends TestCase
{
    public function testCorrectPayload()
    {
        $deviceToken = fake()->randomElement([
            'A1_TEST_TOKEN', 'A2_TEST_TOKEN', 'A3_TEST_TOKEN', 'B1_TEST_TOKEN', 'C1_TEST_TOKEN'
        ]);
        $result = thingsboard()->deviceApi()->postTelemetry([
            ['ts' => fake()->dateTimeBetween('-5 years')->getTimestamp() * 1000, 'values' => ['test-temperature' => fake()->numerify('##')]],
            ['ts' => fake()->dateTimeBetween('-5 years')->getTimestamp() * 1000, 'values' => ['test-humidity' => fake()->numerify('##')]],
        ], $deviceToken);
        $this->assertTrue($result);
    }

    public function testInvalidDeviceToken()
    {
        $this->expectExceptionCode(401);
        thingsboard()->deviceApi()->postTelemetry([
            ['ts' => fake()->dateTimeBetween('-5 years')->getTimestamp() * 1000, 'values' => ['test-temperature' => fake()->numerify('##')]],
            ['ts' => fake()->dateTimeBetween('-5 years')->getTimestamp() * 1000, 'values' => ['test-humidity' => fake()->numerify('##')]],
        ], fake()->slug);
    }

    public function testInvalidPayload()
    {
        $deviceToken = fake()->randomElement([
            'A1_TEST_TOKEN', 'A2_TEST_TOKEN', 'A3_TEST_TOKEN', 'B1_TEST_TOKEN', 'C1_TEST_TOKEN'
        ]);
        $this->expectExceptionCode(500);
        thingsboard()->deviceApi()->postTelemetry([
            ['ts' => fake()->dateTimeBetween('-5 years')->getTimestamp(), 'values' => ['test-temperature' => fake()->numerify('##')]],
            ['ts' => fake()->dateTimeBetween('-5 years')->getTimestamp() * 1000, 'values' => 'test-humidity'],
        ], $deviceToken);
    }
}
