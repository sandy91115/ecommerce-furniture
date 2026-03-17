@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Loading Skeleton -->
<div id="loading-skeleton" class="space-y-6">
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="stat-card p-6 rounded-xl shadow-lg h-24 skeleton"></div>
    <div class="stat-card p-6 rounded-xl shadow-lg h-24 skeleton"></div>
    <div class="stat-card p-6 rounded-xl shadow-lg h-24 skeleton"></div>
    <div class="stat-card p-6 rounded-xl shadow-lg h-24 skeleton"></div>
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="h-80 skeleton rounded-xl"></div>
    <div class="space-y-4">
      <div class="h-8 w-48 skeleton rounded"></div>
      <div class="space-y-3">
        <div class="h-12 skeleton rounded"></div>
        <div class="h-12 skeleton rounded"></div>
        <div class="h-12 skeleton rounded"></div>
      </div>
    </div>
  </div>
</div>

<!-- Main Content -->
<div id="main-content" class="space-y-8" style="display: none;">
  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="stat-card p-8 rounded-xl shadow-xl">
      <div class="flex items-center">
        <div class="stat-icon p-4 rounded-2xl bg-blue-100 dark:bg-blue-900">
          <i class="fas fa-shopping-cart text-blue-600 dark:text-blue-400 text-3xl"></i>
        </div>
        <div class="ml-6 flex-1">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Total Orders</h3>
          <p class="stat-number">{{ number_format($stats['total_orders']) }}</p>
          <span class="growth-badge growth-positive inline-flex items-center mt-1">
            <i class="fas fa-arrow-up mr-1"></i> +12.5%
          </span>
        </div>
      </div>
    </div>
    
    <div class="stat-card p-8 rounded-xl shadow-xl">
      <div class="flex items-center">
        <div class="stat-icon p-4 rounded-2xl bg-green-100 dark:bg-green-900">
          <i class="fas fa-dollar-sign text-green-600 dark:text-green-400 text-3xl"></i>
        </div>
        <div class="ml-6 flex-1">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Total Revenue</h3>
          <p class="stat-number">${{ number_format($stats['total_revenue'], 2) }}</p>
          <span class="growth-badge growth-positive inline-flex items-center mt-1">
            <i class="fas fa-arrow-up mr-1"></i> +28.3%
          </span>
        </div>
      </div>
    </div>
    
    <div class="stat-card p-8 rounded-xl shadow-xl">
      <div class="flex items-center">
        <div class="stat-icon p-4 rounded-2xl bg-purple-100 dark:bg-purple-900">
          <i class="fas fa-users text-purple-600 dark:text-purple-400 text-3xl"></i>
        </div>
        <div class="ml-6 flex-1">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Total Customers</h3>
          <p class="stat-number">{{ number_format($stats['total_customers']) }}</p>
          <span class="growth-badge growth-positive inline-flex items-center mt-1">
            <i class="fas fa-arrow-up mr-1"></i> +8.2%
          </span>
        </div>
      </div>
    </div>
    
    <div class="stat-card p-8 rounded-xl shadow-xl">
      <div class="flex items-center">
        <div class="stat-icon p-4 rounded-2xl bg-indigo-100 dark:bg-indigo-900">
          <i class="fas fa-boxes text-indigo-600 dark:text-indigo-400 text-3xl"></i>
        </div>
        <div class="ml-6 flex-1">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Active Products</h3>
          <p class="stat-number">{{ number_format($stats['total_products']) }}</p>
          <span class="growth-badge growth-positive inline-flex items-center mt-1">
            <i class="fas fa-arrow-up mr-1"></i> +15.7%
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts & Lists -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Sales Chart -->
    <div class="bg-white/70 dark:bg-black/30 backdrop-blur-xl p-8 rounded-2xl shadow-2xl border border-white/50 dark:border-black/50">
      <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Sales Overview (30 Days)</h3>
      <div class="chart-container">
        <canvas id="salesChart"></canvas>
      </div>
    </div>

    <!-- Recent Orders -->
    <div class="orders-table bg-white/70 dark:bg-black/30 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/50 dark:border-black/50 overflow-hidden">
      <div class="p-6 border-b dark:border-gray-700">
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Recent Orders</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b">
              <th class="px-6 py-4 text-left text-sm font-bold text-gray-900 dark:text-white">Order ID</th>
              <th class="px-6 py-4 text-left text-sm font-bold text-gray-900 dark:text-white">Customer</th>
              <th class="px-6 py-4 text-left text-sm font-bold text-gray-900 dark:text-white">Amount</th>
              <th class="px-6 py-4 text-left text-sm font-bold text-gray-900 dark:text-white">Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($stats['recent_orders'] as $order)
            <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-800 transition">
              <td class="px-6 py-4 font-medium">#{{ $order->id }}</td>
              <td class="px-6 py-4">{{ $order->user->name ?? 'Guest' }}</td>
              <td class="px-6 py-4 font-semibold text-green-600">${{ number_format($order->total_amount, 2) }}</td>
              <td class="px-6 py-4">
                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $order->status->badge() }}">
                  {{ $order->status->label() }}
                </span>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">No recent orders</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Top Products -->
  <div>
    <div class="bg-white/70 dark:bg-black/30 backdrop-blur-xl p-8 rounded-2xl shadow-2xl border border-white/50 dark:border-black/50">
      <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Top Selling Products</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
        @forelse($stats['best_selling_products'] as $product)
        <div class="top-product p-6 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all group">
          <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-lg">
              {{ substr($product->name, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
              <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $product->name }}</p>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ $product->sales_count }} sales</p>
            </div>
            <div class="text-right">
              <p class="font-bold text-lg text-green-600">${{ number_format($product->price, 2) }}</p>
            </div>
          </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 text-gray-500 dark:text-gray-400">
          No products sold yet
        </div>
        @endforelse
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  // Simulate loading delay
  setTimeout(() => {
    document.getElementById('loading-skeleton').style.display = 'none';
    document.getElementById('main-content').style.display = 'block';
  }, 800);

  // Sales Chart
  document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChartData = @json($salesChartData);
    
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: Object.keys(salesChartData),
        datasets: [{
          label: 'Revenue',
          data: Object.values(salesChartData),
          borderColor: 'rgb(59, 130, 246)',
          backgroundColor: 'rgba(59, 130, 246, 0.1)',
          tension: 0.4,
          fill: true,
          pointBackgroundColor: 'rgb(59, 130, 246)',
          pointBorderColor: '#fff',
          pointHoverBackgroundColor: '#fff',
          pointHoverBorderColor: 'rgb(59, 130, 246)'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: 'rgba(0,0,0,0.05)'
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        },
        animation: {
          duration: 2000,
          easing: 'easeInOutQuart'
        }
      }
    });
  });
</script>
@endpush
@endsection
