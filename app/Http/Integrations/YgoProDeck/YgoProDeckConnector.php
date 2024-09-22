<?php

namespace App\Http\Integrations\YgoProDeck;

use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\HasPagination;
use Saloon\PaginationPlugin\OffsetPaginator;
use Saloon\Traits\Plugins\AcceptsJson;

class YgoProDeckConnector extends Connector implements HasPagination
{
    use AcceptsJson;

    /**
     * The Base URL of the API
     */
    public function resolveBaseUrl(): string
    {
        return 'https://db.ygoprodeck.com/api/v7';
    }

    /**
     * Default headers for every request
     */
    protected function defaultHeaders(): array
    {
        return [];
    }

    /**
     * Default HTTP client options
     */
    protected function defaultConfig(): array
    {
        return [];
    }

    public function paginate(Request $request): OffsetPaginator
    {
        return new class(connector: $this, request: $request) extends OffsetPaginator
        {
            protected ?int $perPageLimit = 100;

            protected function isLastPage(Response $response): bool
            {
                return $response->json('meta.pages_remaining') == 0;
            }

            protected function getPageItems(Response $response, Request $request): array
            {
                return $response->json('data') ?? [];
            }

            protected function applyPagination(Request $request): Request
            {
                $request->query()->merge([
                    'num' => $this->perPageLimit,
                    'offset' => $this->getOffset(),
                ]);

                return $request;
            }
        };
    }
}
