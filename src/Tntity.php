<?php

namespace JalalLinuX\Thingsboard;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Http;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Exceptions\ThingsboardExceptionHandler;
use JalalLinuX\Thingsboard\Infrastructure\PaginatedResponse;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Interfaces\ThingsboardUser;
use Jenssegers\Model\Model;
use JalalLinuX\Thingsboard\Infrastructure\HasCustomCasts;

abstract class Tntity extends Model
{
    use HasCustomCasts;

    protected ThingsboardUser $_thingsboardUser;

    abstract public function entityType(): ?EnumEntityType;

    protected function api(bool $auth = true, bool $handleException = true): PendingRequest
    {
        $baseUri = config('thingsboard.rest.schema').'://'.config('thingsboard.rest.host').':'.config('thingsboard.rest.port');
        $request = Http::baseUrl("{$baseUri}/api");

        if ($auth) {
            Thingsboard::exception(! isset($this->_thingsboardUser), 'with_token', code: 401);

            $request = $request->withHeaders([
                config('thingsboard.rest.authorization.header_key') => config('thingsboard.rest.authorization.token_type').' '.Thingsboard::fetchUserToken($this->_thingsboardUser)->getAccessToken(),
            ]);
        }

        return ! $handleException ? $request : $request->throw(
            fn (Response $response, RequestException $e) => ThingsboardExceptionHandler::make($response, $e)->handle()
        );
    }

    public function withUser(ThingsboardUser $user): static
    {
        return tap($this, fn () => $this->_thingsboardUser = $user);
    }

    public function get($key = null, $default = null)
    {
        return data_get($this->getCastAttributes(), $key, $default);
    }

    public function forceAttribute($key)
    {
        Thingsboard::validation(is_null($value = @$this->{$key}), 'required', ['attribute' => $key]);

        return $value;
    }

    public function getCastAttributes(): array
    {
        foreach ($this->attributes as $k => $v) {
            $this->attributes[$k] = $this->hasCast($k) ? $this->castAttribute($k, $v) : $v;
        }

        return $this->attributes;
    }

    public function toResource(string $class): JsonResource
    {
        return new $class($this);
    }

    public function fill(array $attributes = []): static
    {
        if (empty($attributes)) {
            return $this;
        }

        return parent::fill($attributes);
    }

    public static function instance(array $attributes = []): static
    {
        return new static($attributes);
    }

    public function paginatedResponse(Response $response, PaginationArguments $arguments, Tntity $tntity = null): PaginatedResponse
    {
        return new PaginatedResponse($tntity ?? $this, $response, $arguments);
    }
}
