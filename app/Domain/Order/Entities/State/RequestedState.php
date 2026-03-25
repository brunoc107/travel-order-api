<?php

namespace App\Domain\Order\Entities\State;

use App\Domain\Order\ValueObjects\OrderStatus;

class RequestedState implements OrderState
{
    public function value(): OrderStatus
    {
        return OrderStatus::REQUESTED;
    }

    public function approve(): OrderState
    {
        return new RequestedState;
    }

    public function cancel(): OrderState
    {
        return new CanceledState;
    }
}
