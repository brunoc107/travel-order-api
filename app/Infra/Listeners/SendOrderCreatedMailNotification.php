<?php

namespace App\Infra\Listeners;

use App\Domain\Order\Events\OrderCreated;
use App\Domain\Order\Repository\OrderRepository;
use App\Infra\Database\Eloquent\User;
use App\Infra\Mail\OrderCreatedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendOrderCreatedMailNotification implements ShouldQueue
{
    public string $queue = 'mail';

    public function __construct(
        private readonly OrderRepository $orderRepository,
    ) {}

    public function handle(OrderCreated $event): void
    {
        $order = $this->orderRepository->findOrderById($event->orderId);

        if (! $order) {
            return;
        }

        $user = User::query()->where('id', $order->getUserId())->first();

        if (! $user) {
            return;
        }
        Mail::to($user->email)
            ->send(new OrderCreatedMail(
                $user->name,
                $order->getId(),
                $order->getDestination(),
                $order->getDepartureDate(),
                $order->getArrivalDate())
            );
    }
}
