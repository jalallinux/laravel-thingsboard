<?php

namespace JalalLinuX\Thingsboard;

use JalalLinuX\Thingsboard\Enums\SortOrder;
use Spatie\Enum\Laravel\Enum;

class PaginationArguments
{
    public int $page = 0;
    public int $pageSize = 20;
    public string $sortProperty = 'createdTime';
    public string $sortOrder = 'DESC';
    public ?string $textSearch = null;

    public function __construct(int $page = null, int $pageSize = null, Enum $sortProperty = null, SortOrder $sortOrder = null, string $textSearch = null)
    {
        $this->setPage($page);
        $this->setPageSize($pageSize);
        $this->setSortProperty($sortProperty);
        $this->setSortOrder($sortOrder);
        $this->setTextSearch($textSearch);
    }

    public static function make(int $page = null, int $pageSize = null, Enum $sortProperty = null, SortOrder $sortOrder = null, string $textSearch = null): PaginationArguments
    {
        return new self($page, $pageSize, $sortProperty, $sortOrder, $textSearch);
    }

    protected function setPage(int $page): self
    {
        return tap($this, fn() => $this->page = $page >= 0 ? $page : $this->page);
    }

    protected function setPageSize(int $pageSize): self
    {
        return tap($this, fn() => $this->pageSize = ($pageSize >= -1 ? $pageSize : $this->pageSize));
    }

    protected function setSortProperty(Enum $sortProperty): self
    {
        return tap($this, fn() => $this->sortProperty = (str_ends_with($sortProperty::class, 'SortProperty') ? $sortProperty->value : $this->sortProperty));
    }

    protected function setSortOrder(SortOrder $sortOrder): self
    {
        return tap($this, fn() => $this->sortOrder = $sortOrder->value);
    }

    protected function setTextSearch(string $textSearch): self
    {
        return tap($this, fn() => $this->textSearch = $textSearch);
    }

    public function queryParams(): array
    {
        return array_filter(get_class_vars($this::class));
    }
}
