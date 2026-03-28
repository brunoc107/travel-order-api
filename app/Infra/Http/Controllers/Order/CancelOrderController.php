<?php

namespace App\Infra\Http\Controllers\Order;

use App\Application\Order\UseCases\CancelOrderUseCase;
use App\Domain\Order\Exception\InvalidOrderActionException;
use App\Infra\Http\Controllers\Controller;
use Domain\User\ValueObjects\UserRole;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class CancelOrderController extends Controller
{
    public function __construct(private readonly CancelOrderUseCase $useCase) {}

    #[OA\Patch(
        path: '/api/orders/{orderId}/cancel',
        operationId: 'cancel-order',
        description: 'Cancel an order',
        summary: 'Order cancellation',
        security: [['bearerAuth' => []]],
        tags: ['Orders'],
        parameters: [
            new OA\Parameter(
                name: 'orderId',
                description: 'ID of the order to canceled',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', example: '01F8MECHZX3TBDSZ7XRADM79XE')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Order canceled',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Order canceled'),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Bad request',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: ['Order already canceled', 'Canceled order cannot be approved']),
                    ]
                )
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
    public function __invoke(string $orderId)
    {
        try {
            $user = Auth::user();
            if ($user->role !== UserRole::ADMIN) {
                return response()->json(['message' => 'Forbidden'], 403);
            }

            $this->useCase->execute($orderId);

            return response()->json(['message' => 'Order canceled']);
        } catch (InvalidOrderActionException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
