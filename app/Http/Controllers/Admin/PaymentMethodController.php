<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $this->authorize('payment_methods.view');

        $settings = [
            'cod_enabled' => Setting::bool(Setting::COD_ENABLED, true),
            'cod_show_checkout' => Setting::bool(Setting::COD_SHOW_CHECKOUT, true),
            'razorpay_key' => Setting::get('razorpay_key'),
            'razorpay_secret' => Setting::get('razorpay_secret'),
            'razorpay_webhook' => Setting::get('razorpay_webhook'),
            'razorpay_enabled' => Setting::bool(Setting::RAZORPAY_ENABLED),
            'razorpay_show_checkout' => Setting::bool(Setting::RAZORPAY_SHOW_CHECKOUT, true),
            'stripe_key' => Setting::get(Setting::STRIPE_KEY),
            'stripe_secret' => Setting::get(Setting::STRIPE_SECRET),
            'stripe_webhook' => Setting::get(Setting::STRIPE_WEBHOOK),
            'stripe_enabled' => Setting::bool(Setting::STRIPE_ENABLED),
            'stripe_show_checkout' => Setting::bool(Setting::STRIPE_SHOW_CHECKOUT, true),
            'phonepe_merchant_id' => Setting::get(Setting::PHONEPE_MERCHANT_ID),
            'phonepe_salt_key' => Setting::get(Setting::PHONEPE_SALT_KEY),
            'phonepe_salt_index' => Setting::get(Setting::PHONEPE_SALT_INDEX),
            'phonepe_webhook' => Setting::get(Setting::PHONEPE_WEBHOOK),
            'phonepe_enabled' => Setting::bool(Setting::PHONEPE_ENABLED),
            'phonepe_show_checkout' => Setting::bool(Setting::PHONEPE_SHOW_CHECKOUT, true),
        ];

        return view('admin.payment-methods.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $this->authorize('payment_methods.update');

        $validated = $request->validate([
            'razorpay_key' => 'nullable|string|max:255',
            'razorpay_secret' => 'nullable|string|max:255',
            'razorpay_webhook' => 'nullable|url|max:500',
            'razorpay_enabled' => 'boolean',
            'razorpay_show_checkout' => 'boolean',
            'stripe_key' => 'nullable|string|max:255',
            'stripe_secret' => 'nullable|string|max:255',
            'stripe_webhook' => 'nullable|url|max:500',
            'stripe_enabled' => 'boolean',
            'stripe_show_checkout' => 'boolean',
            'phonepe_merchant_id' => 'nullable|string|max:255',
            'phonepe_salt_key' => 'nullable|string|max:255',
            'phonepe_salt_index' => 'nullable|string|max:50',
            'phonepe_webhook' => 'nullable|url|max:500',
            'phonepe_enabled' => 'boolean',
            'phonepe_show_checkout' => 'boolean',
            'cod_enabled' => 'boolean',
            'cod_show_checkout' => 'boolean',
        ]);

        $textSettings = [
            Setting::RAZORPAY_KEY,
            Setting::RAZORPAY_SECRET,
            Setting::RAZORPAY_WEBHOOK,
            Setting::STRIPE_KEY,
            Setting::STRIPE_SECRET,
            Setting::STRIPE_WEBHOOK,
            Setting::PHONEPE_MERCHANT_ID,
            Setting::PHONEPE_SALT_KEY,
            Setting::PHONEPE_SALT_INDEX,
            Setting::PHONEPE_WEBHOOK,
        ];

        foreach ($textSettings as $key) {
            Setting::updateOrCreate(['key' => $key], ['value' => $validated[$key] ?? '']);
        }

        $booleanSettings = [
            Setting::COD_ENABLED,
            Setting::COD_SHOW_CHECKOUT,
            Setting::RAZORPAY_ENABLED,
            Setting::RAZORPAY_SHOW_CHECKOUT,
            Setting::STRIPE_ENABLED,
            Setting::STRIPE_SHOW_CHECKOUT,
            Setting::PHONEPE_ENABLED,
            Setting::PHONEPE_SHOW_CHECKOUT,
        ];

        foreach ($booleanSettings as $key) {
            Setting::updateOrCreate(['key' => $key], ['value' => $request->boolean($key) ? '1' : '0']);
        }

        return redirect()->back()->with('success', 'Payment method settings updated successfully!');
    }
}

