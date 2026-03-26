<?php

namespace App\Domain\Order\Repository;

use Illuminate\Support\Collection;

readonly class Page
{
    public function __construct(
        public Collection $items,
        public int $total,
        public int $page,
        public int $perPage,
    ) {}
}
