<?php

namespace App\Services;

use App\Contracts\CouponRepositoryInterface;
use App\Models\Coupon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CouponService
{
    protected $repository;

    public function __construct(CouponRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function all(): LengthAwarePaginator
    {
        return $this->repository->all();
    }

    public function find(int $id): ?Coupon
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Coupon
    {
        $data['code'] = strtoupper($data['code'] ?? uniqid());
        
        // Validation rules
        if (!isset($data['valid_from'])) {
            $data['valid_from'] = now();
        }
        if (!isset($data['valid_until'])) {
            $data['valid_until'] = now()->addYear();
        }

        if ($data['value'] <= 0) {
            throw new \InvalidArgumentException('Coupon value must be greater than 0.');
        }

        return $this->repository->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $coupon = $this->find($id);
        if (!$coupon) {
            throw new \InvalidArgumentException('Coupon not found.');
        }

        // Business validation
        if (isset($data['value']) && $data['value'] <= 0) {
            throw new \InvalidArgumentException('Coupon value must be greater than 0.');
        }

        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        $coupon = $this->find($id);
        if (!$coupon || $coupon->used_count > 0) {
            return false;
        }
        return $this->repository->delete($id);
    }

    public function validateCode(string $code, float $orderAmount = 0): array
    {
        $coupon = $this->repository->findByCode($code);
        if (!$coupon || !$coupon->isValid()) {
            return ['valid' => false, 'message' => 'Invalid or expired coupon.'];
        }

        $discount = $this->calculateDiscount($coupon, $orderAmount);

        return [
            'valid' => true,
            'coupon' => $coupon,
            'discount' => $discount,
        ];
    }

    private function calculateDiscount(Coupon $coupon, float $orderAmount): float
    {
        if ($coupon->type === 'percentage') {
            $discount = $orderAmount * ($coupon->value / 100);
            $discount = min($discount, $coupon->max_discount_amount ?? $discount);
        } else {
            $discount = $coupon->value;
        }

        return max(0, $discount);
    }

    public function validCoupons(): LengthAwarePaginator
    {
        return $this->repository->validCoupons();
    }
}

