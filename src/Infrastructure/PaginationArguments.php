<?php

namespace JalalLinuX\Thingsboard\Infrastructure;

use Illuminate\Support\Arr;
use JalalLinuX\Thingsboard\Enums\EnumSortOrder;
use Spatie\Enum\Laravel\Enum;

class PaginationArguments
{
    public int $page = 0;

    public int $pageSize = 20;

    public string $sortProperty = 'createdTime';

    public string $sortOrder = 'DESC';

    public ?string $textSearch = null;

    public function __construct(int $page = null, int $pageSize = null, Enum $sortProperty = null, EnumSortOrder $sortOrder = null, string $textSearch = null)
    {
        $this->setPage($page);
        $this->setPageSize($pageSize);
        $this->setSortProperty($sortProperty);
        $this->setSortOrder($sortOrder);
        $this->setTextSearch($textSearch);
    }

    public static function make(int $page = null, int $pageSize = null, Enum $sortProperty = null, EnumSortOrder $sortOrder = null, string $textSearch = null): PaginationArguments
    {
        return (new self)
            ->setPage($page)
            ->setPageSize($pageSize)
            ->setSortProperty($sortProperty)
            ->setSortOrder($sortOrder)
            ->setTextSearch($textSearch);
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

    protected function setSortOrder(?EnumSortOrder $sortOrder): self
    {
        return tap($this, fn () => $this->sortOrder = ! is_null($sortOrder) ? $sortOrder->value : $this->sortOrder);
    }

    protected function setTextSearch(?string $textSearch): self
    {
        return tap($this, fn () => $this->textSearch = (! is_null($textSearch) ? $textSearch : $this->textSearch));
    }

    public function validateSortProperty(string $sortPropertyEnum, array $exceptKeys = [], bool $throw = true): bool
    {
        $validated = in_array($this->sortProperty, Arr::except($sortPropertyEnum::toValues(), array_map(fn ($v) => (string) $v, $exceptKeys)));
        throw_if($throw && ! $validated, new \Exception("Sort property must be a instance of {$sortPropertyEnum}."));

        return $validated;
    }

    public function queryParams(array $extra = []): array
    {
        return array_filter(array_merge([
            'page' => $this->page,
            'pageSize' => $this->pageSize,
            'sortProperty' => $this->sortProperty,
            'sortOrder' => $this->sortOrder,
            'textSearch' => $this->textSearch,
        ], $extra), fn ($v) => $v !== null);
    }
}
