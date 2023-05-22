<?php

namespace JalalLinuX\Thingsboard\Tests\Unit;

use JalalLinuX\Thingsboard\Tests\TestCase;

class ExampleTest extends TestCase
{
    public function testExample()
    {
        self::assertNotEmpty(config('thingsboard'));
    }
}
