<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard - NaNi Owner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <!-- NaNi Logo -->
                    <div class="flex items-center">
                        <img src="{{ asset('images/nani-logo.png') }}" alt="NaNi Logo" class="h-10 w-10 mr-3">
                        <div>
                            <a href="/" class="text-xl font-bold text-gray-800">NaNi</a>
                            <p class="text-xs text-gray-500 -mt-1">Owner Dashboard</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('owner.dashboard') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <a href="{{ route('owner.menu.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-utensils mr-1"></i>Menu
                    </a>
                    <a href="{{ route('owner.orders.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-shopping-cart mr-1"></i>Orders
                    </a>
                    <a href="{{ route('owner.analytics.index') }}"
                        class="text-orange-600 hover:text-orange-700 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-chart-bar mr-1"></i>Analytics
                    </a>
                    <a href="{{ route('owner.riders.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-motorcycle mr-1"></i>Riders
                    </a>
                    <a href="{{ route('owner.profile.show') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-user mr-1"></i>Profile
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-sign-out-alt mr-1"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Analytics Dashboard</h1>
            <p class="text-gray-600">Track your restaurant's performance and sales statistics</p>
        </div>

        <!-- Sales Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-money-bill-wave text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Today's Revenue</p>
                        <p class="text-2xl font-bold text-gray-900">₱{{ number_format($salesStats['today'], 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-calendar-alt text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">This Month</p>
                        <p class="text-2xl font-bold text-gray-900">₱{{ number_format($salesStats['this_month'], 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i class="fas fa-chart-line text-purple-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">This Year</p>
                        <p class="text-2xl font-bold text-gray-900">₱{{ number_format($salesStats['this_year'], 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-lg">
                        <i class="fas fa-shopping-cart text-orange-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Orders</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($salesStats['total_orders']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Monthly Sales Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Monthly Sales ({{ date('Y') }})</h3>
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Only delivered orders
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="salesChart"></canvas>
                </div>
                @if(array_sum($monthlySales) == 0)
                    <div class="text-center text-gray-500 py-4">
                        <i class="fas fa-chart-line text-2xl mb-2"></i>
                        <p>No sales data available for {{ date('Y') }}</p>
                    </div>
                @endif
            </div>

            <!-- Order Status Distribution -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Order Status Distribution</h3>
                <div class="h-64">
                    <canvas id="statusChart"></canvas>
                </div>
                @if(array_sum($orderStatusDistribution) == 0)
                    <div class="text-center text-gray-500 py-4">
                        <i class="fas fa-shopping-cart text-2xl mb-2"></i>
                        <p>No order data available</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Top Selling Items -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Top Selling Menu Items</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($topSellingItems as $index => $item)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span
                                    class="w-8 h-8 bg-blue-100 text-blue-800 rounded-full flex items-center justify-center text-sm font-medium mr-4">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $item->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $item->total_quantity }} orders</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-semibold text-gray-900">{{ $item->total_quantity }} sold</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-8">
                            <i class="fas fa-utensils text-4xl mb-2"></i>
                            <p>No sales data available yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Debug Information (Remove in production) -->
        @if(env('APP_DEBUG'))
            <div class="bg-yellow-100 border border-yellow-400 rounded-lg p-4 mb-6">
                <h4 class="font-bold text-yellow-800">Debug Information</h4>
                <pre class="text-sm text-yellow-700 mt-2">Monthly Sales Data: @json($monthlySales)</pre>
                <pre class="text-sm text-yellow-700">Sales Stats: @json($salesStats)</pre>
            </div>
        @endif
    </div>

    <script>
        // Monthly Sales Chart
        const salesCtx = document.getElementById('salesChart');
        if (salesCtx) {
            const monthlySalesData = @json($monthlySales);

            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const salesData = months.map((month, index) => monthlySalesData[index + 1] || 0);

            // Only create chart if there's data
            if (salesData.some(value => value > 0)) {
                new Chart(salesCtx, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [{
                            label: 'Sales (₱)',
                            data: salesData,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function (value) {
                                        return '₱' + value.toLocaleString();
                                    }
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return 'Sales: ₱' + context.parsed.y.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }

        // Order Status Distribution Chart
        const statusCtx = document.getElementById('statusChart');
        if (statusCtx) {
            const statusData = @json($orderStatusDistribution);

            const statusLabels = Object.keys(statusData).map(status =>
                status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())
            );
            const statusCounts = Object.values(statusData);

            // Only create chart if there's data
            if (statusCounts.some(value => value > 0)) {
                const statusColors = {
                    'pending': 'rgb(234, 179, 8)',
                    'preparing': 'rgb(59, 130, 246)',
                    'ready': 'rgb(34, 197, 94)',
                    'on_the_way': 'rgb(168, 85, 247)',
                    'delivered': 'rgb(107, 114, 128)',
                    'cancelled': 'rgb(239, 68, 68)'
                };

                new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: statusLabels,
                        datasets: [{
                            data: statusCounts,
                            backgroundColor: statusLabels.map(label => {
                                const key = label.toLowerCase().replace(' ', '_');
                                return statusColors[key] || 'rgb(156, 163, 175)';
                            })
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        const label = context.label || '';
                                        const value = context.parsed || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = Math.round((value / total) * 100);
                                        return `${label}: ${value} orders (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }
    </script>
</body>

</html>