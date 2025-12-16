<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rider Dashboard - NaNi</title>
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

        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%);
            border: 1px solid rgba(229, 231, 235, 0.5);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.1);
        }

        .order-card {
            background: linear-gradient(135deg, #ffffff 0%, #fefefe 100%);
            border: 1px solid rgba(229, 231, 235, 0.5);
            transition: all 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -10px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>

<body class="bg-stone-50 text-gray-800 antialiased">
    <div data-user-role="{{ Auth::user()->role }}" style="display: none;"></div>

    <!-- Navigation -->
    <nav
        class="fixed w-full top-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-2 group">
                    <img src="{{ asset('images/NaNi_Logo.png') }}" alt="NaNi Logo"
                        class="h-20 w-auto group-hover:rotate-12 transition-transform duration-300">
                    <div class="ml-2">
                        <a href="/" class="text-xl font-bold text-gray-800 font-serif">NaNi</a>
                        <p class="text-xs text-gray-500 -mt-1">Rider Dashboard - {{ Auth::user()->name }}</p>
                    </div>
                </div>

                <div class="hidden md:flex items-center space-x-2">
                    <a href="{{ route('rider.dashboard') }}"
                        class="text-orange-600 hover:bg-orange-50 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('rider.order-history') }}"
                        class="text-gray-600 hover:text-orange-600 hover:bg-gray-50 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors">
                        <i class="fas fa-history mr-2"></i>Order History
                    </a>
                    <a href="{{ route('rider.profile.show') }}"
                        class="text-gray-600 hover:text-orange-600 hover:bg-gray-50 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors">
                        <i class="fas fa-user mr-2"></i>Profile
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

    @if(session('error'))
        <div class="max-w-7xl mx-auto mt-28 px-4 fade-in">
            <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-2xl shadow-sm flex items-center gap-4"
                role="alert">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-red-600"></i>
                </div>
                <div>
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            </div>
        </div>
    @endif

    <div class="pt-32 pb-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 fade-in">
            <div class="stat-card rounded-3xl shadow-sm p-7">
                <div class="flex items-center">
                    <div
                        class="p-4 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg shadow-blue-500/30">
                        <i class="fas fa-motorcycle text-white text-xl"></i>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm font-medium text-gray-600">Active Deliveries</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $activeDeliveries }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500 flex items-center gap-2">
                        <i class="fas fa-clock text-orange-500"></i>
                        Currently on route
                    </p>
                </div>
            </div>

            <div class="stat-card rounded-3xl shadow-sm p-7">
                <div class="flex items-center">
                    <div
                        class="p-4 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg shadow-green-500/30">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm font-medium text-gray-600">Today's Deliveries</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $todayDeliveries }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500 flex items-center gap-2">
                        <i class="fas fa-calendar-day text-green-500"></i>
                        Completed today
                    </p>
                </div>
            </div>

            <div class="stat-card rounded-3xl shadow-sm p-7">
                <div class="flex items-center">
                    <div
                        class="p-4 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg shadow-purple-500/30">
                        <i class="fas fa-money-bill-wave text-white text-xl"></i>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm font-medium text-gray-600">Total Earnings</p>
                        <p class="text-3xl font-bold text-gray-900">₱{{ number_format($totalEarnings, 2) }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500 flex items-center gap-2">
                        <i class="fas fa-wallet text-purple-500"></i>
                        Lifetime earnings
                    </p>
                </div>
            </div>
        </div>

        <!-- Assigned Orders -->
        <div class="bg-white rounded-3xl shadow-sm border border-stone-100 mb-8 fade-in" style="animation-delay: 0.1s;">
            <div class="p-7 border-b border-stone-100 bg-stone-50/50">
                <h3 class="text-xl font-bold text-gray-900 font-serif">Assigned Orders</h3>
                <p class="text-gray-600 text-sm mt-1">Orders currently assigned to you</p>
            </div>

            @if($assignedOrders->count() > 0)
                <div class="p-7 space-y-5">
                    @foreach($assignedOrders as $order)
                        <div class="order-card rounded-2xl p-6">
                            <div class="flex flex-col md:flex-row md:items-start justify-between gap-5">
                                <div class="flex-1">
                                    <div class="flex items-start gap-4">
                                        <div
                                            class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center text-orange-500 font-bold text-lg">
                                            #{{ $loop->iteration }}
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex flex-col md:flex-row md:items-center gap-3 mb-3">
                                                <h4 class="text-lg font-bold text-gray-900">Order #{{ $order->order_number }}
                                                </h4>
                                                <span class="px-3 py-1 text-xs font-bold rounded-full uppercase tracking-wider
                                                    @if($order->status == 'preparing') bg-yellow-100 text-yellow-800
                                                    @elseif($order->status == 'ready') bg-blue-100 text-blue-800
                                                    @else bg-green-100 text-green-800 @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                </span>
                                            </div>
                                            <p class="text-gray-600 mb-4 flex items-center gap-2">
                                                <i class="fas fa-user text-gray-400"></i>
                                                {{ $order->customer->name }}
                                            </p>

                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-4">
                                                <div class="bg-gray-50 rounded-xl p-4">
                                                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Customer</p>
                                                    <p class="font-bold text-gray-900">{{ $order->customer->name }}</p>
                                                </div>
                                                <div class="bg-gray-50 rounded-xl p-4">
                                                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Restaurant
                                                    </p>
                                                    <p class="font-bold text-gray-900">{{ $order->restaurant->name }}</p>
                                                </div>
                                                <div class="bg-gray-50 rounded-xl p-4">
                                                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Delivery
                                                        Address</p>
                                                    <p class="font-bold text-gray-900">
                                                        {{ Str::limit($order->delivery_address, 30) }}</p>
                                                </div>
                                            </div>

                                            @if($order->payment_method === 'cash_on_delivery' && $order->cash_provided)
                                                <div class="mt-4 bg-green-50 rounded-xl p-4 border border-green-100">
                                                    <p class="text-green-700 text-sm font-medium">
                                                        <i class="fas fa-money-bill-wave mr-2"></i>
                                                        Cash: ₱{{ number_format($order->cash_provided, 2) }}
                                                        (Change:
                                                        ₱{{ number_format($order->cash_provided - $order->grand_total, 2) }})
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col space-y-3 min-w-[180px]">
                                    <a href="{{ route('rider.orders.show', $order) }}"
                                        class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-5 py-3 rounded-xl font-medium hover:shadow-lg shadow-blue-500/30 hover:-translate-y-1 transition-all text-center">
                                        <i class="fas fa-eye mr-2"></i>View Details
                                    </a>

                                    @if($order->status == 'ready')
                                        <form action="{{ route('rider.orders.update-status', $order) }}" method="POST"
                                            class="delivery-start-form">
                                            @csrf
                                            <input type="hidden" name="status" value="on_the_way">
                                            <button type="button"
                                                class="delivery-start-btn w-full bg-gradient-to-r from-green-600 to-green-700 text-white px-5 py-3 rounded-xl font-medium hover:shadow-lg shadow-green-500/30 hover:-translate-y-1 transition-all"
                                                data-order-number="{{ $order->order_number }}">
                                                <i class="fas fa-play mr-2"></i>Start Delivery
                                            </button>
                                        </form>
                                    @endif

                                    @if($order->status == 'on_the_way')
                                        <form action="{{ route('rider.orders.update-status', $order) }}" method="POST"
                                            class="delivery-complete-form">
                                            @csrf
                                            <input type="hidden" name="status" value="delivered">
                                            <button type="button"
                                                class="delivery-complete-btn w-full bg-gradient-to-r from-green-600 to-green-700 text-white px-5 py-3 rounded-xl font-medium hover:shadow-lg shadow-green-500/30 hover:-translate-y-1 transition-all"
                                                data-order-number="{{ $order->order_number }}">
                                                <i class="fas fa-check mr-2"></i>Mark Delivered
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-motorcycle text-gray-400 text-3xl"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">No Assigned Orders</h4>
                    <p class="text-gray-500 max-w-md mx-auto">You don't have any assigned orders at the moment. New orders
                        will appear here when assigned to you.</p>
                    <div class="mt-6 animate-pulse">
                        <div class="w-64 h-3 bg-gray-200 rounded-full mx-auto"></div>
                        <div class="w-48 h-3 bg-gray-200 rounded-full mx-auto mt-2"></div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Recent Completed Orders -->
        @if($completedOrders->count() > 0)
            <div class="bg-white rounded-3xl shadow-sm border border-stone-100 fade-in" style="animation-delay: 0.2s;">
                <div class="p-7 border-b border-stone-100 bg-stone-50/50">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 font-serif">Recent Completed Deliveries</h3>
                            <p class="text-gray-600 text-sm mt-1">Your latest successful deliveries</p>
                        </div>
                        <a href="{{ route('rider.order-history') }}"
                            class="text-orange-600 hover:text-orange-700 text-sm font-medium flex items-center gap-2">
                            View All <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <div class="p-7">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach($completedOrders as $order)
                            <div class="bg-gray-50 rounded-2xl p-5 hover:bg-gray-100 transition-colors">
                                <div class="flex items-center justify-between mb-4">
                                    <div
                                        class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $order->updated_at->format('g:i A') }}</span>
                                </div>
                                <h4 class="font-bold text-gray-900 mb-2">Order #{{ $order->order_number }}</h4>
                                <p class="text-sm text-gray-600 mb-3">{{ $order->customer->name }} •
                                    {{ $order->restaurant->name }}</p>
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-sm font-bold text-gray-900">₱{{ number_format($order->grand_total, 2) }}</span>
                                    <span class="text-xs px-2 py-1 bg-gray-200 text-gray-700 rounded-full">Delivered</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Auto-refresh every 30 seconds to check for new orders
        setTimeout(function () {
            window.location.reload();
        }, 30000);

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
                            <p class="text-gray-700">Are you sure you want to logout?</p>
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

            // Start Delivery Confirmation
            document.querySelectorAll('.delivery-start-btn').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const orderNumber = this.getAttribute('data-order-number');
                    const form = this.closest('.delivery-start-form');

                    Swal.fire({
                        title: 'Start Delivery',
                        html: `<div class="text-center">
                            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-play text-green-600 text-2xl"></i>
                            </div>
                            <p class="text-lg font-bold text-gray-900">Order #${orderNumber}</p>
                            <p class="text-gray-600 mt-2">Are you ready to start the delivery?</p>
                            <div class="mt-4 p-3 bg-blue-50 rounded-xl border border-blue-100">
                                <p class="text-sm text-blue-700">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    You will be responsible for picking up and delivering this order.
                                </p>
                            </div>
                        </div>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: '<i class="fas fa-play mr-2"></i>Start Delivery',
                        cancelButtonText: '<i class="fas fa-times mr-2"></i>Not Yet',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-2xl',
                            confirmButton: 'rounded-xl px-6 py-3 font-medium',
                            cancelButton: 'rounded-xl px-6 py-3 font-medium'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Toast.fire({
                                icon: 'success',
                                title: `Delivery started for order #${orderNumber}`
                            });
                            setTimeout(() => {
                                form.submit();
                            }, 1500);
                        }
                    });
                });
            });

            // Mark as Delivered Confirmation
            document.querySelectorAll('.delivery-complete-btn').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const orderNumber = this.getAttribute('data-order-number');
                    const form = this.closest('.delivery-complete-form');

                    Swal.fire({
                        title: 'Confirm Delivery',
                        html: `<div class="text-center">
                            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                            </div>
                            <p class="text-lg font-bold text-gray-900">Order #${orderNumber}</p>
                            <p class="text-gray-600 mt-2">Have you successfully delivered this order to the customer?</p>
                            
                            <div class="mt-4 p-4 bg-yellow-50 rounded-xl border border-yellow-100 text-left">
                                <p class="text-sm font-medium text-yellow-800 mb-2">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Please ensure:
                                </p>
                                <ul class="text-sm text-yellow-700 space-y-1">
                                    <li class="flex items-center">
                                        <i class="fas fa-check-circle text-xs mr-2"></i>
                                        Order was received by the correct customer
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check-circle text-xs mr-2"></i>
                                        Payment was collected (if COD)
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check-circle text-xs mr-2"></i>
                                        Customer is satisfied with the delivery
                                    </li>
                                </ul>
                            </div>
                        </div>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: '<i class="fas fa-check mr-2"></i>Yes, Delivered',
                        cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
                        reverseButtons: true,
                        showCloseButton: true,
                        customClass: {
                            popup: 'rounded-2xl',
                            confirmButton: 'rounded-xl px-6 py-3 font-medium',
                            cancelButton: 'rounded-xl px-6 py-3 font-medium'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Toast.fire({
                                icon: 'success',
                                title: `Order #${orderNumber} marked as delivered`
                            });
                            setTimeout(() => {
                                form.submit();
                            }, 1500);
                        }
                    });
                });
            });

            // Add click animation to buttons
            const buttons = document.querySelectorAll('button, a[href]');
            buttons.forEach(button => {
                button.addEventListener('click', function (e) {
                    if (!this.classList.contains('transition-all')) {
                        this.classList.add('active:scale-95', 'transition-transform');
                    }
                });
            });
        });
    </script>
</body>

</html>