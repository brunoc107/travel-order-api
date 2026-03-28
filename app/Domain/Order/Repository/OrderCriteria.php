<?php

namespace App\Domain\Order\Repository;

use App\Domain\Order\ValueObjects\OrderStatus;
use DateTimeImmutable;

class OrderCriteria
{
    public function __construct(
        public ?OrderStatus $status = null,
        public ?string $userId = null,
        public ?string $destination = null,
        public ?DateTimeImmutable $departureDateTime = null,
        public ?DateTimeImmutable $arrivalDateTime = null,
    ) {}
}
