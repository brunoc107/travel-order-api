<?php

namespace App\Infra\Http\Resources\Order;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderCollectionResource',
    required: ['data', 'meta'],
    type: 'object'
)]
class OrderCollectionResourceOpenApi
{
    #[OA\Property(
        property: 'data',
        description: 'List of orders',
        type: 'array',
        items: new OA\Items(ref: '#/components/schemas/OrderResource')
    )]
    public array $data;

    #[OA\Property(
        property: 'meta',
        description: 'Pagination metadata',
        properties: [
            new OA\Property(property: 'total', type: 'integer', example: 100),
            new OA\Property(property: 'per_page', type: 'integer', example: 10),
            new OA\Property(property: 'current_page', type: 'integer', example: 1),
        ],
        type: 'object'
    )]
    public array $meta;
}
