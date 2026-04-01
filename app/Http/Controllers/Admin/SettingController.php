<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'admin_logo_path' => Setting::get('admin_logo_path'),
            'admin_favicon_path' => Setting::get('admin_favicon_path'),
            'admin_theme_mode' => Setting::get('admin_theme_mode', 'light'),
            'site_logo_path' => Setting::get('site_logo_path'),
            'site_favicon_path' => Setting::get('site_favicon_path'),
            'site_name' => Setting::get('site_name', 'Furnixar'),
            'admin_email' => Setting::get('admin_email', 'admin@furnixar.com'),
            'whatsapp_number' => Setting::get('whatsapp_number', '1234567890'),
            'currency' => Setting::get('currency', 'USD'),
            'currency_symbol' => Setting::get('currency_symbol', '$'),
            // Razorpay Payment Settings
            'razorpay_key' => Setting::get('razorpay_key'),
            'razorpay_secret' => Setting::get('razorpay_secret'),
            'razorpay_webhook' => Setting::get('razorpay_webhook'),
            'razorpay_enabled' => Setting::get('razorpay_enabled', false),
            'razorpay_show_checkout' => Setting::get('razorpay_show_checkout', true),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        if ($request->expectsJson()) {
            $data = $request->validate([
                'admin_theme_mode' => 'required|in:light,dark',
            ]);

            Setting::updateOrCreate(['key' => 'admin_theme_mode'], ['value' => $data['admin_theme_mode']]);

            return response()->json(['success' => true]);
        }

        $request->validate([
            'site_name' => 'required|string|max:255',
            'admin_email' => 'required|email',
            'whatsapp_number' => 'nullable|string|max:20',
            'admin_theme_mode' => 'required|in:light,dark',
            'currency' => 'required|string|max:10',
            'currency_symbol' => 'required|string|max:5',
            // Razorpay validation
            'razorpay_key' => 'nullable|string|max:255',
            'razorpay_secret' => 'nullable|string|max:255',
            'razorpay_webhook' => 'nullable|url|max:500',
            'razorpay_enabled' => 'boolean',
            'razorpay_show_checkout' => 'boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'site_favicon' => 'nullable|image|mimes:ico,png,jpg|max:2048',
            'admin_favicon' => 'nullable|image|mimes:ico,png,jpg|max:2048',
        ]);

        // Update text settings
        Setting::updateOrCreate(['key' => 'site_name'], ['value' => $request->site_name]);
        Setting::updateOrCreate(['key' => 'admin_email'], ['value' => $request->admin_email]);
        Setting::updateOrCreate(['key' => 'whatsapp_number'], ['value' => $request->whatsapp_number]);
        Setting::updateOrCreate(['key' => 'admin_theme_mode'], ['value' => $request->admin_theme_mode]);
        Setting::updateOrCreate(['key' => 'currency'], ['value' => $request->currency]);
        Setting::updateOrCreate(['key' => 'currency_symbol'], ['value' => $request->currency_symbol]);

        // Update Razorpay settings
        Setting::updateOrCreate(['key' => 'razorpay_key'], ['value' => $request->razorpay_key]);
        Setting::updateOrCreate(['key' => 'razorpay_secret'], ['value' => $request->razorpay_secret]);
        Setting::updateOrCreate(['key' => 'razorpay_webhook'], ['value' => $request->razorpay_webhook]);
        Setting::updateOrCreate(['key' => 'razorpay_enabled'], ['value' => $request->razorpay_enabled ? '1' : '0']);
        Setting::updateOrCreate(['key' => 'razorpay_show_checkout'], ['value' => $request->razorpay_show_checkout ? '1' : '0']);

        // Handle Admin Logo upload
        if ($request->hasFile('logo')) {
            $oldLogo = Setting::get('admin_logo_path');
            if ($oldLogo && File::exists(storage_path('app/public/' . $oldLogo))) {
                File::delete(storage_path('app/public/' . $oldLogo));
            }
            $path = $request->file('logo')->store('admin', 'public');
            Setting::updateOrCreate(['key' => 'admin_logo_path'], ['value' => $path]);
        }

        // Handle Site Favicon upload
        if ($request->hasFile('site_favicon')) {
            $oldFavicon = Setting::get('site_favicon_path');
            if ($oldFavicon && File::exists(storage_path('app/public/' . $oldFavicon))) {
                File::delete(storage_path('app/public/' . $oldFavicon));
            }
            $path = $request->file('site_favicon')->store('logos', 'public');
            Setting::updateOrCreate(['key' => 'site_favicon_path'], ['value' => $path]);
        }

        // Handle Site Logo upload
        if ($request->hasFile('site_logo')) {
            $oldLogo = Setting::get('site_logo_path');
            if ($oldLogo && File::exists(storage_path('app/public/' . $oldLogo))) {
                File::delete(storage_path('app/public/' . $oldLogo));
            }
            $path = $request->file('site_logo')->store('logos', 'public');
            Setting::updateOrCreate(['key' => 'site_logo_path'], ['value' => $path]);
        }

        // Handle Admin Favicon upload
        if ($request->hasFile('admin_favicon')) {
            $oldFavicon = Setting::get('admin_favicon_path');
            if ($oldFavicon && File::exists(storage_path('app/public/' . $oldFavicon))) {
                File::delete(storage_path('app/public/' . $oldFavicon));
            }
            $path = $request->file('admin_favicon')->store('admin', 'public');
            Setting::updateOrCreate(['key' => 'admin_favicon_path'], ['value' => $path]);
        }

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
}
