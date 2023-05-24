<?php

namespace JalalLinuX\Thingsboard;

use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Support\Facades\Http;
use JalalLinuX\Thingsboard\Enums\ThingsboardEntityType;
use JalalLinuX\Thingsboard\Exceptions\ThingsboardExceptionHandler;
use JalalLinuX\Thingsboard\Interfaces\ThingsboardEntityId;
use JalalLinuX\Thingsboard\Interfaces\ThingsboardUser;
use Jenssegers\Model\Model;

abstract class Tntity extends Model
{
    protected ThingsboardUser $_thingsboardUser;

    abstract public function entityType(): ?ThingsboardEntityType;

    protected function api(bool $auth = false, bool $handleException = true): PendingRequest
    {
        $baseUri = config('thingsboard.rest.base_uri');
        $baseUri = str_ends_with($baseUri, '/') ? substr($baseUri, 0, -1) : $baseUri;
        $request = Http::baseUrl("{$baseUri}/api")->acceptJson();

        if ($auth) {
            throw_if(! isset($this->_thingsboardUser), $this->exception('method need authentication token.', 401));
            $request = $request->withHeaders([
                config('thingsboard.rest.authorization.header_key') => config('thingsboard.rest.authorization.token_type').' '.Thingsboard::fetchUserToken($this->_thingsboardUser),
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
        throw_if(
            is_null($value = @$this->{$key}),
            $this->exception("{$key} attribute is required in ")
        );

        return $value;
    }

    public function getAttributes(): array
    {
        foreach ($this->attributes as $k => $v) {
            $this->attributes[$k] = $this->hasCast($k) ? $this->castAttribute($k, $v) : $v;
        }

        return $this->attributes;
    }

    protected function exception(string $message, $code = 500): Exception
    {
        $message = debug_backtrace()[1]['class'].'@'.debug_backtrace()[1]['function'].": {$message}";

        return new Exception($message, $code);
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

    public function paginatedResponse(Response $response, ThingsboardPaginationArguments $arguments, Tntity $tntity = null): ThingsboardPaginatedResponse
    {
        return new ThingsboardPaginatedResponse($tntity ?? $this, $response, $arguments);
    }

    protected function castAttribute($key, $value)
    {
        if (is_null($value)) {
            return $value;
        }

        switch ($this->getCastType($key)) {
            case 'id':
                return new ThingsboardEntityId(is_array($value) ? @$value['id'] : $value, $this->entityType());
            case 'int':
            case 'integer':
                return (int) $value;
            case 'real':
            case 'float':
            case 'double':
                return (float) $value;
            case 'string':
                return (string) $value;
            case 'bool':
            case 'boolean':
                return (bool) $value;
            case 'object':
                return $this->fromJson($value, true);
            case 'array':
            case 'json':
                return $this->fromJson($value);
            case 'collection':
                return new BaseCollection($this->fromJson($value));
            default:
                return $value;
        }
    }
}
