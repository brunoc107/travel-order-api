<?php

namespace App\Application\Order\UseCases;

use App\Domain\Order\Entities\Order;
use App\Domain\Order\Exception\OrderNotFoundException;
use App\Domain\Order\Repository\OrderRepository;

readonly class GetOrderUseCase
{
    public function __construct(
        private OrderRepository $orderRepository,
    ) {}

    public function execute(string $id, ?string $userId = null): Order
    {
        $order = $this->orderRepository->findOrderById($id, $userId);

        if (! $order) {
            throw new OrderNotFoundException('Order not found');
        }

        return $order;
    }
}
