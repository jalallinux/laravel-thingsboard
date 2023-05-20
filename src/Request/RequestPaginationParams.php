<?php

namespace JalalLinuX\Tntity\Request;

class RequestPaginationParams
{
    public int $page = 0;

    public int $perPage = 20;

    public string $sortProperty = 'createdTime';

    public string $sortOrder = 'DESC';

    public function page($page): static
    {
        $this->page = $page;

        return $this;
    }

    public function perPage($perPage): static
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function setSortProperty($sortProperty): static
    {
        $this->sortProperty = $sortProperty;

        return $this;
    }

    public function setSortOrder($sortOrder): static
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    public function params(): array
    {
        return get_class_vars(static::class);
    }
}
