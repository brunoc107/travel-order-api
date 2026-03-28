<?php

namespace App\Infra\Http\Resources\Order;

use App\Domain\Order\ValueObjects\OrderStatus;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderResource',
    required: [
        'id',
        'userId',
        'userName',
        'destination',
        'departureDate',
        'arrivalDate',
        'status',
        'createdAt',
        'updatedAt',
    ],
    type: 'object'
)]
class OrderResource extends JsonResource
{
    #[OA\Property(
        property: 'id',
        description: 'Order Id',
        type: 'string',
        example: '01KMKXT8169DZBFAY64958180J'
    )]
    #[OA\Property(
        property: 'userId',
        description: 'User Id',
        type: 'string',
        example: '01kmknec99e43swm1r86yryt38'
    )]
    #[OA\Property(
        property: 'userName',
        description: 'User name',
        type: 'string',
        example: 'John Doe'
    )]
    #[OA\Property(
        property: 'destination',
        description: 'Travel destination',
        type: 'string',
        example: 'São Paulo - SP'
    )]
    #[OA\Property(
        property: 'departureDate',
        description: 'Departure date',
        type: 'string',
        example: '2026-05-21'
    )]
    #[OA\Property(
        property: 'arrivalDate',
        description: 'Arrival date',
        type: 'string',
        example: '2026-05-28'
    )]
    #[OA\Property(
        property: 'status',
        description: 'Order status',
        type: 'string',
        example: 'approved',
        enum: [OrderStatus::APPROVED, OrderStatus::APPROVED, OrderStatus::CANCELED]
    )]
    #[OA\Property(
        property: 'createdAt',
        description: 'Created at',
        type: 'string',
        example: '2026-05-28 01:25:12'
    )]
    #[OA\Property(
        property: 'updatedAt',
        description: 'Updated at',
        type: 'string',
        example: '2026-05-28 01:27:01'
    )]
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
