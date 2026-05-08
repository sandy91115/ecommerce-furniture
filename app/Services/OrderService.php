<?php

namespace App\Services;

use App\Contracts\OrderRepositoryInterface;
use App\Enums\OrderStatus;
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
        $order = $this->repository->find($id);
        if (!$order) return false;

        $currentStatus = $this->statusValue($order->status);
        $requestedStatus = isset($data['status']) ? $this->statusValue($data['status']) : null;

        if (
            ($data['payment_status'] ?? $order->payment_status) === 'paid'
            && $currentStatus === OrderStatus::PENDING->value
            && ($requestedStatus === null || $requestedStatus === OrderStatus::PENDING->value)
        ) {
            $data['status'] = OrderStatus::PROCESSING->value;
        }

        return $this->repository->update($id, $data);
    }

    private function statusValue(OrderStatus|string|null $status): ?string
    {
        return $status instanceof OrderStatus ? $status->value : $status;
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

