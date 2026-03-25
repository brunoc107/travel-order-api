<?php

namespace App\Domain\Order\Events;

use DateTimeImmutable;

readonly class OrderApproved
{
    public function __construct(
        public string $orderId,
        public string $userId,
        public DateTimeImmutable $occurredAt,
    ) {}
}
