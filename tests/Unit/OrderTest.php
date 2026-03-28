<?php

namespace Tests\Unit;

use App\Domain\Order\Entities\Order;
use App\Domain\Order\Events\OrderApproved;
use App\Domain\Order\Events\OrderCanceled;
use App\Domain\Order\Events\OrderCreated;
use App\Domain\Order\Exception\InvalidOrderActionException;
use App\Domain\Order\ValueObjects\OrderStatus;
use DateInterval;
use DateInvalidOperationException;
use DateTimeImmutable;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    /**
     * Test Order domain entity create logic
     */
    public function test_order_entity_create(): void
    {
        $orderId = Str::ulid()->toString();
        $userId = Str::ulid()->toString();
        $order = Order::create(
            $orderId,
            $userId,
            'John Doe',
            'São Paulo - SP',
            new DateTimeImmutable('2026-05-01'),
            new DateTimeImmutable('2026-05-09'),
        );

        $events = $order->pullEvents();

        $this->assertCount(1, $events);
        $this->assertInstanceOf(OrderCreated::class, $events[0]);
        $this->assertEquals($orderId, $events[0]->orderId);
        $this->assertEquals($userId, $events[0]->userId);
    }

    /**
     * Test order domain entity restore logic
     *
     * @throws DateInvalidOperationException
     */
    public function test_order_entity_restore(): void
    {
        $now = new DateTimeImmutable;
        $createdAt = $now->sub(new DateInterval('PT2H'));
        $updatedAt = $now->sub(new DateInterval('PT1H'));

        $order = new OrderTestUtils()->restore_order(initialStatus: OrderStatus::REQUESTED, createdAt: $createdAt, updatedAt: $updatedAt);

        $this->assertEquals(OrderStatus::REQUESTED, $order->getStatus());
        $this->assertEquals(OrderStatus::REQUESTED, $order->getStatus());
        $this->assertEquals($now->sub(new DateInterval('PT2H')), $order->getCreatedAt());
        $this->assertEquals($now->sub(new DateInterval('PT1H')), $order->getUpdatedAt());
    }

    /**
     * Test order approve logic
     */
    public function test_order_entity_approve(): void
    {
        $order = new OrderTestUtils()->restore_order(initialStatus: OrderStatus::REQUESTED);
        $order->approve();

        $events = $order->pullEvents();

        $this->assertEquals(OrderStatus::APPROVED, $order->getStatus());
        $this->assertCount(1, $events);
        $this->assertInstanceOf(OrderApproved::class, $events[0]);
    }

    /**
     * Test order double approve error handling
     */
    public function test_order_entity_doubled_approve_error(): void
    {
        $this->expectException(InvalidOrderActionException::class);
        $this->expectExceptionMessage('Order already approved');
        $order = new OrderTestUtils()->restore_order(initialStatus: OrderStatus::APPROVED);

        $order->approve();
    }

    /**
     * Test canceled order approval attempt error handling
     */
    public function test_order_entity_approve_cancelled_error(): void
    {
        $this->expectException(InvalidOrderActionException::class);
        $this->expectExceptionMessage('Canceled order cannot be approved');
        $order = new OrderTestUtils()->restore_order(initialStatus: OrderStatus::CANCELED);

        $order->approve();
    }

    /**
     * Test order cancel logic
     */
    public function test_order_entity_cancel(): void
    {
        $order = new OrderTestUtils()->restore_order(initialStatus: OrderStatus::REQUESTED);
        $order->cancel();

        $events = $order->pullEvents();

        $this->assertEquals(OrderStatus::CANCELED, $order->getStatus());
        $this->assertCount(1, $events);
        $this->assertInstanceOf(OrderCanceled::class, $events[0]);
    }

    /**
     * Test order double cancel error handling
     */
    public function test_order_entity_doubled_cancel_error(): void
    {
        $this->expectException(InvalidOrderActionException::class);
        $this->expectExceptionMessage('Order already canceled');
        $order = new OrderTestUtils()->restore_order(initialStatus: OrderStatus::CANCELED);

        $order->cancel();
    }

    /**
     * Test approved order cancellation attempt error handling
     */
    public function test_order_entity_cancel_approved_error(): void
    {
        $this->expectException(InvalidOrderActionException::class);
        $this->expectExceptionMessage('Approved order cannot be canceled');
        $order = new OrderTestUtils()->restore_order(initialStatus: OrderStatus::APPROVED);

        $order->cancel();
    }
}
