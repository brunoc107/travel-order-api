<?php

namespace App\Domain\Order\Entities\State;

use App\Domain\Order\Exception\InvalidOrderActionException;
use App\Domain\Order\ValueObjects\OrderStatus;

class ApprovedState implements OrderState
{
    public function value(): OrderStatus
    {
        return OrderStatus::APPROVED;
    }

    public function approve(): OrderState
    {
        throw new InvalidOrderActionException('Order already approved');
    }

    public function cancel(): OrderState
    {
        throw new InvalidOrderActionException('Approved order cannot be canceled');
    }
}
