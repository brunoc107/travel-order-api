<?php

namespace Tests\Unit;

use App\Application\Order\UseCases\ApproveOrderUseCase;
use App\Domain\Order\Exception\InvalidOrderActionException;
use App\Domain\Order\Exception\OrderNotFoundException;
use App\Domain\Order\Repository\OrderRepository;
use App\Domain\Order\ValueObjects\OrderStatus;
use App\Domain\Shared\EventBus;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class ApproveOrderUseCaseTest extends TestCase
{
    private OrderRepository $orderRepository;

    private EventBus $eventBus;

    private ApproveOrderUseCase $useCase;

    protected function setUp(): void
    {
        $this->orderRepository = $this->createMock(OrderRepository::class);
        $this->eventBus = $this->createMock(EventBus::class);
        $this->useCase = new ApproveOrderUseCase($this->orderRepository, $this->eventBus);
    }

    /**
     * Test the use-case's success path
     */
    public function test_order_approval(): void
    {
        $orderId = Str::ulid();
        $order = new OrderTestUtils()->restore_order(initialStatus: OrderStatus::REQUESTED);
        $this->orderRepository->method('findOrderById')->willReturn($order);

        $this->orderRepository->expects($this->once())->method('findOrderById')->with($orderId);
        $this->eventBus->expects($this->once())->method('dispatchAll');

        $this->useCase->execute($orderId);
    }

    /**
     * Test the use-case's fail due to double approval attempt
     */
    public function test_order_doubled_approval_error(): void
    {
        $orderId = Str::ulid();
        $order = new OrderTestUtils()->restore_order(initialStatus: OrderStatus::APPROVED);
        $this->orderRepository->method('findOrderById')->willReturn($order);

        $this->expectException(InvalidOrderActionException::class);
        $this->expectExceptionMessage('Order already approved');
        $this->orderRepository->expects($this->once())->method('findOrderById')->with($orderId);
        $this->eventBus->expects($this->never())->method('dispatchAll');

        $this->useCase->execute($orderId);
    }

    /**
     * Test the use-case's fail due to canceled order approval attempt
     */
    public function test_order_approve_canceled_error(): void
    {
        $orderId = Str::ulid();
        $order = new OrderTestUtils()->restore_order(initialStatus: OrderStatus::CANCELED);
        $this->orderRepository->method('findOrderById')->willReturn($order);

        $this->expectException(InvalidOrderActionException::class);
        $this->expectExceptionMessage('Canceled order cannot be approved');
        $this->orderRepository->expects($this->once())->method('findOrderById')->with($orderId);
        $this->eventBus->expects($this->never())->method('dispatchAll');

        $this->useCase->execute($orderId);
    }

    /**
     * Test the use-case's fail due to non-existent order under the received ID
     */
    public function test_order_not_found_error(): void
    {
        $orderId = Str::ulid();
        $this->orderRepository->method('findOrderById')->willReturn(null);

        $this->expectException(OrderNotFoundException::class);
        $this->expectExceptionMessage('Order not found');

        $this->useCase->execute($orderId);
    }
}
