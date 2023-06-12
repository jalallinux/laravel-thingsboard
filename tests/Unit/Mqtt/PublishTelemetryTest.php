<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Mqtt;

use JalalLinuX\Thingsboard\Tests\TestCase;

class PublishTelemetryTest extends TestCase
{
    public function testStructure()
    {
        $deviceToken = $this->faker->randomElement([
            'A1_TEST_TOKEN', 'A2_TEST_TOKEN', 'A3_TEST_TOKEN', 'B1_TEST_TOKEN', 'C1_TEST_TOKEN',
        ]);

        $result = thingsboard()->mqtt($deviceToken)->publishTelemetry([
            ['ts' => $this->faker->dateTimeBetween('-5 years')->getTimestamp() * 1000, 'values' => ['test-temperature' => $this->faker->numerify('##')]],
            ['ts' => $this->faker->dateTimeBetween('-5 years')->getTimestamp() * 1000, 'values' => ['test-humidity' => $this->faker->numerify('##')]],
        ]);

        $this->assertTrue($result);
    }
}
