<?php

namespace App\Infra\Listeners;

use App\Domain\Order\Events\OrderApproved;
use App\Domain\Order\Repository\OrderRepository;
use App\Infra\Database\Eloquent\User;
use App\Infra\Mail\OrderApprovedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendOrderApprovedMailNotification implements ShouldQueue
{
    public string $queue = 'mail';

    public function __construct(
        private readonly OrderRepository $orderRepository,
    ) {}

    public function handle(OrderApproved $event): void
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
            ->send(new OrderApprovedMail(
                    $user->name,
                    $order->getId(),
                    $order->getDestination(),
                    $order->getDepartureDate(),
                    $order->getArrivalDate())
            );
    }
}
