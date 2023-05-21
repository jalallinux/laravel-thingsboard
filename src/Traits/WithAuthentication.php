<?php

namespace JalalLinuX\Tntity\Traits;

use Illuminate\Http\Client\PendingRequest;

trait WithAuthentication
{
    public function __construct(...$args)
    {
        parent::__construct(...$args);
        $this->auth($this->defaultThingsboardToken());
    }

    private function defaultThingsboardToken(): string
    {
        return 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJ0ZW5hbnRAdGhpbmdzYm9hcmQub3JnIiwidXNlcklkIjoiYTRmNjEzMzAtZjdhOC0xMWVkLWJjY2YtZjcwODNhYWE3ZGZmIiwic2NvcGVzIjpbIlRFTkFOVF9BRE1JTiJdLCJzZXNzaW9uSWQiOiIwNDM2MjhjMi1iMmQ2LTQxNjItOWE2Ny1hNmRjNzUzNjIyMjUiLCJpc3MiOiJ0aGluZ3Nib2FyZC5pbyIsImlhdCI6MTY4NDY1NDAxOSwiZXhwIjoxNjg0NjYzMDE5LCJlbmFibGVkIjp0cnVlLCJpc1B1YmxpYyI6ZmFsc2UsInRlbmFudElkIjoiYTRhNTgyMzAtZjdhOC0xMWVkLWJjY2YtZjcwODNhYWE3ZGZmIiwiY3VzdG9tZXJJZCI6IjEzODE0MDAwLTFkZDItMTFiMi04MDgwLTgwODA4MDgwODA4MCJ9.2oqglZk5L-e-6lH4SBeANRMJ564rTf52sjtuQa9XjUOC7wRZrlOFP-nnkyJ2GbKZHQQVUvVE1MFy_ke7eFU_xw';
    }

    public function api(bool $handleException = true): PendingRequest
    {
        return parent::api($handleException)->withToken(last(explode(' ', $this->_token)));
    }

    public function setThingsboardToken(string $token = null): static
    {
        throw_if(
            is_null($token = $token ?? $this->defaultThingsboardToken()),
            $this->exception('method argument must be valid string token or "defaultThingsboardToken" must be return valid string token.')
        );

        return tap($this, fn () => $this->_token = last(explode(' ', $token)));
    }

    public function auth($args): static
    {
        $token = null;
        switch (count($args)) {
            case 0: /** Fetch credential from user attributes */
                dd(
                    request()->user()->getThingsboardEmailAttribute(),
                    request()->user()->getThingsboardPasswordAttribute(),
                );
            case 1: /** Token */
                $token = last(explode(' ', ...$args));
                break;
            case 2: /** User, Password */
                dd(...$args);
        }

        throw_if(
            is_null($token),
            $this->exception('method argument must be valid string token or "defaultThingsboardToken" must be return valid string token.')
        );

        return tap($this, fn () => $this->_token = $token);
    }
}
