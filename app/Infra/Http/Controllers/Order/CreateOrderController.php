<?php

namespace App\Infra\Http\Controllers\Order;

use App\Application\Order\UseCases\CreateOrderUseCase;
use App\Infra\Http\Controllers\Controller;
use App\Infra\Http\Requests\CreateOrderRequest;
use App\Infra\Http\resources\Order\OrderResource;
use DateTimeImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CreateOrderController extends Controller
{
    public function __construct(private readonly CreateOrderUseCase $useCase) {}

    public function __invoke(
        CreateOrderRequest $request,
    ): JsonResponse {
        $user = Auth::user();

        $order = $this->useCase->execute(
            userId: $user->id,
            userName: $user->name,
            destination: $request->input('destination'),
            departureDate: new DateTimeImmutable($request->input('departure_date')),
            arrivalDate: new DateTimeImmutable($request->input('arrival_date')),
        );

        return new OrderResource($order)->response()->setStatusCode(201);
    }
}
