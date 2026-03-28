<?php

namespace App\Infra\Database\Repository;

use App\Domain\Order\Entities\Order;
use App\Domain\Order\Repository\OrderCriteria;
use App\Domain\Order\Repository\OrderRepository;
use App\Domain\Order\Repository\Page;
use App\Domain\Order\Repository\Pagination;
use App\Infra\Database\Eloquent\OrderModel;
use App\Infra\Database\Mapper\OrderMapper;
use Illuminate\Support\Collection;

class EloquentOrderRepository implements OrderRepository
{
    public function save(Order $order): void
    {
        $model = OrderModel::find($order->getId());

        if ($model) {
            $updatedModel = OrderMapper::toModel($order);
            $model->fill($updatedModel->getAttributes());
        } else {
            $model = OrderMapper::toModel($order);
        }

        $model->save();
    }

    public function findMany(OrderCriteria $criteria, Pagination $pagination): Page
    {
        $query = OrderModel::query();

        $query
            ->when($criteria->status, fn ($q) => $q->where('status', $criteria->status))
            ->when($criteria->userId, fn ($q) => $q->where('user_id', $criteria->userId))
            ->when($criteria->destination, fn ($q) => $q->where('destination', $criteria->destination))
            ->when($criteria->departureDateTime, fn ($q) => $q->where('departure_date', '>=', $criteria->departureDateTime))
            ->when($criteria->arrivalDateTime, fn ($q) => $q->where('arrival_date', '<=', $criteria->arrivalDateTime));

        $result = $query->orderBy('created_at')->paginate(
            perPage: $pagination->perPage,
            page: $pagination->page
        );

        return new Page(
            items: collect($result->items())->map(
                fn (OrderModel $model) => OrderMapper::toDomain($model)
            ),
            total: $result->total(),
            page: $result->currentPage(),
            perPage: $result->perPage(),
        );
    }

    public function findOrderById(string $id, ?string $userId = null): ?Order
    {
        $query = OrderModel::query()->where('id', $id);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $model = $query->first();

        if (! $model) {
            return null;
        }

        return OrderMapper::toDomain($model);
    }

    /**
     * @return Collection<Order>
     */
    public function findOrdersByUserId(string $userId): Collection
    {
        $models = collect(OrderModel::where('user_id', $userId)->orderBy('created_at', 'desc')->get());

        return $models->map(fn ($model) => OrderMapper::toDomain($model));
    }
}
