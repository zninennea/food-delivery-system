<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard - NaNi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5 {
            font-family: 'Playfair Display', serif;
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0;
            transform: translateY(10px);
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="bg-stone-50 text-gray-800 antialiased">

    <nav
        class="fixed w-full top-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('owner.dashboard') }}" class="flex-shrink-0 flex items-center gap-2 group">
                    <img src="{{ asset('images/NaNi_Logo.png') }}" alt="NaNi Logo"
                        class="h-20 w-auto group-hover:rotate-12 transition-transform duration-300">
                </a>

                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('owner.dashboard') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-home mr-1"></i> Dashboard
                    </a>
                    <a href="{{ route('owner.orders.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-shopping-cart mr-1"></i> Orders
                    </a>
                    <a href="{{ route('owner.menu.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-utensils mr-1"></i> Menu
                    </a>
                    <a href="{{ route('owner.analytics.index') }}"
                        class="text-orange-600 bg-orange-50 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-chart-line mr-1"></i> Analytics
                    </a>
                    <a href="{{ route('owner.reviews.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-star mr-1"></i> Reviews
                    </a>
                    <a href="{{ route('owner.riders.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-motorcycle mr-1"></i> Riders
                    </a>

                    <div class="ml-4 pl-4 border-l border-gray-200 flex items-center gap-3">
                        <!-- Profile Button (Active) -->
                        <a href="{{ route('owner.profile.show') }}"
                            class="text-grey-600 hover:text-orange-600 transition-colors">
                            <i class="fas fa-user-circle text-xl"></i>
                        </a>

                        <span class="text-sm font-bold text-gray-700">Admin</span>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors"
                                title="Logout">
                                <i class="fas fa-sign-out-alt text-lg"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="pt-32 pb-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-8 fade-in">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Performance Analytics</h1>
                <p class="text-stone-500">Track sales, orders, and customer satisfaction.</p>
            </div>

        </div>
        <!-- Simple CSV Downloads -->
        <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-6 mb-8 fade-in"
            style="animation-delay: 0.1s;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900 font-serif">Order Data Downloads</h3>
                <a href="{{ route('owner.analytics.data-dictionary') }}"
                    class="text-blue-600 text-sm font-bold hover:underline flex items-center gap-1">
                    <i class="fas fa-book"></i> Data Dictionary
                </a>
            </div>

            <p class="text-sm text-stone-600 mb-6">Download anonymized order data in CSV format, similar to public
                bike-sharing systems.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Quarterly Data -->
                <div class="border border-stone-200 rounded-xl p-4 hover:bg-stone-50 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-bold text-gray-900">Quarterly Data</h4>
                        <span
                            class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Q{{ ceil(date('m') / 3) }}</span>
                    </div>
                    <p class="text-xs text-stone-500 mb-3">All orders from the current quarter</p>
                    <a href="{{ route('owner.analytics.trip-data', ['type' => 'quarterly', 'year' => date('Y'), 'quarter' => ceil(date('m') / 3)]) }}"
                        class="inline-flex items-center gap-2 text-blue-600 text-sm font-bold hover:text-blue-800">
                        <i class="fas fa-file-csv"></i> Download CSV
                    </a>
                </div>

                <!-- Monthly Data -->
                <div class="border border-stone-200 rounded-xl p-4 hover:bg-stone-50 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-bold text-gray-900">Monthly Data</h4>
                        <span
                            class="text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded-full">{{ date('F') }}</span>
                    </div>
                    <p class="text-xs text-stone-500 mb-3">All orders from {{ date('F Y') }}</p>
                    <a href="{{ route('owner.analytics.trip-data', ['type' => 'monthly', 'year' => date('Y'), 'month' => date('m')]) }}"
                        class="inline-flex items-center gap-2 text-orange-600 text-sm font-bold hover:text-orange-800">
                        <i class="fas fa-file-csv"></i> Download CSV
                    </a>
                </div>

                <!-- Recent Data -->
                <div class="border border-stone-200 rounded-xl p-4 hover:bg-stone-50 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-bold text-gray-900">Recent Orders</h4>
                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Today</span>
                    </div>
                    <p class="text-xs text-stone-500 mb-3">Orders from the last 7 days</p>
                    <a href="{{ route('owner.analytics.trip-data', ['type' => 'daily']) }}"
                        class="inline-flex items-center gap-2 text-green-600 text-sm font-bold hover:text-green-800">
                        <i class="fas fa-file-csv"></i> Download CSV
                    </a>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-stone-100">
                <div class="flex items-start gap-3">
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-blue-900 text-sm mb-1">Privacy Protected</h4>
                        <p class="text-xs text-blue-700">All personally identifiable information (names, addresses,
                            phone
                            numbers) has been removed. Only order metadata is included.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 fade-in" style="animation-delay: 0.1s;">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-stone-100">
                <div class="flex items-center gap-4 mb-4">
                    <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                        <i class="fas fa-coins text-xl"></i>
                    </div>
                    <span class="text-xs font-bold text-stone-400 uppercase tracking-wider">Today's Revenue</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-900">₱{{ number_format($salesStats['today'], 2) }}</h3>
                <p class="text-xs text-green-600 mt-2 font-medium flex items-center gap-1">
                    <i class="fas fa-chart-line"></i> Updated just now
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-stone-100">
                <div class="flex items-center gap-4 mb-4">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                        <i class="fas fa-calendar-day text-xl"></i>
                    </div>
                    <span class="text-xs font-bold text-stone-400 uppercase tracking-wider">This Month</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-900">₱{{ number_format($salesStats['this_month'], 2) }}</h3>
                <p class="text-xs text-stone-400 mt-2">Sales Year-to-Date:
                    ₱{{ number_format($salesStats['this_year'], 2) }}</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-stone-100">
                <div class="flex items-center gap-4 mb-4">
                    <div class="p-3 bg-orange-50 text-orange-600 rounded-xl">
                        <i class="fas fa-shopping-bag text-xl"></i>
                    </div>
                    <span class="text-xs font-bold text-stone-400 uppercase tracking-wider">Total Orders</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-900">{{ number_format($salesStats['total_orders']) }}</h3>
                <p class="text-xs text-stone-400 mt-2">Avg Order:
                    ₱{{ number_format($salesStats['average_order_value'], 2) }}</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-stone-100">
                <div class="flex items-center gap-4 mb-4">
                    <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                        <i class="fas fa-star text-xl"></i>
                    </div>
                    <span class="text-xs font-bold text-stone-400 uppercase tracking-wider">Customer Rating</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-900">
                    {{ number_format($reviewAnalytics['stats']['avg_rating'], 1) }}
                </h3>
                <div class="flex text-yellow-400 text-xs mt-2">
                    @for($i = 1; $i <= 5; $i++)
                        <i
                            class="fas fa-star{{ $i <= round($reviewAnalytics['stats']['avg_rating']) ? '' : '-o text-stone-200' }}"></i>
                    @endfor
                    <span class="text-stone-400 ml-2">({{ $reviewAnalytics['stats']['total_reviews'] }})</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8 fade-in" style="animation-delay: 0.2s;">
            <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-stone-100 p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 font-serif">Revenue Overview</h3>
                    <select id="salesYear"
                        class="bg-stone-50 border-none text-sm font-bold text-stone-600 rounded-lg focus:ring-0 cursor-pointer">
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                    </select>
                </div>
                <div class="h-80 w-full">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 flex flex-col">
                <h3 class="text-xl font-bold text-gray-900 font-serif mb-6">Order Breakdown</h3>
                <div class="flex-1 flex items-center justify-center relative">
                    <canvas id="statusChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-3xl font-bold text-gray-900">{{ array_sum($orderStatusDistribution) }}</span>
                        <span class="text-xs text-stone-400 uppercase tracking-wider">Orders</span>
                    </div>
                </div>
                <div class="mt-6 space-y-3">
                    @foreach($orderStatusDistribution as $status => $count)
                        @if($count > 0)
                            <div class="flex justify-between items-center text-sm">
                                <span class="capitalize text-stone-600 flex items-center gap-2">
                                    <span
                                        class="w-2 h-2 rounded-full 
                                                                                                                                                                                                                                                                                @if($status == 'delivered') bg-green-500
                                                                                                                                                                                                                                                                                @elseif($status == 'cancelled') bg-red-500
                                                                                                                                                                                                                                                                                @else bg-blue-500 @endif
                                                                                                                                                                                                                                                                            "></span>
                                    {{ str_replace('_', ' ', $status) }}
                                </span>
                                <span class="font-bold text-gray-900">{{ $count }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8 fade-in" style="animation-delay: 0.25s;">

            <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8">
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-900 font-serif">Peak Ordering Hours</h3>
                    <p class="text-xs text-stone-500">Based on last 30 days activity</p>
                </div>
                <div class="h-64">
                    <canvas id="peakHoursChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8">
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-900 font-serif">Revenue by Category</h3>
                    <p class="text-xs text-stone-500">Which food categories earn the most?</p>
                </div>

                <div class="flex flex-col md:flex-row items-center gap-6">
                    <div class="h-64 w-full md:w-1/2">
                        <canvas id="categoryChart"></canvas>
                    </div>

                    <div class="w-full md:w-1/2 space-y-3">
                        @foreach($salesByCategory->take(5) as $cat)
                            <div class="flex justify-between items-center text-sm p-3 bg-stone-50 rounded-xl">
                                <span class="font-bold text-gray-700 capitalize">{{ $cat->category }}</span>
                                <span class="text-orange-600 font-bold">₱{{ number_format($cat->total_revenue) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 fade-in" style="animation-delay: 0.3s;">

            <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden">
                <div class="p-6 border-b border-stone-100 bg-stone-50/50 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-900 font-serif">Top Selling Dishes</h3>
                    <a href="{{ route('owner.analytics.export', ['type' => 'top_selling']) }}"
                        class="text-orange-600 text-sm font-bold hover:underline flex items-center gap-1">
                        <i class="fas fa-download"></i> Export List
                    </a>
                </div>
                <div class="divide-y divide-stone-100">
                    @forelse($topSellingItems as $index => $item)
                        <div class="p-5 flex items-center justify-between hover:bg-stone-50 transition-colors">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-8 h-8 rounded-full bg-stone-900 text-white flex items-center justify-center font-bold text-xs">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">{{ $item->name }}</h4>
                                    <p class="text-xs text-stone-500">{{ $item->category ?? 'Main Course' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-orange-600">{{ $item->total_quantity }}</p>
                                <p class="text-xs text-stone-400 uppercase">Sold</p>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-stone-400">
                            <p>No sales data yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8">
                <h3 class="text-xl font-bold text-gray-900 font-serif mb-6">Review Performance</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-green-50 p-5 rounded-2xl border border-green-100">
                        <p class="text-3xl font-bold text-green-600 mb-1">
                            {{ $reviewAnalytics['stats']['positive_reviews'] }}
                        </p>
                        <p class="text-sm text-green-800 font-medium">Positive Reviews</p>
                        <p class="text-xs text-green-600/70 mt-1">4 Stars & Above</p>
                    </div>

                    <div class="bg-stone-50 p-5 rounded-2xl border border-stone-100">
                        <p class="text-3xl font-bold text-gray-900 mb-1">
                            {{ $reviewAnalytics['stats']['reviews_this_month'] }}
                        </p>
                        <p class="text-sm text-gray-600 font-medium">New This Month</p>
                        <p class="text-xs text-gray-400 mt-1">Growth</p>
                    </div>
                </div>

                <div
                    class="mt-6 bg-orange-50 rounded-2xl p-6 border border-orange-100 flex items-center justify-between">
                    <div>
                        <p class="font-bold text-orange-900">View All Feedback</p>
                        <p class="text-xs text-orange-700/80">Read what customers are saying</p>
                    </div>
                    <a href="{{ route('owner.reviews.index') }}"
                        class="bg-white text-orange-600 px-4 py-2 rounded-xl text-sm font-bold shadow-sm hover:shadow-md transition-all">
                        Open Reviews
                    </a>
                </div>
            </div>

        </div>

    </div>

    <script>
        // --- Data Injection ---
        const monthlySalesData = @json($monthlySales);
        const statusData = @json($orderStatusDistribution);
        const peakHoursData = @json($peakHours);
        const categoryData = @json($salesByCategory);

        // --- Sales Chart ---
        const salesCtx = document.getElementById('salesChart');
        if (salesCtx) {
            new Chart(salesCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Revenue',
                        data: Object.values(monthlySalesData),
                        backgroundColor: '#ea580c', // Orange-600
                        borderRadius: 6,
                        barThickness: 20
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { display: true, borderDash: [2, 2], drawBorder: false },
                            ticks: { callback: (val) => '₱' + val }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        // --- Status Chart ---
        const statusCtx = document.getElementById('statusChart');
        if (statusCtx) {
            const labels = Object.keys(statusData).map(s => s.charAt(0).toUpperCase() + s.slice(1).replace('_', ' '));
            const values = Object.values(statusData);

            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: ['#eab308', '#3b82f6', '#a855f7', '#6366f1', '#22c55e', '#ef4444'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: { legend: { display: false } }
                }
            });
        }
        // --- Peak Hours Chart ---
        const peakCtx = document.getElementById('peakHoursChart');
        if (peakCtx) {
            // Fill in missing hours (0-23) with 0
            const fullHours = Array.from({ length: 24 }, (_, i) => i);
            const hoursData = fullHours.map(h => peakHoursData[h] || 0);

            // Format labels (12 PM, 1 PM...)
            const hourLabels = fullHours.map(h => {
                const ampm = h >= 12 ? 'PM' : 'AM';
                const hour = h % 12 || 12;
                return `${hour} ${ampm}`;
            });

            new Chart(peakCtx, {
                type: 'bar',
                data: {
                    labels: hourLabels,
                    datasets: [{
                        label: 'Orders',
                        data: hoursData,
                        backgroundColor: (context) => {
                            const value = context.raw;
                            // Make high traffic bars darker orange
                            const alpha = Math.max(0.2, Math.min(1, value / 5));
                            return `rgba(234, 88, 12, ${alpha})`;
                        },
                        borderRadius: 4,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { display: false } },
                        x: { grid: { display: false }, ticks: { maxTicksLimit: 8 } } // Show fewer labels
                    }
                }
            });
        }

        // --- Category Revenue Chart ---
        const catCtx = document.getElementById('categoryChart');
        if (catCtx) {
            new Chart(catCtx, {
                type: 'pie',
                data: {
                    labels: categoryData.map(d => d.category.charAt(0).toUpperCase() + d.category.slice(1)),
                    datasets: [{
                        data: categoryData.map(d => d.total_revenue),
                        backgroundColor: [
                            '#ea580c', // Orange
                            '#f59e0b', // Amber
                            '#ef4444', // Red
                            '#84cc16', // Lime
                            '#0ea5e9', // Sky
                            '#64748b'  // Slate
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: { boxWidth: 12, font: { size: 10 } }
                        }
                    }
                }
            });
        }
    </script>
</body>

</html>