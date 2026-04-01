@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Settings</h1>
</div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- General Settings -->
    <div class="bg-white shadow rounded-lg p-8">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800 border-b pb-2">General Settings</h2>
        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
            @csrf
            @method('POST')
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                <input type="text" name="site_name" value="{{ $settings['site_name'] ?? '' }}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Admin Email</label>
                <input type="email" name="admin_email" value="{{ $settings['admin_email'] ?? '' }}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp Number</label>
                <input type="tel" name="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? '' }}" placeholder="+1234567890" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Format: +1234567890 (for WhatsApp CTA button on frontend)</p>
            </div>
            
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Admin Theme Mode</label>
                <select name="admin_theme_mode" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="light" {{ ($settings['admin_theme_mode'] ?? 'light') == 'light' ? 'selected' : '' }}>Light</option>
                    <option value="dark" {{ ($settings['admin_theme_mode'] ?? '') == 'dark' ? 'selected' : '' }}>Dark</option>
                </select>
            </div>

            <!-- Logo Upload Section -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Admin Logo</label>
                @if($settings['admin_logo_path'])
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $settings['admin_logo_path']) }}" alt="Current Logo" class="max-w-32 h-16 object-contain border rounded-lg shadow">
                        <p class="text-sm text-gray-500 mt-1">Current logo</p>
                    </div>
                @endif
                <input type="file" name="logo" accept="image/*" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Recommended size: 200x60px, Max 2MB</p>
            </div>

            <!-- Site Logo Upload Section -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Site Logo (Frontend)</label>
                @if($settings['site_logo_path'])
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $settings['site_logo_path']) }}" alt="Current Site Logo" class="max-w-32 h-16 object-contain border rounded-lg shadow">
                        <p class="text-sm text-gray-500 mt-1">Current site logo</p>
                    </div>
                @endif
                <input type="file" name="site_logo" accept="image/*" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Recommended size: 200x60px or SVG, Max 2MB</p>
            </div>

            <!-- Site Favicon Upload Section -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Site Favicon (Frontend)</label>
                @if($settings['site_favicon_path'])
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $settings['site_favicon_path']) }}" alt="Current Site Favicon" class="w-16 h-16 object-contain border rounded-lg shadow mx-auto">
                        <p class="text-sm text-gray-500 mt-1">Current site favicon</p>
                    </div>
                @endif
                <input type="file" name="site_favicon" accept="image/*" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Recommended: 32x32px or 16x16px ICO/PNG, Max 2MB</p>
            </div>

            <!-- Favicon Upload Section -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Admin Favicon</label>
                @if($settings['admin_favicon_path'])
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $settings['admin_favicon_path']) }}" alt="Current Admin Favicon" class="w-16 h-16 object-contain border rounded-lg shadow mx-auto">
                        <p class="text-sm text-gray-500 mt-1">Current admin favicon</p>
                    </div>
                @endif
                <input type="file" name="admin_favicon" accept="image/*" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Recommended: 32x32px or 16x16px ICO/PNG, Max 2MB</p>
            </div>

            <!-- Currency Settings -->
            <div class="mb-8 bg-gradient-to-r  p-6 rounded-xl border border-emerald-200">
                <h3 class="text-lg font-semibold mb-4 text-white flex items-center">
                    <i class="fas fa-dollar-sign mr-2"></i>Currency Settings
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Currency Code</label>
                        <select name="currency" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            <option value="USD" {{ ($settings['currency'] ?? 'USD') == 'USD' ? 'selected' : '' }}>USD - US Dollar ($)</option>
                            <option value="EUR" {{ ($settings['currency'] ?? 'USD') == 'EUR' ? 'selected' : '' }}>EUR - Euro (€)</option>
                            <option value="GBP" {{ ($settings['currency'] ?? 'USD') == 'GBP' ? 'selected' : '' }}>GBP - British Pound (£)</option>
                            <option value="INR" {{ ($settings['currency'] ?? 'USD') == 'INR' ? 'selected' : '' }}>INR - Indian Rupee (₹)</option>
                            <option value="CAD" {{ ($settings['currency'] ?? 'USD') == 'CAD' ? 'selected' : '' }}>CAD - Canadian Dollar (C$)</option>
                            <option value="AUD" {{ ($settings['currency'] ?? 'USD') == 'AUD' ? 'selected' : '' }}>AUD - Australian Dollar (A$)</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">ISO 4217 code for currency</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Currency Symbol</label>
                        <input type="text" name="currency_symbol" value="{{ $settings['currency_symbol'] ?? '$' }}" maxlength="5" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Symbol to display before prices (e.g. $, €, ₹)</p>
                    </div>
                </div>
                <div class="mt-4 p-4 rounded-lg border border-white">
                    <p class="text-sm text-white"><strong>Preview:</strong> <span class="font-mono bg-white px-2 py-1 rounded">{{ $settings['currency_symbol'] ?? '$' }}99.99</span></p>
                </div>
            </div>

           

            <button type="submit" class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 font-medium transition duration-200">
                <i class="fas fa-save mr-2"></i>Save Settings
            </button>
        </form>
    </div>

    <!-- Preview/Info -->
    <div class="bg-white shadow rounded-lg p-8">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800 border-b pb-2">Preview</h2>
        <div class="text-center">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-8 rounded-xl mb-6">
                <h3 class="text-xl font-bold mb-2">{{ $settings['site_name'] ?? 'Furnixar' }}</h3>
                <p class="opacity-90">{{ $settings['admin_email'] ?? 'admin@furnixar.com' }}</p>
            </div>
            <p class="text-gray-600 text-sm">Changes will apply immediately to admin panel.</p>
            <div class="mt-6 grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-medium text-gray-500">Theme</label>
                    <span class="block px-3 py-1 bg-gray-100 rounded-full text-sm mt-1">{{ ucfirst($settings['admin_theme_mode'] ?? 'Light') }}</span>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500">Status</label>
                    <span class="block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm mt-1">Live</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
