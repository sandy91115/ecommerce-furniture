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
<<<<<<< HEAD
        $this->authorize('coupons.view');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $coupons = $this->couponService->all();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
<<<<<<< HEAD
        $this->authorize('coupons.create');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        return view('admin.coupons.create');
    }

    public function store(CouponStoreRequest $request)
    {
<<<<<<< HEAD
        $this->authorize('coupons.create');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $data = $request->validated();
        $this->couponService->create($data);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function show(Coupon $coupon)
    {
<<<<<<< HEAD
        $this->authorize('coupons.view');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        return view('admin.coupons.show', compact('coupon'));
    }

    public function edit(Coupon $coupon)
    {
<<<<<<< HEAD
        $this->authorize('coupons.update');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(CouponUpdateRequest $request, Coupon $coupon)
    {
<<<<<<< HEAD
        $this->authorize('coupons.update');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $data = $request->validated();
        $this->couponService->update($coupon->id, $data);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
<<<<<<< HEAD
        $this->authorize('coupons.delete');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $this->couponService->delete($coupon->id);
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon moved to Recycle Bin successfully.');
    }
}

