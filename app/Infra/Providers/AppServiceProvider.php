<?php

namespace App\Infra\Providers;

use App\Domain\Shared\EventBus;
use App\Infra\Bus\LaravelEventBus;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(EventBus::class, LaravelEventBus::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
