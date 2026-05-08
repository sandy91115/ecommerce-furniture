<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Vendor Dashboard - Furnixar')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex">
    <!-- Sidebar -->
    <div class="w-64 bg-gradient-to-b from-blue-600 to-blue-800 text-white flex-shrink-0 shadow-lg">
        <div class="p-6 border-b border-blue-500">
            <h1 class="text-2xl font-bold">Vendor Panel</h1>
            <p class="text-blue-100">Manage your store</p>
        </div>
        <nav class="mt-8 px-4">
            <a href="{{ route('vendor.dashboard') }}" class="flex items-center py-3 px-4 rounded-lg hover:bg-blue-500 {{ request()->routeIs('vendor.dashboard') ? 'bg-blue-500' : '' }}">
                <i class="w-6 fa fa-tachometer-alt mr-4"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('vendor.products.index') }}" class="flex items-center py-3 px-4 rounded-lg hover:bg-blue-500 {{ request()->routeIs('vendor.products*') ? 'bg-blue-500' : '' }}">
                <i class="w-6 fa fa-box mr-4"></i>
                <span>My Products</span>
            </a>
            <a href="{{ route('vendor.orders.index') }}" class="flex items-center py-3 px-4 rounded-lg hover:bg-blue-500 {{ request()->routeIs('vendor.orders*') ? 'bg-blue-500' : '' }}">
                <i class="w-6 fa fa-shopping-cart mr-4"></i>
                <span>Orders</span>
            </a>
            <a href="{{ route('vendor.profile.edit') }}" class="flex items-center py-3 px-4 rounded-lg hover:bg-blue-500">
                <i class="w-6 fa fa-store mr-4"></i>
                <span>Store Settings</span>
            </a>
            <a href="{{ route('logout') }}" class="flex items-center py-3 px-4 rounded-lg hover:bg-blue-500" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="w-6 fa fa-sign-out-alt mr-4"></i>
                <span>Logout</span>
            </a>
        </nav>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Topbar -->
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-6 py-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-700">Welcome back, {{ Auth::user()->name }}</span>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 p-6 overflow-y-auto">
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>

