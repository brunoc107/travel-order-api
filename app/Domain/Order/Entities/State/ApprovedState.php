<?php

namespace App\Domain\Order\Entities\State;

use App\Domain\Order\ValueObjects\OrderStatus;
use DomainException;

class ApprovedState implements OrderState
{
    public function value(): OrderStatus
    {
        return OrderStatus::APPROVED;
    }

    public function approve(): OrderState
    {
        throw new DomainException('Order already approved');
    }

    public function cancel(): OrderState
    {
        throw new DomainException('Approved order cannot be canceled');
    }
}
