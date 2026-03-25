<?php

namespace App\Domain\Order\Repository;

use App\Domain\Order\Entities\Order;
use Illuminate\Support\Collection;

readonly class Page
{
    /**
     * @param  Collection<Order>  $items
     */
    public function __construct(
        public Collection $items,
        public int $total,
        public int $page,
        public int $perPage,
    ) {}
}
