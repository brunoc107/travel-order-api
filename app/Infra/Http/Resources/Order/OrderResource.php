<?php

namespace App\Infra\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->getId(),
            'user_id' => $this->getUserId(),
            'user_name' => $this->getUserName(),
            'destination' => $this->getDestination(),
            'departure_date' => $this->getDepartureDate()->format('Y-m-d'),
            'arrival_date' => $this->getArrivalDate()->format('Y-m-d'),
            'status' => $this->getStatus()->value,
            'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $this->getUpdatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}
