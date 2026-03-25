<?php

namespace App\Domain\Order\Entities\State;

use App\Domain\Order\ValueObjects\OrderStatus;

final class OrderStateFactory
{
    public static function from(OrderStatus $status): OrderState
    {
        return match ($status) {
            OrderStatus::REQUESTED => new RequestedState,
            OrderStatus::APPROVED => new ApprovedState,
            OrderStatus::CANCELED => new CanceledState,
        };
    }
}
