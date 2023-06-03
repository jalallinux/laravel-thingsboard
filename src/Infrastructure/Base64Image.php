<?php

namespace JalalLinuX\Thingsboard\Infrastructure;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Base64Image
{
    private string $base64string;

    public function __construct(string $base64string)
    {
        $this->base64string = $base64string;
    }

    public function download(): BinaryFileResponse
    {
        $path = config('thingsboard.temp_path') . ".{$this->extension()}";

        file_put_contents($path, base64_decode($this->data()));

        return response()->download($path)->deleteFileAfterSend();
    }

    public function extension(): string
    {
        return explode('/', mime_content_type($this->base64string))[1];
    }

    public function data()
    {
        return last(explode(',', $this->base64string));
    }

    public function __toString(): string
    {
        return $this->base64string;
    }
}
