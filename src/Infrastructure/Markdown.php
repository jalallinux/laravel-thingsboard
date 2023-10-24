<?php

namespace JalalLinuX\Thingsboard\Infrastructure;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;
use Stringable;

class Markdown implements Htmlable, Stringable
{
    private string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function markdown(): string
    {
        return Str::markdown($this->text);
    }

    public function inlineMarkdown(): string
    {
        return Str::inlineMarkdown($this->text);
    }

    public function __toString()
    {
        return $this->text;
    }

    public function toHtml(): string
    {
        return $this->markdown();
    }
}
