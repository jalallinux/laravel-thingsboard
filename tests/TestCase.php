<?php

namespace JalalLinuX\Thingsboard\Tests;

use JalalLinuX\Thingsboard\LaravelThingsboardServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected $loadEnvironmentVariables = true;

    protected function getPackageProviders($app): array
    {
        return [
            LaravelThingsboardServiceProvider::class,
        ];
    }

    protected function getApplicationTimezone($app): string
    {
        return 'Asia/Tehran';
    }

    protected function setUp(): void
    {
        // Code before application created.
        parent::setUp();
        // Code after application created.
    }
}
