<?php

namespace App\Infra\Http\Controllers\Order;

use App\Application\Order\UseCases\CreateOrderUseCase;
use App\Infra\Http\Controllers\Controller;
use App\Infra\Http\Requests\CreateOrderRequest;
use App\Infra\Http\Resources\Order\OrderResource;
use DateTimeImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class CreateOrderController extends Controller
{
    public function __construct(private readonly CreateOrderUseCase $useCase) {}

    /**
     * @throws \DateMalformedStringException
     */
    #[OA\Post(
        path: '/api/orders',
        operationId: 'create-order',
        description: 'Create a new order',
        summary: 'Order creation',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/CreateOrderRequest')
        ),
        tags: ['Orders'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Order created',
                content: new OA\JsonContent(ref: '#/components/schemas/OrderResource')
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Unauthorized'),
                    ]
                )
            ),
        ],
    )]
    public function __invoke(
        CreateOrderRequest $request,
    ): JsonResponse {
        $user = Auth::user();

        $order = $this->useCase->execute(
            userId: $user->id,
            userName: $user->name,
            destination: $request->input('destination'),
            departureDate: new DateTimeImmutable($request->input('departureDate')),
            arrivalDate: new DateTimeImmutable($request->input('arrivalDate')),
        );

        return new OrderResource($order)->response()->setStatusCode(201);
    }
}
