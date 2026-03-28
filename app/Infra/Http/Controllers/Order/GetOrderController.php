<?php

namespace App\Infra\Http\Controllers\Order;

use App\Application\Order\UseCases\GetOrderUseCase;
use App\Domain\Order\Exception\OrderNotFoundException;
use App\Infra\Http\Resources\Order\OrderResource;
use Domain\User\ValueObjects\UserRole;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class GetOrderController extends Controller
{
    public function __construct(private readonly GetOrderUseCase $useCase) {}

    #[OA\Get(
        path: '/api/orders/{orderId}',
        operationId: 'get-order',
        description: 'Get a specific order',
        summary: 'Get a specific order',
        security: [['bearerAuth' => []]],
        tags: ['Orders'],
        parameters: [
            new OA\Parameter(
                name: 'orderId',
                description: 'ID of the order to retrieve',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', example: '01F8MECHZX3TBDSZ7XRADM79XE')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Order found',
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
            new OA\Response(
                response: 404,
                description: 'Not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Order not found'),
                    ]
                )
            ),
        ],
    )]
    public function __invoke(
        string $orderId,
    ): JsonResponse|JsonResource {
        try {
            $user = Auth::user();

            $userId = null;
            if ($user->role !== UserRole::ADMIN) {
                $userId = $user->id;
            }

            $order = $this->useCase->execute($orderId, $userId);

            return new OrderResource($order);
        } catch (OrderNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
