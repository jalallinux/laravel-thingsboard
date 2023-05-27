<?php

namespace JalalLinuX\Thingsboard\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumThingsboardSortOrder;
use JalalLinuX\Thingsboard\infrastructure\PaginationArguments;
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

    public function randomPagination(string $sortPropertyEnum, int $page = null, int $pageSize = null, EnumThingsboardSortOrder $sortOrder = null): PaginationArguments
    {
        return PaginationArguments::make(
            $page ?? $this->faker->numberBetween(1, 10),
            $pageSize ?? $this->faker->numberBetween(1, 10),
            $this->faker->randomElement($sortPropertyEnum::cases()),
            $sortOrder ?? $this->faker->randomElement(EnumThingsboardSortOrder::cases())
        );
    }

    public function thingsboardUser(EnumAuthority $role, string $mail = null, string $pass = null): ThingsboardUser
    {
        return new class($role, $mail, $pass) implements ThingsboardUser
        {
            private array $user;

            private ?string $mail;

            private ?string $pass;

            public function __construct(EnumAuthority $role, string $mail = null, string $pass = null)
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

            public function getThingsboardAuthorityAttribute(): EnumAuthority
            {
                return $this->user['role'];
            }
        };
    }
}
