<?php

namespace App\Infra\Database\Mappers;

use App\Domain\Order\Entities\Order;
use App\Domain\Order\Entities\State\OrderStateFactory;
use App\Infra\Database\Eloquent\OrderModel;
use DateTimeImmutable;

class OrderMapper
{
    public static function toDomain(OrderModel $model): Order
    {
        return Order::restore(
            id: $model->id,
            userId: $model->user_id,
            userName: $model->user_name,
            destination: $model->destination,
            departureDate: new DateTimeImmutable($model->departure_date),
            arrivalDate: new DateTimeImmutable($model->arrival_date),
            state: OrderStateFactory::from($model->status),
            createdAt: new DateTimeImmutable($model->created_at),
            updatedAt: new DateTimeImmutable($model->updated_at)
        );
    }

    public static function toModel(Order $order): OrderModel
    {
        return new OrderModel([
            'id' => $order->getId(),
            'user_id' => $order->getUserId(),
            'user_name' => $order->getUserName(),
            'destination' => $order->getDestination(),
            'departure_date' => $order->getDepartureDate(),
            'arrival_date' => $order->getArrivalDate(),
            'status' => $order->getStatus()->value,
        ]);
    }
}
