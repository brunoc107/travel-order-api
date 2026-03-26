<?php

namespace App\Domain\Order\Entities\State;

use App\Domain\Order\Exception\InvalidOrderActionException;
use App\Domain\Order\ValueObjects\OrderStatus;

class CanceledState implements OrderState
{
    public function value(): OrderStatus
    {
        return OrderStatus::CANCELED;
    }

    public function approve(): OrderState
    {
        throw new InvalidOrderActionException('Canceled order cannot be approved');
    }

    public function cancel(): OrderState
    {
        throw new InvalidOrderActionException('Order already canceled');
    }
}
