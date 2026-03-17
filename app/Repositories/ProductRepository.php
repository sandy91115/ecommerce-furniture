<?php

namespace App\Repositories;

use App\Contracts\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductRepositoryInterface
{
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function all(): LengthAwarePaginator
    {
        return $this->model->with(['category', 'vendor'])->paginate(10);
    }

    public function find(int $id): ?Product
    {
        return $this->model->with(['category', 'vendor', 'images', 'variations'])->find($id);
    }

    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            return $this->model->create($data);
        });
    }

    public function update(int $id, array $data): bool
    {
        $product = $this->find($id);
        if (!$product) return false;

        return DB::transaction(function () use ($product, $data) {
            return $product->update($data);
        });
    }

    public function delete(int $id): bool
    {
        $product = $this->find($id);
        if (!$product) return false;

        return DB::transaction(function () use ($product) {
            // Delete images etc.
            foreach ($product->images as $image) {
                $image->delete();
            }
            return $product->delete();
        });
    }

    public function search(string $query): LengthAwarePaginator
    {
        return $this->model->where('name', 'like', "%{$query}%")
            ->orWhere('sku', 'like', "%{$query}%")
            ->with(['category'])
            ->paginate(10);
    }

    public function getLowStock(int $threshold = 10): LengthAwarePaginator
    {
        return $this->model->where('stock', '<=', $threshold)
            ->where('status', 'active')
            ->with('vendor')
            ->paginate(10);
    }
}

