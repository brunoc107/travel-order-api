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

class GetOrderController extends Controller
{
    public function __construct(private readonly GetOrderUseCase $useCase) {}

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
