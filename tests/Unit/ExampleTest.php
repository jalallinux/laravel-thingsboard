<?php

namespace JalalLinuX\Tntity\Tests\Unit;

use JalalLinuX\Tntity\Tests\TestCase;

class ExampleTest extends TestCase
{
    public function testExample()
    {
        self::assertNotEmpty(config('thingsboard'));
    }
}
