<?php

namespace JalalLinuX\Tntity\Entities;

use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Http;
use JalalLinuX\Tntity\Exceptions\TntityExceptionHandler;
use Jenssegers\Model\Model;

abstract class Tntity extends Model
{
    protected string $_token;

    protected function api(bool $handleException = true): PendingRequest
    {
        $baseUri = config('thingsboard.connection.base_uri');
        $baseUri = str_ends_with($baseUri, '/') ? substr($baseUri, 0, -1) : $baseUri;

        $request = Http::baseUrl("{$baseUri}/api")->acceptJson();

        return ! $handleException ? $request : $request->throw(
            fn (Response $response, RequestException $e) => TntityExceptionHandler::make($response, $e)->handle()
        );
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

    public static function make(array $attributes = []): static
    {
        return new static($attributes);
    }
}