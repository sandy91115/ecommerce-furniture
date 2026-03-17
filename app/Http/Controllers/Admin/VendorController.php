<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::with('user')->paginate(15);
        return view('admin.vendors.index', compact('vendors'));
    }

    public function create()
    {
        return view('admin.vendors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'store_name' => 'required|string|max:255',
            'store_slug' => 'required|string|unique:vendors|alpha_dash',
            'store_description' => 'required|string',
            'store_address' => 'string',
            'store_phone' => 'string',
            'store_logo' => 'nullable|image|max:2048',
            'store_banner' => 'nullable|image|max:2048',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole('vendor');

        $logoPath = null;
        if ($request->file('store_logo')) {
            $logoPath = $request->file('store_logo')->store('vendors/logos', 'public');
        }
        $bannerPath = null;
        if ($request->file('store_banner')) {
            $bannerPath = $request->file('store_banner')->store('vendors/banners', 'public');
        }

        Vendor::create([
            'user_id' => $user->id,
            'store_name' => $validated['store_name'],
            'store_slug' => $validated['store_slug'],
            'store_description' => $validated['store_description'],
            'store_address' => $validated['store_address'],
            'store_phone' => $validated['store_phone'],
            'store_logo' => $logoPath,
            'store_banner' => $bannerPath,
            'status' => 'active',
        ]);

        return redirect()->route('admin.vendors.index')->with('success', 'Vendor created successfully.');
    }

    public function show(Vendor $vendor)
    {
        $vendor->load('user');
        return view('admin.vendors.show', compact('vendor'));
    }

    public function edit(Vendor $vendor)
    {
        return view('admin.vendors.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'store_slug' => 'required|string|unique:vendors,store_slug,' . $vendor->id,
            'store_description' => 'required|string',
            'store_address' => 'string',
            'store_phone' => 'string',
            'status' => 'required|in:active,pending,inactive',
        ]);

        if ($request->file('store_logo')) {
            $validated['store_logo'] = $request->file('store_logo')->store('vendors/logos', 'public');
        }
        if ($request->file('store_banner')) {
            $validated['store_banner'] = $request->file('store_banner')->store('vendors/banners', 'public');
        }

        $vendor->update($validated);

        return redirect()->route('admin.vendors.index')->with('success', 'Vendor updated successfully.');
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->user->delete(); // Cascade user too?
        $vendor->delete();
        return redirect()->route('admin.vendors.index')->with('success', 'Vendor deleted successfully.');
    }
}

