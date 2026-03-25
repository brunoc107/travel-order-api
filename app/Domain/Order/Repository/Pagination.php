<?php

namespace App\Domain\Order\Repository;

readonly class Pagination
{
    public function __construct(
        public int $page,
        public int $perPage,
    ) {}
}
