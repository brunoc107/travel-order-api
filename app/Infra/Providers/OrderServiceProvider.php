<?php

namespace App\Infra\Providers;

use App\Domain\Order\Repository\OrderRepository;
use App\Infra\Database\Repositories\EloquentOrderRepository;
use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(OrderRepository::class, EloquentOrderRepository::class);
    }
}
