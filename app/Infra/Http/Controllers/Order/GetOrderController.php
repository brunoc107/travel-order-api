<?php

namespace App\Infra\Http\Controllers\Order;

use App\Application\Order\UseCases\GetOrderUseCase;
use App\Infra\Http\resources\Order\OrderResource;
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
        $user = Auth::user();

        $userId = null;
        if ($user->role !== UserRole::ADMIN) {
            $userId = $user->id;
        }

        $order = $this->useCase->execute($orderId, $userId);

        if (! $order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return new OrderResource($order);
    }
}
