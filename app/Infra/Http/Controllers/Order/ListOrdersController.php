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

class ListOrdersController extends Controller
{
    public function __construct(private readonly ListOrdersUseCase $useCase) {}

    public function __invoke(ListOrdersRequest $request)
    {
        $user = Auth::user();
        $userId = $request->userId();
        if ($user->role !== UserRole::ADMIN && $userId !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
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
