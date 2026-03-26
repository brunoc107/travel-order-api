<?php

namespace App\Infra\Providers;

use App\Domain\Order\Repository\OrderRepository;
use App\Domain\Shared\EventBus;
use App\Infra\Bus\LaravelEventBus;
use App\Infra\Database\Repositories\EloquentOrderRepository;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(OrderRepository::class, EloquentOrderRepository::class);
        $this->app->bind(EventBus::class, LaravelEventBus::class);
    }

    public function boot(): void
    {
        Blade::componentNamespace('App\\Infra\\View\\Components\\mail\\', 'mail');
    }
}
