<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Mqtt;

use JalalLinuX\Thingsboard\Tests\TestCase;

class TelemetryTest extends TestCase
{
    public function testSuccess()
    {
        $deviceToken = $this->faker->randomElement([
            'A1_TEST_TOKEN', 'A2_TEST_TOKEN', 'A3_TEST_TOKEN', 'B1_TEST_TOKEN', 'C1_TEST_TOKEN',
        ]);

        thingsboard()->mqtt($deviceToken)->telemetry([
            ['ts' => $this->faker->dateTimeBetween('-5 years')->getTimestamp() * 1000, 'values' => ['test-temperature' => $this->faker->numerify('##')]],
            ['ts' => $this->faker->dateTimeBetween('-5 years')->getTimestamp() * 1000, 'values' => ['test-humidity' => $this->faker->numerify('##')]],
        ]);

        $this->assertTrue(true);
    }
}
