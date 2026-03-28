<?php

namespace App\Application\Order\UseCases;

use App\Domain\Order\Exception\OrderNotFoundException;
use App\Domain\Order\Repository\OrderRepository;
use App\Domain\Shared\EventBus;

class CancelOrderUseCase
{
    public function __construct(
        private OrderRepository $orderRepository,
        private EventBus $eventBus
    ) {}

    public function execute(string $orderId): void
    {
        $order = $this->orderRepository->findOrderById($orderId, null);

        if (! $order) {
            throw new OrderNotFoundException('Order not found');
        }

        $order->cancel();

        $this->orderRepository->save($order);

        $this->eventBus->dispatchAll($order->pullEvents());
    }
}
