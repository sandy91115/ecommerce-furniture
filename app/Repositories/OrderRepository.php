<?php

namespace App\Repositories;

use App\Contracts\OrderRepositoryInterface;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
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
        return $this->model->with(['user', 'vendor', 'orderItems.product.images'])->latest()->paginate(15);
    }

    public function find(int $id): ?Order
    {
        return $this->model->with(['user', 'vendor', 'orderItems.product.images'])->find($id);
    }

    public function create(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $items = $this->normalizeItemsPayload($data['items'] ?? []);
            $data['items'] = $items;

            $order = $this->model->create($data);
            $products = Product::whereIn('id', collect($items)->pluck('product_id')->filter()->unique()->all())
                ->get()
                ->keyBy('id');

            foreach ($items as $item) {
                $productId = $item['product_id'] ?? $item['id'] ?? null;
                $quantity = (int) ($item['quantity'] ?? $item['qty'] ?? 0);
                $price = (float) ($item['price'] ?? 0);

                if (! $productId || $quantity < 1) {
                    continue;
                }

                $product = $products->get($productId);

                $order->orderItems()->create([
                    'product_id' => $productId,
                    'product_name' => $item['product_name'] ?? $item['name'] ?? $product?->name ?? 'Product',
                    'product_sku' => $item['product_sku'] ?? $item['sku'] ?? $product?->sku,
                    'price' => $price,
                    'quantity' => $quantity,
                    'total' => $price * $quantity,
                    'variant_id' => $item['variation_id'] ?? null,
                    'variation_data' => ! empty($item['attributes']) ? $item['attributes'] : ($item['variation_data'] ?? null),
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

    protected function normalizeItemsPayload(mixed $items): array
    {
        if ($items instanceof Collection) {
            return $items->toArray();
        }

        if (is_array($items)) {
            return $items;
        }

        if (! is_string($items) || $items === '') {
            return [];
        }

        $decoded = json_decode($items, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }

        if (is_array($decoded)) {
            return $decoded;
        }

        if (is_string($decoded)) {
            $decoded = json_decode($decoded, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        return [];
    }
}
