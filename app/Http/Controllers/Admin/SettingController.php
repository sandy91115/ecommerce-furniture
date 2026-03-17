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
            'site_name' => Setting::get('site_name', 'Furnixar'),
            'admin_email' => Setting::get('admin_email', 'admin@furnixar.com'),
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
            'admin_theme_mode' => 'required|in:light,dark',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:2048',
        ]);

        // Update text settings
        Setting::updateOrCreate(['key' => 'site_name'], ['value' => $request->site_name]);
        Setting::updateOrCreate(['key' => 'admin_email'], ['value' => $request->admin_email]);
        Setting::updateOrCreate(['key' => 'admin_theme_mode'], ['value' => $request->admin_theme_mode]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $oldLogo = Setting::get('admin_logo_path');
            if ($oldLogo && File::exists(storage_path('app/public/' . $oldLogo))) {
                File::delete(storage_path('app/public/' . $oldLogo));
            }
            $path = $request->file('logo')->store('admin', 'public');
            Setting::updateOrCreate(['key' => 'admin_logo_path'], ['value' => $path]);
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $oldFavicon = Setting::get('admin_favicon_path');
            if ($oldFavicon && File::exists(storage_path('app/public/' . $oldFavicon))) {
                File::delete(storage_path('app/public/' . $oldFavicon));
            }
            $path = $request->file('favicon')->store('admin', 'public');
            Setting::updateOrCreate(['key' => 'admin_favicon_path'], ['value' => $path]);
        }

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
}
