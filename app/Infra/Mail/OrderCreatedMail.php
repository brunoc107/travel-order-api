<?php

namespace App\Infra\Mail;

use DateTimeImmutable;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param string $user
     * @param string $orderId
     * @param string $destination
     * @param DateTimeImmutable $departureDate
     * @param DateTimeImmutable $arrivalDate
     */
    public function __construct(
        private readonly string $user,
        private readonly string $orderId,
        private readonly string $destination,
        private readonly DateTimeImmutable $departureDate,
        private readonly DateTimeImmutable $arrivalDate,
    ) {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Demo - Recebemos seu pedido',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.order-created',
            text: 'mail.order-created-text',
            with: [
                'user' => $this->user,
                'orderId' => $this->orderId,
                'destination' => $this->destination,
                'departureDate' => $this->departureDate->format('d/m/Y'),
                'arrivalDate' => $this->arrivalDate->format('d/m/Y'),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
