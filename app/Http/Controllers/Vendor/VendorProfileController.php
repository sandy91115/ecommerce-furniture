<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class VendorProfileController extends Controller
{
    public function edit()
    {
        $vendor = Auth::user()->vendor;
        return view('vendor.profile.edit', compact('vendor'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        $request->validate([
            'store_name' => 'required|string|max:255',
            'store_slug' => ['required', 'string', Rule::unique('vendors', 'store_slug')->ignore($vendor->id)],
            'store_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'store_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'store_description' => 'nullable|string',
            'store_address' => 'nullable|string',
            'store_phone' => 'nullable|string|max:20',
            'status' => 'in:pending,active,inactive',
        ]);

        $data = $request->only(['store_name', 'store_description', 'store_address', 'store_phone']);

        // Slug
        $data['store_slug'] = Str::slug($request->store_slug);

        // Logo
        if ($request->hasFile('store_logo')) {
            $logoPath = $request->file('store_logo')->store('vendors/' . $vendor->id . '/logo', 'public');
            $data['store_logo'] = $logoPath;
        }

        // Banner
        if ($request->hasFile('store_banner')) {
            $bannerPath = $request->file('store_banner')->store('vendors/' . $vendor->id . '/banner', 'public');
            $data['store_banner'] = $bannerPath;
        }

        $vendor->update($data);

        return redirect()->route('vendor.profile.edit')->with('success', 'Store profile updated successfully!');
    }
}

