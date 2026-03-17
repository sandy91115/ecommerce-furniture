<?php

namespace App\Services;

use App\Contracts\OrderRepositoryInterface;
use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderService
{
    protected $repository;

    public function __construct(OrderRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function all(): LengthAwarePaginator
    {
        return $this->repository->all();
    }

    public function find(int $id): ?Order
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Order
    {
        $data['order_number'] = $data['order_number'] ?? 'ORD-' . strtoupper(uniqid());
        return $this->repository->create($data);
    }

    public function update(int $id, array $data): bool
    {
        // Business validation
        $order = $this->repository->find($id);
        if (!$order) return false;

        if (in_array($order->status, ['delivered', 'cancelled']) && isset($data['status']) && $data['status'] !== $order->status) {
            throw new \InvalidArgumentException('Cannot change status of completed/cancelled order.');
        }

        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function recent(int $limit = 5): \Illuminate\Support\Collection
    {
        return $this->repository->recent($limit);
    }

    public function byStatus(string $status): LengthAwarePaginator
    {
        return $this->repository->byStatus($status);
    }
}

