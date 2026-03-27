<?php

namespace App\Infra\Http\Resources\Order;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class OrderCollectionResource extends ResourceCollection
{
    private readonly int $total;

    private readonly int $currentPage;

    private readonly int $perPage;

    public function __construct(Collection $items, int $total, int $currentPage, int $perPage)
    {
        parent::__construct($items);

        $this->total = $total;
        $this->currentPage = $currentPage;
        $this->perPage = $perPage;
    }

    public function toArray($request): array
    {
        return [
            'data' => OrderResource::collection($this->collection),
            'meta' => [
                'total' => $this->total,
                'per_page' => $this->perPage,
                'current_page' => $this->currentPage,
            ],
        ];
    }
}
