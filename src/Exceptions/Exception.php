<?php

namespace JalalLinuX\Thingsboard\Exceptions;

use Throwable;

class Exception extends \Exception
{
    public function __construct(string $message = null, int $code = 500, Throwable $previous = null)
    {
        $message = $message ?? __('thingsboard::exception.default');
        parent::__construct($message, $code, $previous);
    }
}
