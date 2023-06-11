<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Mqtt;

use JalalLinuX\Thingsboard\Tests\TestCase;

class PublishAttributeTest extends TestCase
{
    public function testStructure()
    {
        $deviceToken = $this->faker->randomElement([
            'A1_TEST_TOKEN', 'A2_TEST_TOKEN', 'A3_TEST_TOKEN', 'B1_TEST_TOKEN', 'C1_TEST_TOKEN',
        ]);

        $result = thingsboard()->mqtt($deviceToken)->publishAttribute([
            'TEST_KEY_1' => $this->faker->numerify,
            'TEST_KEY_2' => $this->faker->numerify,
        ]);

        $this->assertTrue($result);
    }
}
