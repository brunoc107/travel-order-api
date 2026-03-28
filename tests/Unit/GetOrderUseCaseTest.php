<?php

namespace Tests\Unit;

use App\Application\Order\UseCases\GetOrderUseCase;
use App\Domain\Order\Entities\Order;
use App\Domain\Order\Exception\OrderNotFoundException;
use App\Domain\Order\Repository\OrderRepository;
use App\Domain\Order\ValueObjects\OrderStatus;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class GetOrderUseCaseTest extends TestCase
{
    private OrderRepository $orderRepository;

    private GetOrderUseCase $useCase;

    protected function setUp(): void
    {
        $this->orderRepository = $this->createMock(OrderRepository::class);
        $this->useCase = new GetOrderUseCase($this->orderRepository);
    }

    /**
     * Test the use-case's success path
     *
     * @return void
     */
    public function test_get_order()
    {
        $order = new OrderTestUtils()->restore_order(OrderStatus::REQUESTED);
        $this->orderRepository->method('findOrderById')->willReturn($order);
        $result = $this->useCase->execute($order->getId());

        $this->assertInstanceOf(Order::class, $result);
    }

    /**
     * Test the use-case's fail due to non-existent order under the received ID
     *
     * @return void
     */
    public function test_get_order_not_found_error()
    {
        $this->orderRepository->method('findOrderById')->willReturn(null);

        $this->expectException(OrderNotFoundException::class);
        $this->expectExceptionMessage('Order not found');

        $this->useCase->execute(Str::ulid());
    }
}
