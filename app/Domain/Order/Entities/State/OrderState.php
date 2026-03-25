<?php

namespace App\Domain\Order\Entities\State;

use App\Domain\Order\ValueObjects\OrderStatus;

interface OrderState
{
    public function value(): OrderStatus;

    public function approve(): OrderState;

    public function cancel(): OrderState;
}
