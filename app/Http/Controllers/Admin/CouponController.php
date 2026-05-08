<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CouponStoreRequest;
use App\Http\Requests\Admin\CouponUpdateRequest;
use App\Services\CouponService;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function index()
    {
        $this->authorize('coupons.view');

        $coupons = $this->couponService->all();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $this->authorize('coupons.create');

        return view('admin.coupons.create');
    }

    public function store(CouponStoreRequest $request)
    {
        $this->authorize('coupons.create');

        $data = $request->validated();
        $this->couponService->create($data);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function show(Coupon $coupon)
    {
        $this->authorize('coupons.view');

        return view('admin.coupons.show', compact('coupon'));
    }

    public function edit(Coupon $coupon)
    {
        $this->authorize('coupons.update');

        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(CouponUpdateRequest $request, Coupon $coupon)
    {
        $this->authorize('coupons.update');

        $data = $request->validated();
        $this->couponService->update($coupon->id, $data);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $this->authorize('coupons.delete');

        $this->couponService->delete($coupon->id);
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon moved to Recycle Bin successfully.');
    }
}

