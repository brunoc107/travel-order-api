<?php

namespace App\Application\Order\UseCases;

use App\Domain\Order\Entities\Order;
use App\Domain\Order\Repository\OrderRepository;

readonly class GetOrderUseCase
{
    public function __construct(
        private OrderRepository $orderRepository,
    ) {}

    public function execute(string $id, ?string $userId): ?Order
    {
        $order = $this->orderRepository->findOrderById($id, $userId);

        if (! $order) {
            return null;
        }

        return $order;
    }
}
