<?php

namespace App\Repositories;

use App\Contracts\OrderRepositoryInterface;
use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
{
    protected $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function all(): LengthAwarePaginator
    {
        return $this->model->with(['user', 'vendor'])->latest()->paginate(15);
    }

    public function find(int $id): ?Order
    {
        return $this->model->with(['user', 'vendor'])->find($id);
    }

    public function create(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $items = $data['items'] ?? [];
            
            // Ensure items is passed as a value that can be JSON cast by the model
            $order = $this->model->create($data);

            foreach ($items as $item) {
                $order->items()->create([
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'product_sku' => $item['sku'] ?? null,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total' => $item['price'] * $item['quantity'],
                    'variant_id' => $item['variation_id'] ?? null,
                    'variation_data' => !empty($item['attributes']) ? $item['attributes'] : null,
                ]);
            }

            return $order;
        });
    }

    public function update(int $id, array $data): bool
    {
        $order = $this->find($id);
        if (!$order) return false;

        return DB::transaction(function () use ($order, $data) {
            return $order->update($data);
        });
    }

    public function delete(int $id): bool
    {
        $order = $this->find($id);
        if (!$order) return false;

        return $order->delete();
    }

    public function recent(int $limit = 5): \Illuminate\Support\Collection
    {
        return $this->model->with(['user'])->latest()->limit($limit)->get();
    }

    public function byStatus(string $status): LengthAwarePaginator
    {
        return $this->model->with(['user', 'vendor'])
            ->where('status', $status)
            ->latest()
            ->paginate(15);
    }
}
