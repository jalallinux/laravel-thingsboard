<?php

namespace JalalLinuX\Tntity\Traits;

use Illuminate\Http\Client\PendingRequest;

trait WithThingsboardAuthentication
{
    public function __construct(...$args)
    {
        parent::__construct(...$args);
        $this->setThingsboardToken();
    }

    private function defaultThingsboardToken(): string
    {
        return 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJ0ZW5hbnRAdGhpbmdzYm9hcmQub3JnIiwidXNlcklkIjoiZjg3MDgxYTAtZWU1Zi0xMWVkLWIxMTgtNTNkZGRmZjZiY2JiIiwic2NvcGVzIjpbIlRFTkFOVF9BRE1JTiJdLCJzZXNzaW9uSWQiOiJiYjkwY2FiNy0wZTNmLTQwOTctYTllZC03YzYxMDMyN2MwM2YiLCJpc3MiOiJ0aGluZ3Nib2FyZC5pbyIsImlhdCI6MTY4NDA3MTAzNiwiZXhwIjoxNjg0MDgwMDM2LCJlbmFibGVkIjp0cnVlLCJpc1B1YmxpYyI6ZmFsc2UsInRlbmFudElkIjoiZjc3ZmUwMTAtZWU1Zi0xMWVkLWIxMTgtNTNkZGRmZjZiY2JiIiwiY3VzdG9tZXJJZCI6IjEzODE0MDAwLTFkZDItMTFiMi04MDgwLTgwODA4MDgwODA4MCJ9.W5cZ4SpXnn_6OsYhecnqqZH6sgwDRP8iQyJHiUZmeFQZrCSwGH7FVv0NKvFo6i5hVNTzjD6Aez2YlAW-mkS6RA';
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
}
