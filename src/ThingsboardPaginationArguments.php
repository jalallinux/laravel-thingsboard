<?php

namespace JalalLinuX\Thingsboard;

use JalalLinuX\Thingsboard\Enums\ThingsboardSortOrder;
use Spatie\Enum\Laravel\Enum;

class ThingsboardPaginationArguments
{
    public int $page = 0;

    public int $pageSize = 20;

    public string $sortProperty = 'createdTime';

    public string $sortOrder = 'DESC';

    public ?string $textSearch = null;

    public function __construct(int $page = null, int $pageSize = null, Enum $sortProperty = null, ThingsboardSortOrder $sortOrder = null, string $textSearch = null)
    {
        $this->setPage($page);
        $this->setPageSize($pageSize);
        $this->setSortProperty($sortProperty);
        $this->setSortOrder($sortOrder);
        $this->setTextSearch($textSearch);
    }

    public static function make(int $page = null, int $pageSize = null, Enum $sortProperty = null, ThingsboardSortOrder $sortOrder = null, string $textSearch = null): ThingsboardPaginationArguments
    {
        $instance = new self;
        $instance->setPage($page);
        $instance->setPageSize($pageSize);
        $instance->setSortProperty($sortProperty);
        $instance->setSortOrder($sortOrder);
        $instance->setTextSearch($textSearch);

        return $instance;
    }

    protected function setPage(?int $page): self
    {
        return tap($this, fn () => $this->page = (is_numeric($page) && $page >= 0 ? $page : $this->page));
    }

    protected function setPageSize(?int $pageSize): self
    {
        return tap($this, fn () => $this->pageSize = (is_numeric($pageSize) && $pageSize >= -1 ? $pageSize : $this->pageSize));
    }

    protected function setSortProperty(?Enum $sortProperty): self
    {
        return tap($this, fn () => $this->sortProperty = (! is_null($sortProperty) && str_ends_with($sortProperty::class, 'SortProperty') ? $sortProperty->value : $this->sortProperty));
    }

    protected function setSortOrder(?ThingsboardSortOrder $sortOrder): self
    {
        return tap($this, fn () => $this->sortOrder = ! is_null($sortOrder) ? $sortOrder->value : $this->sortOrder);
    }

    protected function setTextSearch(?string $textSearch): self
    {
        return tap($this, fn () => $this->textSearch = (! is_null($textSearch) ? $textSearch : $this->textSearch));
    }

    public function queryParams(array $extra = []): array
    {
        return array_filter(array_merge(get_class_vars($this::class), $extra), fn ($v) => $v !== null);
    }
}
