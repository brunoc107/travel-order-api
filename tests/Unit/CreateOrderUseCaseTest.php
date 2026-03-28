<?php

namespace Tests\Unit;

use App\Application\Order\UseCases\CreateOrderUseCase;
use App\Domain\Order\Entities\Order;
use App\Domain\Order\Repository\OrderRepository;
use App\Domain\Shared\EventBus;
use DateTimeImmutable;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class CreateOrderUseCaseTest extends TestCase
{
    private OrderRepository $orderRepository;

    private EventBus $eventBus;

    private CreateOrderUseCase $useCase;

    protected function setUp(): void
    {
        $this->orderRepository = $this->createMock(OrderRepository::class);
        $this->eventBus = $this->createMock(EventBus::class);
        $this->useCase = new CreateOrderUseCase($this->orderRepository, $this->eventBus);
    }

    /**
     * Test the use-case's success path
     */
    public function test_order_creation(): void
    {
        $userId = Str::ulid();
        $userName = 'John Doe';
        $destination = 'São Paulo - SP';
        $departureDate = new DateTimeImmutable('2026-05-01');
        $arrivalDate = new DateTimeImmutable('2026-05-08');

        $this->orderRepository->expects($this->once())->method('save');
        $this->eventBus->expects($this->once())->method('dispatchAll');

        $order = $this->useCase->execute(
            $userId,
            $userName,
            $destination,
            $departureDate,
            $arrivalDate
        );

        $this->assertInstanceOf(Order::class, $order);
        $this->assertTrue(Str::isUlid($order->getId()));
    }
}
