<?php

namespace App\Domain\Order\ValueObjects;

enum OrderStatus: string
{
    case REQUESTED = 'requested';
    case APPROVED = 'approved';
    case CANCELED = 'cancelled';
}
