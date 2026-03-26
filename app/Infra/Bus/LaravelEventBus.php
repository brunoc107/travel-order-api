<?php

namespace App\Infra\Bus;

use App\Domain\Shared\EventBus;
use Illuminate\Contracts\Events\Dispatcher;

readonly class LaravelEventBus implements EventBus
{
    public function __construct(
        private Dispatcher $dispatcher
    ) {}

    public function dispatch(object $event): void
    {
        $this->dispatcher->dispatch($event);
    }

    public function dispatchAll(array $events): void
    {
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }
}
