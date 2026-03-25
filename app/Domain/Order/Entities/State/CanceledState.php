<?php

namespace App\Domain\Order\Entities\State;

use App\Domain\Order\ValueObjects\OrderStatus;
use DomainException;

class CanceledState implements OrderState
{
    public function value(): OrderStatus
    {
        return OrderStatus::CANCELED;
    }

    public function approve(): OrderState
    {
        throw new DomainException('Cancelled order cannot be approved');
    }

    public function cancel(): OrderState
    {
        throw new DomainException('Order already canceled');
    }
}
