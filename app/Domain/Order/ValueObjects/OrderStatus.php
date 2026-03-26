<?php

namespace App\Domain\Order\ValueObjects;

enum OrderStatus: string
{
    case REQUESTED = 'requested';
    case APPROVED = 'approved';
    case CANCELED = 'cancelled';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_map(
            fn ($case) => $case->value,
            OrderStatus::cases()
        );
    }
}
