<?php

namespace App\Services;

use App\Contracts\OrderRepositoryInterface;
<<<<<<< HEAD
use App\Enums\OrderStatus;
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
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
<<<<<<< HEAD
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
=======
        // Business validation
        $order = $this->repository->find($id);
        if (!$order) return false;

        if (in_array($order->status, ['delivered', 'cancelled']) && isset($data['status']) && $data['status'] !== $order->status) {
            throw new \InvalidArgumentException('Cannot change status of completed/cancelled order.');
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        }

        return $this->repository->update($id, $data);
    }

<<<<<<< HEAD
    private function statusValue(OrderStatus|string|null $status): ?string
    {
        return $status instanceof OrderStatus ? $status->value : $status;
    }

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
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

