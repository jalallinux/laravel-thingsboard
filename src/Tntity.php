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
use Vkovic\LaravelCustomCasts\HasCustomCasts;

abstract class Tntity extends Model
{
    use HasCustomCasts;

    protected ThingsboardUser $_thingsboardUser;

    abstract public function entityType(): ?EnumEntityType;

    protected function api(bool $auth = true, bool $handleException = true): PendingRequest
    {
        $baseUri = self::config('rest.base_uri');
        $baseUri = str_ends_with($baseUri, '/') ? substr($baseUri, 0, -1) : $baseUri;
        $request = Http::baseUrl("{$baseUri}/api");

        if ($auth) {
            Thingsboard::exception(! isset($this->_thingsboardUser), 'with_token', 401);
            $request = $request->withHeaders([
                self::config('rest.authorization.header_key') => self::config('rest.authorization.token_type').' '.Thingsboard::fetchUserToken($this->_thingsboardUser),
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
        return data_get($this->getAttributes(), $key, $default);
    }

    public function forceAttribute($key)
    {
        Thingsboard::validation(is_null($value = @$this->{$key}), 'required', ['attribute' => $key]);

        return $value;
    }

    public function getAttributes(): array
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

    public static function config(string $key = null, $default = null)
    {
        return is_null($key) ? config('thingsboard') : config("thingsboard.{$key}", $default);
    }
}
