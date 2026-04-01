<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $settings = [
            'razorpay_key' => Setting::get('razorpay_key'),
            'razorpay_secret' => Setting::get('razorpay_secret'),
            'razorpay_webhook' => Setting::get('razorpay_webhook'),
            'razorpay_enabled' => Setting::get('razorpay_enabled', false),
            'razorpay_show_checkout' => Setting::get('razorpay_show_checkout', true),
        ];

        return view('admin.payment-methods.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'razorpay_key' => 'nullable|string|max:255',
            'razorpay_secret' => 'nullable|string|max:255',
            'razorpay_webhook' => 'nullable|url|max:500',
            'razorpay_enabled' => 'boolean',
            'razorpay_show_checkout' => 'boolean',
        ]);

        Setting::updateOrCreate(['key' => 'razorpay_key'], ['value' => $validated['razorpay_key'] ?? '']);
        Setting::updateOrCreate(['key' => 'razorpay_secret'], ['value' => $validated['razorpay_secret'] ?? '']);
        Setting::updateOrCreate(['key' => 'razorpay_webhook'], ['value' => $validated['razorpay_webhook'] ?? '']);
        Setting::updateOrCreate(['key' => 'razorpay_enabled'], ['value' => $validated['razorpay_enabled'] ? '1' : '0']);
        Setting::updateOrCreate(['key' => 'razorpay_show_checkout'], ['value' => $validated['razorpay_show_checkout'] ? '1' : '0']);

        return redirect()->back()->with('success', 'Payment method settings updated successfully!');
    }
}

