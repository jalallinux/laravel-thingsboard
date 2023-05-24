<?php

namespace JalalLinuX\Thingsboard\Tests;

use JalalLinuX\Thingsboard\Enums\ThingsboardSortOrder;
use JalalLinuX\Thingsboard\Enums\ThingsboardUserRole;
use JalalLinuX\Thingsboard\Interfaces\ThingsboardUser;
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

    protected function setUp(): void
    {
        // Code before application created.
        parent::setUp();
        // Code after application created.
    }

    public function randomPagination(string $sortPropertyEnum, int $page = null, int $pageSize = null, ThingsboardSortOrder $sortOrder = null): array
    {
        return [
            'page' => $page ?? fake()->numberBetween(1, 10),
            'pageSize' => $pageSize ?? fake()->numberBetween(1, 10),
            'sortOrder' => $sortOrder ?? fake()->randomElement(ThingsboardSortOrder::cases()),
            'sortProperty' => fake()->randomElement($sortPropertyEnum::cases()),
        ];
    }

    public function thingsboardUser(ThingsboardUserRole $role, string $mail = null, string $pass = null): ThingsboardUser
    {
        return new class($role, $mail, $pass) implements ThingsboardUser
        {
            private array $user;

            private ?string $mail;

            private ?string $pass;

            public function __construct(ThingsboardUserRole $role, string $mail = null, string $pass = null)
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

            public function getThingsboardRoleAttribute(): ThingsboardUserRole
            {
                return $this->user['role'];
            }
        };
    }
}
