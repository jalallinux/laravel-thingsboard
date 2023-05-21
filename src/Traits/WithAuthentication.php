<?php

namespace JalalLinuX\Tntity\Traits;

use Illuminate\Http\Client\PendingRequest;
use JalalLinuX\Tntity\Authenticate;
use JalalLinuX\Tntity\Interfaces\ThingsboardUser;

trait WithAuthentication
{
    public function __construct(...$args)
    {
        parent::__construct(...$args);
    }

    public function api(bool $handleException = true): PendingRequest
    {
        return parent::api($handleException)->withToken(last(explode(' ', $this->_token)));
    }

    public function authWith(ThingsboardUser $user): static
    {
        return tap($this, fn () => $this->_token = Authenticate::fromUser($user));
    }
}
