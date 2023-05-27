<?php

namespace JalalLinuX\Thingsboard\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use JalalLinuX\Thingsboard\Enums\ThingsboardSortOrder;
use JalalLinuX\Thingsboard\Enums\ThingsboardUserAuthority;
use JalalLinuX\Thingsboard\Interfaces\ThingsboardUser;
use JalalLinuX\Thingsboard\LaravelThingsboardServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use WithFaker;

    protected $loadEnvironmentVariables = true;

    protected function getPackageProviders($app): array
    {
        return [
            LaravelThingsboardServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        // Code before application created.
        parent::setUp();
        // Code after application created.
    }

    public function randomPagination(string $sortPropertyEnum, int $page = null, int $pageSize = null, ThingsboardSortOrder $sortOrder = null): array
    {
        return [
            'page' => $page ?? $this->faker->numberBetween(1, 10),
            'pageSize' => $pageSize ?? $this->faker->numberBetween(1, 10),
            'sortOrder' => $sortOrder ?? $this->faker->randomElement(ThingsboardSortOrder::cases()),
            'sortProperty' => $this->faker->randomElement($sortPropertyEnum::cases()),
        ];
    }

    public function thingsboardUser(ThingsboardUserAuthority $role, string $mail = null, string $pass = null): ThingsboardUser
    {
        return new class($role, $mail, $pass) implements ThingsboardUser
        {
            private array $user;

            private ?string $mail;

            private ?string $pass;

            public function __construct(ThingsboardUserAuthority $role, string $mail = null, string $pass = null)
            {
                $this->user = collect(config('thingsboard.rest.users'))->filter(fn ($user) => $role->equals($user['role']))->random();
                $this->mail = $mail;
                $this->pass = $pass;
            }

            public function getThingsboardEmailAttribute(): string
            {
                return $this->mail ?? $this->user['mail'];
            }

            public function getThingsboardPasswordAttribute(): string
            {
                return $this->pass ?? $this->user['pass'];
            }

            public function getThingsboardRoleAttribute(): ThingsboardUserAuthority
            {
                return $this->user['role'];
            }
        };
    }
}
