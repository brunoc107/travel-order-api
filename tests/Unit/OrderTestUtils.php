<?php

namespace Tests\Unit;

use App\Domain\Order\Entities\Order;
use App\Domain\Order\Entities\State\OrderStateFactory;
use App\Domain\Order\ValueObjects\OrderStatus;
use DateTimeImmutable;
use Illuminate\Support\Str;

class OrderTestUtils
{
    public function restore_order(
        OrderStatus $initialStatus,
        DateTimeImmutable $createdAt = new DateTimeImmutable,
        DateTimeImmutable $updatedAt = new DateTimeImmutable,
    ): Order {
        $orderId = Str::ulid()->toString();
        $userId = Str::ulid()->toString();

        return Order::restore(
            $orderId,
            $userId,
            'John Doe',
            'São Paulo - SP',
            new DateTimeImmutable('2026-05-01'),
            new DateTimeImmutable('2026-05-09'),
            OrderStateFactory::from($initialStatus),
            $createdAt,
            $updatedAt,
        );
    }
}
