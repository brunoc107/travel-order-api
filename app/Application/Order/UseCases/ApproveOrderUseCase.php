<?php

namespace App\Application\Order\UseCases;

use App\Domain\Order\Repository\OrderRepository;
use App\Domain\Shared\EventBus;

readonly class ApproveOrderUseCase
{
    public function __construct(
        private OrderRepository $orderRepository,
        private EventBus $eventBus
    ) {}

    public function execute(string $orderId): void
    {
        $order = $this->orderRepository->findOrderById($orderId, null);

        $order->approve();

        $this->orderRepository->save($order);

        $this->eventBus->dispatchAll($order->pullEvents());
    }
}
