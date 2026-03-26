<?php

namespace App\Domain\Order\Repository;

use App\Domain\Order\Entities\Order;
use Illuminate\Support\Collection;

interface OrderRepository
{
    public function save(Order $order): void;

    /**
     * @return Page<Order>
     */
    public function findMany(OrderCriteria $criteria, Pagination $pagination): Page;

    public function findOrderById(string $id, ?string $userId = null): ?Order;

    /**
     * @return Collection<Order>
     */
    public function findOrdersByUserId(string $userId): Collection;
}
