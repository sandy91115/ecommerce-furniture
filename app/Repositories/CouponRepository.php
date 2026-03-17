<?php

namespace App\Repositories;

use App\Contracts\CouponRepositoryInterface;
use App\Models\Coupon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CouponRepository implements CouponRepositoryInterface
{
    public function all(): LengthAwarePaginator
    {
        return Coupon::query()
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }

    public function find(int $id): ?Coupon
    {
        return Coupon::find($id);
    }

    public function create(array $data): Coupon
    {
        return Coupon::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $coupon = $this->find($id);
        return $coupon ? $coupon->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $coupon = $this->find($id);
        return $coupon ? $coupon->delete() : false;
    }

    public function findByCode(string $code): ?Coupon
    {
        return Coupon::where('code', $code)->first();
    }

    public function validCoupons(): LengthAwarePaginator
    {
        return Coupon::where('status', true)
            ->where('valid_until', '>', now())
            ->whereRaw('used_count < max_uses')
            ->paginate(15);
    }
}

