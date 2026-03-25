<?php

namespace App\Infra\Database\Eloquent;

use App\Domain\Order\ValueObjects\OrderStatus;
use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    protected $table = 'orders';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'user_name',
        'destination',
        'departure_date',
        'arrival_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'departure_date' => 'datetime',
            'arrival_date' => 'datetime',
            'status' => OrderStatus::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
