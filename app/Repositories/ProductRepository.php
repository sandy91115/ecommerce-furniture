<?php

namespace App\Repositories;

use App\Contracts\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ProductRepository implements ProductRepositoryInterface
{
    protected Product $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function all(): LengthAwarePaginator
    {
        return $this->model->with(['category', 'vendor'])->paginate(15);
    }

    public function find(int $id): ?Product
    {
        return $this->model->with(['category', 'vendor', 'images'])->find($id);
    }

    public function create(array $data): Product
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): ?Product
    {
        $product = $this->model->withTrashed()->find($id);
        if (!$product) {
            return null;
        }
        $product->update($data);
        return $product->fresh(['category', 'vendor']);
    }

    public function delete(int $id): bool
    {
        $product = $this->find($id);
        return $product ? $product->delete() : false;
    }

    public function search(string $query): LengthAwarePaginator
    {
        return $this->model->where('name', 'like', "%{$query}%")
            ->orWhere('sku', 'like', "%{$query}%")
            ->with(['category', 'vendor'])
            ->paginate(15);
    }

    public function getLowStock(int $threshold = 10): LengthAwarePaginator
    {
        return $this->model->where('stock', '<=', $threshold)
            ->where('status', 'active')
            ->with(['category', 'vendor'])
            ->paginate(15);
    }
}

