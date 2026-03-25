<?php

namespace App\Domain\Order\Services;

use App\Domain\Order\Entities\Order;
use App\Domain\Order\Repository\OrderRepository;
use App\Domain\Shared\EventBus;
use DateTimeImmutable;
use Illuminate\Support\Str;

readonly class CreateOrderUseCase
{
    public function __construct(
        private OrderRepository $orderRepository,
        private EventBus        $eventBus
    ) {}

    public function execute(
        string $userId,
        string $userName,
        string $destination,
        DateTimeImmutable $departureDate,
        DateTimeImmutable $arrivalDate,
    ) {
        $order = Order::create(
            id: Str::ulid()->toString(),
            userId: $userId,
            userName: $userName,
            destination: $destination,
            departureDate: $departureDate,
            arrivalDate: $arrivalDate,
        );

        $this->orderRepository->save($order);

        $this->eventBus->dispatchAll($order->pullEvents());

        return $order->getId();
    }
}
