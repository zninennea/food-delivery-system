<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Order History - NaNi Rider</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

        .history-card {
            background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%);
            border: 1px solid rgba(229, 231, 235, 0.5);
            transition: all 0.3s ease;
        }

        .history-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
        }

        .table-row {
            transition: all 0.2s ease;
        }

        .table-row:hover {
            background-color: #f9fafb;
        }

        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="bg-stone-50 text-gray-800 antialiased">
    <!-- Navigation -->
    <nav
        class="fixed w-full top-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-2 group">
                    <img src="https://i.imgur.com/vPOu1H2.png" alt="NaNi Icon"
                        class="h-20 w-auto group-hover:rotate-12 transition-transform duration-300">
                    <div class="ml-2">
                        <a href="/" class="text-xl font-bold text-gray-800 font-serif">NaNi</a>
                        <p class="text-xs text-gray-500 -mt-1">Order History - {{ Auth::user()->name }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('rider.dashboard') }}"
                        class="text-gray-600 hover:text-orange-600 hover:bg-gray-50 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="text-gray-400 hover:text-red-600 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors"
                            title="Logout">
                            <i class="fas fa-sign-out-alt text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto mt-28 px-4 fade-in">
            <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-2xl shadow-sm flex items-center gap-4"
                role="alert">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div>
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    <div class="pt-32 pb-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Stats Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 fade-in">
            <div class="history-card rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Deliveries</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $orders->total() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-truck text-blue-500 text-lg"></i>
                    </div>
                </div>
            </div>

            <div class="history-card rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">This Month</p>
                        <p class="text-2xl font-bold text-gray-900">
                            @php
                                $currentMonth = now()->month;
                                $thisMonthCount = $orders->where('updated_at', '>=', now()->startOfMonth())->count();
                            @endphp
                            {{ $thisMonthCount }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-green-500 text-lg"></i>
                    </div>
                </div>
            </div>

            <div class="history-card rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Earnings</p>
                        <p class="text-2xl font-bold text-gray-900">
                            @php
                                $totalEarnings = $orders->sum('delivery_fee');
                            @endphp
                            ₱{{ number_format($totalEarnings, 2) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-purple-500 text-lg"></i>
                    </div>
                </div>
            </div>

            <div class="history-card rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Avg. Delivery</p>
                        <p class="text-2xl font-bold text-gray-900">
                            @php
                                $avgFee = $orders->count() > 0 ? $totalEarnings / $orders->count() : 0;
                            @endphp
                            ₱{{ number_format($avgFee, 2) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-orange-500 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden fade-in"
            style="animation-delay: 0.1s;">
            <div class="p-7 border-b border-stone-100 bg-stone-50/50">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 font-serif">Delivery History</h2>
                        <p class="text-gray-600 mt-1">Your completed deliveries and earnings</p>
                    </div>

                    @if($orders->count() > 0)
                        <div class="flex items-center gap-3">
                            <div class="text-sm text-gray-500">
                                Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }}
                                deliveries
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if($orders->count() > 0)
                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto custom-scrollbar">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-7 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-hashtag text-gray-400"></i>
                                        Order #
                                    </div>
                                </th>
                                <th class="px-7 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-user text-gray-400"></i>
                                        Customer
                                    </div>
                                </th>
                                <th class="px-7 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-store text-gray-400"></i>
                                        Restaurant
                                    </div>
                                </th>
                                <th class="px-7 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-money-bill text-gray-400"></i>
                                        Order Total
                                    </div>
                                </th>
                                <th class="px-7 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-money-check-alt text-gray-400"></i>
                                        Your Earnings
                                    </div>
                                </th>
                                <th class="px-7 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                                        Delivery Address
                                    </div>
                                </th>
                                <th class="px-7 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-clock text-gray-400"></i>
                                        Completed At
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($orders as $order)
                                <tr class="table-row hover:bg-gray-50/50 transition-colors">
                                    <td class="px-7 py-5 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                                                <span class="font-bold text-blue-600">#</span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ $order->order_number }}</div>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">
                                                        {{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-7 py-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user text-green-600 text-xs"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $order->customer->name }}
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $order->customer_phone }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-7 py-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-store text-red-600 text-xs"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $order->restaurant->name }}
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $order->restaurant->phone }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-7 py-5 whitespace-nowrap">
                                        <div class="text-lg font-bold text-gray-900">
                                            ₱{{ number_format($order->grand_total, 2) }}</div>
                                    </td>
                                    <td class="px-7 py-5 whitespace-nowrap">
                                        <div class="text-lg font-bold text-green-600">
                                            ₱{{ number_format($order->delivery_fee, 2) }}</div>
                                        <div class="text-xs text-gray-500 mt-1">Delivery Fee</div>
                                    </td>
                                    <td class="px-7 py-5">
                                        <div class="text-sm text-gray-900 max-w-xs">
                                            <div class="flex items-start gap-2">
                                                <i class="fas fa-map-marker-alt text-gray-400 mt-0.5"></i>
                                                <span>{{ $order->delivery_address }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-7 py-5 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $order->updated_at->format('M j, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $order->updated_at->format('g:i A') }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden">
                    <div class="p-6 space-y-5">
                        @foreach($orders as $order)
                            <div class="history-card rounded-2xl p-5">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                                                <span class="font-bold text-blue-600">#</span>
                                            </div>
                                            <div>
                                                <div class="text-lg font-bold text-gray-900">{{ $order->order_number }}</div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $order->updated_at->format('M j, Y g:i A') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">
                                        {{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}
                                    </span>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user text-green-600 text-xs"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">Customer</div>
                                                <div class="text-xs text-gray-500">{{ $order->customer->name }}</div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-medium text-gray-900">Order Total</div>
                                            <div class="text-lg font-bold text-gray-900">
                                                ₱{{ number_format($order->grand_total, 2) }}</div>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-store text-red-600 text-xs"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">Restaurant</div>
                                                <div class="text-xs text-gray-500">{{ $order->restaurant->name }}</div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-medium text-gray-900">Your Earnings</div>
                                            <div class="text-lg font-bold text-green-600">
                                                ₱{{ number_format($order->delivery_fee, 2) }}</div>
                                        </div>
                                    </div>

                                    <div class="pt-3 border-t border-gray-100">
                                        <div class="flex items-start gap-2">
                                            <i class="fas fa-map-marker-alt text-gray-400 mt-0.5"></i>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 mb-1">Delivery Address</div>
                                                <div class="text-xs text-gray-600">{{ $order->delivery_address }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="px-7 py-6 border-t border-gray-100">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="text-sm text-gray-600">
                                Page {{ $orders->currentPage() }} of {{ $orders->lastPage() }}
                            </div>
                            <div class="flex items-center gap-2">
                                @if($orders->onFirstPage())
                                    <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-xl cursor-not-allowed">
                                        <i class="fas fa-chevron-left mr-2"></i> Previous
                                    </span>
                                @else
                                    <a href="{{ $orders->previousPageUrl() }}"
                                        class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-chevron-left mr-2"></i> Previous
                                    </a>
                                @endif

                                <div class="flex items-center gap-1">
                                    @foreach(range(1, min(5, $orders->lastPage())) as $page)
                                        @if($page == $orders->currentPage())
                                            <span
                                                class="w-10 h-10 flex items-center justify-center bg-orange-600 text-white rounded-xl font-medium">
                                                {{ $page }}
                                            </span>
                                        @else
                                            <a href="{{ $orders->url($page) }}"
                                                class="w-10 h-10 flex items-center justify-center bg-white border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endforeach

                                    @if($orders->lastPage() > 5)
                                        <span class="px-2 text-gray-500">...</span>
                                        <a href="{{ $orders->url($orders->lastPage()) }}"
                                            class="w-10 h-10 flex items-center justify-center bg-white border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                                            {{ $orders->lastPage() }}
                                        </a>
                                    @endif
                                </div>

                                @if($orders->hasMorePages())
                                    <a href="{{ $orders->nextPageUrl() }}"
                                        class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                                        Next <i class="fas fa-chevron-right ml-2"></i>
                                    </a>
                                @else
                                    <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-xl cursor-not-allowed">
                                        Next <i class="fas fa-chevron-right ml-2"></i>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

            @else
                <div class="p-12 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-history text-gray-400 text-3xl"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">No Delivery History Yet</h4>
                    <p class="text-gray-500 max-w-md mx-auto">Your completed deliveries will appear here once you start
                        making deliveries.</p>
                    <a href="{{ route('rider.dashboard') }}"
                        class="inline-flex items-center gap-2 mt-6 text-orange-600 hover:text-orange-700 font-medium">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Fade-in animation for page load
        document.addEventListener('DOMContentLoaded', function () {
            const fadeElements = document.querySelectorAll('.fade-in');
            fadeElements.forEach((element, index) => {
                element.style.animationDelay = `${index * 0.1}s`;
            });

            // Initialize SweetAlert2 with custom theme
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#1f2937',
                color: '#f9fafb',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            // Logout Confirmation
            const logoutForm = document.getElementById('logout-form');
            if (logoutForm) {
                logoutForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Logout Confirmation',
                        html: `<div class="text-center">
                            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-sign-out-alt text-red-600 text-2xl"></i>
                            </div>
                            <p class="text-gray-700">Are you sure you want to logout from your rider account?</p>
                            <p class="text-sm text-gray-500 mt-1">You will be redirected to the login page.</p>
                        </div>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: '<i class="fas fa-sign-out-alt mr-2"></i>Yes, Logout',
                        cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-2xl',
                            confirmButton: 'rounded-xl px-6 py-3 font-medium',
                            cancelButton: 'rounded-xl px-6 py-3 font-medium'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            logoutForm.submit();
                        }
                    });
                });
            }

            // Add click animation to buttons
            const buttons = document.querySelectorAll('button, a[href]');
            buttons.forEach(button => {
                button.addEventListener('click', function (e) {
                    if (!this.classList.contains('transition-all')) {
                        this.classList.add('active:scale-95', 'transition-transform');
                    }
                });
            });

            // Highlight table rows on hover (for desktop)
            const tableRows = document.querySelectorAll('.table-row');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function () {
                    this.style.backgroundColor = '#f9fafb';
                });
                row.addEventListener('mouseleave', function () {
                    this.style.backgroundColor = '';
                });
            });

            // Smooth scroll for pagination
            const paginationLinks = document.querySelectorAll('a[href*="page"]');
            paginationLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    if (this.getAttribute('href').includes('page')) {
                        e.preventDefault();
                        const href = this.getAttribute('href');

                        // Add loading state
                        const target = this;
                        target.classList.add('opacity-50', 'cursor-wait');
                        target.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Loading...';

                        // Smooth scroll to top
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });

                        // Navigate after scroll
                        setTimeout(() => {
                            window.location.href = href;
                        }, 500);
                    }
                });
            });
        });
    </script>
</body>

</html>