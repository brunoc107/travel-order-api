<?php

namespace App\Infra\Listeners;

use App\Domain\Order\Events\OrderCanceled;
use App\Domain\Order\Repository\OrderRepository;
use App\Infra\Database\Eloquent\User;
use App\Infra\Mail\OrderCanceledMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendOrderCanceledMailNotification implements ShouldQueue
{
    public string $queue = 'mail';

    public function __construct(
        private readonly OrderRepository $orderRepository,
    ) {}

    public function handle(OrderCanceled $event): void
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
            ->send(new OrderCanceledMail(
                $user->name,
                $order->getId(),
                $order->getDestination(),
                $order->getDepartureDate(),
                $order->getArrivalDate())
            );
    }
}
