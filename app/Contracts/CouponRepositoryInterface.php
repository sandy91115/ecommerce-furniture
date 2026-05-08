<?php

namespace App\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Coupon;

interface CouponRepositoryInterface
{
    public function all(): LengthAwarePaginator;
    public function find(int $id): ?Coupon;
    public function create(array $data): Coupon;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function findByCode(string $code): ?Coupon;
    public function validCoupons(): LengthAwarePaginator;
}

