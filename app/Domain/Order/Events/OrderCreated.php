<?php

namespace App\Domain\Order\Events;

use DateTimeImmutable;

readonly class OrderCreated
{
    public function __construct(
        public string $orderId,
        public string $userId,
        public DateTimeImmutable $occurredAt,
    ) {}
}
