<?php

namespace App\Infra\Http\Controllers\Order;

use App\Application\Order\UseCases\ListOrdersUseCase;
use App\Domain\Order\Repository\OrderCriteria;
use App\Domain\Order\Repository\Pagination;
use App\Infra\Http\Requests\ListOrdersRequest;
use App\Infra\Http\Resources\Order\OrderCollectionResource;
use Domain\User\ValueObjects\UserRole;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class ListOrdersController extends Controller
{
    public function __construct(private readonly ListOrdersUseCase $useCase) {}

    #[OA\Get(
        path: '/api/orders',
        operationId: 'list-order',
        description: 'List all orders',
        summary: 'List all orders (if user has admin role, can list from all users omitting userId query parameter)',
        security: [['bearerAuth' => []]],
        tags: ['Orders'],
        parameters: [
            new OA\Parameter(name: 'status', in: 'query', required: false, schema: new OA\Schema(type: 'string', example: 'requested', enum: ['requested', 'approved', 'canceled'])),
            new OA\Parameter(name: 'userId', in: 'query', required: false, schema: new OA\Schema(type: 'string', example: '01kmknec99e43swm1r86yryt38')),
            new OA\Parameter(name: 'destination', in: 'query', required: false, schema: new OA\Schema(type: 'string', example: 'São Paulo - SP')),
            new OA\Parameter(name: 'departureDate', in: 'query', required: false, schema: new OA\Schema(type: 'string', example: '2026-10-21')),
            new OA\Parameter(name: 'arrivalDate', in: 'query', required: false, schema: new OA\Schema(type: 'string', example: '2026-10-27')),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 1)),
            new OA\Parameter(name: 'perPage', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 10)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Orders list',
                content: new OA\JsonContent(ref: '#/components/schemas/OrderCollectionResource')
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
                response: 403,
                description: 'Forbidden',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Forbidden'),
                    ]
                )
            ),
        ],
    )]
    public function __invoke(ListOrdersRequest $request)
    {
        $user = Auth::user();
        $userId = $request->userId();

        if ($user->role !== UserRole::ADMIN) {
            if ($userId && $userId !== $user->id) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
            $userId = $user->id;
        }

        $page = $this->useCase->execute(
            new OrderCriteria(
                $request->status(),
                $userId, $request->destination(),
                $request->departureDate(),
                $request->arrivalDate()
            ),
            new Pagination(
                $request->page(),
                $request->perPage()
            )
        );

        return new OrderCollectionResource(
            $page->items,
            $page->total,
            $request->page(),
            $request->perPage()
        );
    }
}
