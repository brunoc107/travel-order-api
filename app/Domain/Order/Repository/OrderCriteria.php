<?php

namespace App\Domain\Order\Repository;

use App\Domain\Order\ValueObjects\OrderStatus;
use DateTimeImmutable;

class OrderCriteria
{
    public function __construct(
        public ?OrderStatus $status,
        public ?string $userId,
        public ?string $destination,
        public ?DateTimeImmutable $departureDateTime,
        public ?DateTimeImmutable $arrivalDateTime,
    ) {}
}
