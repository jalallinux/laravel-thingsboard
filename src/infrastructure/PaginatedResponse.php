<?php

namespace JalalLinuX\Thingsboard\infrastructure;

use Illuminate\Http\Client\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use JalalLinuX\Thingsboard\Tntity;

class PaginatedResponse
{
    private Tntity $tntity;

    private Response $response;

    private PaginationArguments $arguments;

    public function __construct(Tntity $tntity, Response $response, PaginationArguments $arguments)
    {
        $this->tntity = $tntity;
        $this->response = $response;
        $this->arguments = $arguments;
    }

    public function paginator(): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            $this->response->json('data'), $this->response->json('totalElements'), $this->arguments->pageSize, $this->arguments->page, [
                'sortOrder' => $this->arguments->sortOrder,
                'sortProperty' => $this->arguments->sortProperty,
            ]
        );
    }

    public function data(): Collection
    {
        return collect($this->response->json('data'))->map(fn ($row) => new $this->tntity($row));
    }
}
