<?php

namespace App\Infra\Http\Requests;

use App\Domain\Order\ValueObjects\OrderStatus;
use DateTimeImmutable;
use Illuminate\Foundation\Http\FormRequest;

class ListOrdersRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => 'sometimes|string|in:'.implode(',', OrderStatus::values()),
            'userId' => 'sometimes|ulid',
            'destination' => 'sometimes|string',
            'departureDate' => 'sometimes|date|',
            'arrivalDate' => 'sometimes|date|after:departureDate',
            'page' => 'sometimes|int|gt:0',
            'perPage' => 'sometimes|int|gt:0',
        ];
    }

    public function status(): ?OrderStatus
    {
        $status = $this->input('status');
        if (! $status) {
            return null;
        }

        return OrderStatus::from($status);
    }

    public function userId(): ?string
    {
        return $this->query('userId');
    }

    public function destination(): ?string
    {
        return $this->query('destination');
    }

    public function departureDate(): ?DateTimeImmutable
    {
        $date = $this->input('departureDate');
        if (! $date) {
            return null;
        }

        return new DateTimeImmutable($date);
    }

    public function arrivalDate(): ?DateTimeImmutable
    {
        $date = $this->input('arrivalDate');
        if (! $date) {
            return null;
        }

        return new DateTimeImmutable($date);
    }

    public function page(): int
    {
        return $this->input('page') ?? 1;
    }

    public function perPage(): int
    {
        return $this->input('perPage') ?? 10;
    }
}
