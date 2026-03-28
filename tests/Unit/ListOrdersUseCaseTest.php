<?php

namespace Tests\Unit;

use App\Application\Order\UseCases\ListOrdersUseCase;
use App\Domain\Order\Entities\Order;
use App\Domain\Order\Repository\OrderCriteria;
use App\Domain\Order\Repository\OrderRepository;
use App\Domain\Order\Repository\Page;
use App\Domain\Order\Repository\Pagination;
use App\Domain\Order\ValueObjects\OrderStatus;
use PHPUnit\Framework\TestCase;

class ListOrdersUseCaseTest extends TestCase
{
    private OrderRepository $orderRepository;

    private ListOrdersUseCase $useCase;

    protected function setUp(): void
    {
        $this->orderRepository = $this->createMock(OrderRepository::class);
        $this->useCase = new ListOrdersUseCase($this->orderRepository);
    }

    public function test_list_orders(): void
    {
        $order = new OrderTestUtils()->restore_order(OrderStatus::REQUESTED);
        $this->orderRepository->method('findMany')->willReturn(new Page(
            collect([$order]),
            1, 1, 1,
        ));
        $result = $this->useCase->execute(new OrderCriteria(), new Pagination(1, 10));

        $this->assertInstanceOf(Page::class, $result);
        $this->assertCount(1, $result->items->toArray());
        $this->assertInstanceOf(Order::class, $result->items->first());
        $this->assertEquals(OrderStatus::REQUESTED, $result->items->first()->getStatus());
    }
}
