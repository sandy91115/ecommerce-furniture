<?php

namespace App\Contracts;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    public function all(): LengthAwarePaginator;
    public function find(int $id): ?Order;
    public function create(array $data): Order;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function recent(int $limit = 5): \Illuminate\Support\Collection;
    public function byStatus(string $status): LengthAwarePaginator;
}

