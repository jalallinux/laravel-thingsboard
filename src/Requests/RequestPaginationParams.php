<?php

namespace JalalLinuX\Thingsboard\Requests;

class RequestPaginationParams
{
    public int $page = 0;

    public int $perPage = 20;

    public string $sortProperty = 'createdTime';

    public string $sortOrder = 'DESC';

    public function page($page): self
    {
        $this->page = $page;

        return $this;
    }

    public function perPage($perPage): self
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function setSortProperty($sortProperty): self
    {
        $this->sortProperty = $sortProperty;

        return $this;
    }

    public function setSortOrder($sortOrder): self
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    public function params(): array
    {
        return get_class_vars(static::class);
    }
}
