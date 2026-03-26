<?php

namespace App\Application\Order\UseCases;

use App\Domain\Order\Repository\OrderCriteria;
use App\Domain\Order\Repository\OrderRepository;
use App\Domain\Order\Repository\Page;
use App\Domain\Order\Repository\Pagination;

readonly class ListOrdersUseCase
{
    public function __construct(
        private OrderRepository $orderRepository,
    ) {}

    public function execute(OrderCriteria $criteria, Pagination $pagination): Page
    {
        return $this->orderRepository->findMany($criteria, $pagination);
    }
}
