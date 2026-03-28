<?php

namespace App\Infra\Http\Controllers\Order;

use App\Application\Order\UseCases\ApproveOrderUseCase;
use App\Domain\Order\Exception\InvalidOrderActionException;
use App\Domain\Order\Exception\OrderNotFoundException;
use App\Infra\Http\Controllers\Controller;
use Domain\User\ValueObjects\UserRole;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class ApproveOrderController extends Controller
{
    public function __construct(private readonly ApproveOrderUseCase $useCase) {}

    #[OA\Patch(
        path: '/api/orders/{orderId}/approve',
        operationId: 'approve-order',
        description: 'Approve an order',
        summary: 'Order approval',
        security: [['bearerAuth' => []]],
        tags: ['Orders'],
        parameters: [
            new OA\Parameter(
                name: 'orderId',
                description: 'ID of the order to approve',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', example: '01F8MECHZX3TBDSZ7XRADM79XE')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Order approved',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Order approved'),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Bad request',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: ['Order already approved', 'Approved order cannot be canceled']),
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

            return response()->json(['message' => 'Order approved']);
        } catch (InvalidOrderActionException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (OrderNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
