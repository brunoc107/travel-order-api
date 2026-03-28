<?php

namespace App\Infra\Http\Controllers\Order;

use App\Application\Order\UseCases\ApproveOrderUseCase;
use App\Domain\Order\Exception\InvalidOrderActionException;
use App\Domain\Order\Exception\OrderNotFoundException;
use App\Infra\Http\Controllers\Controller;
use Domain\User\ValueObjects\UserRole;
use Illuminate\Support\Facades\Auth;

class ApproveOrderController extends Controller
{
    public function __construct(private readonly ApproveOrderUseCase $useCase) {}

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
