<?php

namespace App\Infra\Http\Controllers\Order;

use App\Application\Order\UseCases\CancelOrderUseCase;
use App\Domain\Order\Exception\InvalidOrderActionException;
use App\Infra\Http\Controllers\Controller;
use Domain\User\ValueObjects\UserRole;
use Illuminate\Support\Facades\Auth;

class CancelOrderController extends Controller
{
    public function __construct(private readonly CancelOrderUseCase $useCase) {}

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
