<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;

class VendorOrderController extends Controller
{
    public function index()
    {
        return view('vendor.orders.index');
    }

    public function create()
    {
        return view('vendor.orders.create');
    }

    public function store()
    {
        return redirect()->route('vendor.orders.index');
    }

    public function show($id)
    {
        return view('vendor.orders.show');
    }

    public function edit($id)
    {
        return view('vendor.orders.edit');
    }

    public function update($id)
    {
        return redirect()->route('vendor.orders.index');
    }

    public function destroy($id)
    {
        return redirect()->route('vendor.orders.index');
    }
}

