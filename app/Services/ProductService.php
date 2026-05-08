<?php

namespace App\Services;

use App\Contracts\ProductRepositoryInterface;
use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    protected $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function all(): LengthAwarePaginator
    {
        return $this->repository->all();
    }

    public function find(int $id): ?Product
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Product
    {
        // Business rules
        if (isset($data['sale_price']) && $data['sale_price'] >= $data['price']) {
            throw new \InvalidArgumentException('Sale price must be less than regular price.');
        }

        // Single-vendor: set default vendor if not provided (and if it exists)
        if (!isset($data['vendor_id'])) {
            $singleVendorId = (int) config('single_vendor.vendor_id');

            if ($singleVendorId > 0 && Schema::hasColumn('products', 'vendor_id')) {
                $vendorExists = !Schema::hasTable('vendors') || Vendor::whereKey($singleVendorId)->exists();

                if ($vendorExists) {
                    $data['vendor_id'] = $singleVendorId;
                }
            }
        }

        $product = $this->repository->create($data);

<<<<<<< HEAD
=======
        // Handle images
        if (isset($data['images'])) {
            foreach ($data['images'] as $imagePath) {
                // Logic for images
            }
        }

>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        return $product;
    }

    public function update(int $id, array $data): ?Product
    {
        if (isset($data['sale_price']) && $data['sale_price'] >= $data['price']) {
            throw new \InvalidArgumentException('Sale price must be less than regular price.');
        }

        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function search(string $query): LengthAwarePaginator
    {
        return $this->repository->search($query);
    }

    public function lowStock(int $threshold = 10): LengthAwarePaginator
    {
        return $this->repository->getLowStock($threshold);
    }
}

