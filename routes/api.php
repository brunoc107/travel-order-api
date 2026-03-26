<?php

use App\Infra\Http\Controllers\AuthController;
use App\Infra\Http\Controllers\Order\ApproveOrderController;
use App\Infra\Http\Controllers\Order\CancelOrderController;
use App\Infra\Http\Controllers\Order\CreateOrderController;
use App\Infra\Http\Controllers\Order\GetOrderController;
use App\Infra\Http\Controllers\Order\ListOrdersController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('orders', CreateOrderController::class);
        Route::get('orders', ListOrdersController::class);
        Route::get('orders/{id}', GetOrderController::class);
        Route::patch('orders/{id}/approve', ApproveOrderController::class);
        Route::patch('orders/{id}/cancel', CancelOrderController::class);
    });
});
