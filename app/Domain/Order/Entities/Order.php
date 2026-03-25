<?php

namespace App\Domain\Order\Entities;

use App\Domain\Order\Entities\State\OrderState;
use App\Domain\Order\Entities\State\RequestedState;
use App\Domain\Order\Events\OrderApproved;
use App\Domain\Order\Events\OrderCanceled;
use App\Domain\Order\Events\OrderCreated;
use App\Domain\Order\ValueObjects\OrderStatus;
use DateTimeImmutable;

class Order
{
    private array $events = [];

    private string $id;

    private string $userId;

    private string $userName;

    private string $destination;

    private DateTimeImmutable $departureDate;

    private DateTimeImmutable $arrivalDate;

    private OrderState $state;

    private DateTimeImmutable $createdAt;

    private DateTimeImmutable $updatedAt;

    public static function create(
        string $id,
        string $userId,
        string $userName,
        string $destination,
        DateTimeImmutable $departureDate,
        DateTimeImmutable $arrivalDate
    ): Order {
        $now = new DateTimeImmutable;
        $order = new self(
            $id,
            $userId,
            $userName,
            $destination,
            $departureDate,
            $arrivalDate,
            new RequestedState,
            $now,
            $now
        );

        $order->record(new OrderCreated($id, $userId, $now));

        return $order;
    }

    public static function restore(
        string $id,
        string $userId,
        string $userName,
        string $destination,
        DateTimeImmutable $departureDate,
        DateTimeImmutable $arrivalDate,
        OrderState $state,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt
    ): Order {
        return new self(
            $id,
            $userId,
            $userName,
            $destination,
            $departureDate,
            $arrivalDate,
            $state,
            $createdAt,
            $updatedAt
        );
    }

    private function __construct(
        string $id,
        string $userId,
        string $userName,
        string $destination,
        DateTimeImmutable $departureDate,
        DateTimeImmutable $arrivalDate,
        OrderState $state,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->userName = $userName;
        $this->destination = $destination;
        $this->departureDate = $departureDate;
        $this->arrivalDate = $arrivalDate;
        $this->state = $state;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getDestination(): string
    {
        return $this->destination;
    }

    public function getDepartureDate(): DateTimeImmutable
    {
        return $this->departureDate;
    }

    public function getArrivalDate(): DateTimeImmutable
    {
        return $this->arrivalDate;
    }

    public function getStatus(): OrderStatus
    {
        return $this->state->value();
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function approve(): void
    {
        $this->state->approve();
        $this->record(new OrderApproved(
            $this->id,
            $this->userId,
            new DateTimeImmutable
        ));
    }

    public function cancel(): void
    {
        $this->state->cancel();
        $this->record(new OrderCanceled(
            $this->id,
            $this->userId,
            new DateTimeImmutable
        ));
    }

    private function record(mixed $event): void
    {
        $this->events[] = $event;
    }

    public function pullEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}
