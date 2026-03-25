<?php

namespace App\Domain\Shared;

interface EventBus
{
    public function dispatch(object $event): void;

    public function dispatchAll(array $events): void;
}
