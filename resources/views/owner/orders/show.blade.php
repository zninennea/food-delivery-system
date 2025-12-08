<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #{{ $order->order_number }} - NaNi Owner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        .hover-card {
            transition: all 0.3s ease;
        }

        .hover-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-stone-50 text-gray-800 antialiased">

    <nav
        class="fixed w-full top-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('owner.dashboard') }}" class="flex-shrink-0 flex items-center gap-2 group">
                    <img src="https://i.imgur.com/vPOu1H2.png" alt="NaNi Icon"
                        class="h-20 w-auto group-hover:rotate-12 transition-transform duration-300">
                </a>

                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('owner.dashboard') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-home mr-1"></i> Dashboard
                    </a>
                    <a href="{{ route('owner.orders.index') }}"
                        class="text-orange-600 bg-orange-50 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-shopping-cart mr-1"></i> Orders
                    </a>
                    <a href="{{ route('owner.menu.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-utensils mr-1"></i> Menu
                    </a>
                    <a href="{{ route('owner.analytics.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
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

    <div class="pt-32 pb-16 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 fade-in">
                <div
                    class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-green-100 rounded-xl">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                        <div>
                            <strong class="font-bold">Success!</strong>
                            <span class="block">{{ session('success') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 fade-in">
                <div
                    class="bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-red-100 rounded-xl">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div>
                            <strong class="font-bold">Error!</strong>
                            <span class="block">{{ session('error') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Header -->
        <div class="mb-6 fade-in">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                <div>
                    <a href="{{ route('owner.orders.index') }}"
                        class="inline-flex items-center gap-2 text-stone-600 hover:text-orange-600 font-medium transition-colors group mb-4">
                        <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                        Back to Orders
                    </a>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Order #{{ $order->order_number }}</h1>
                    <p class="text-stone-500 mt-1">Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-4 py-2 text-sm font-bold rounded-full 
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'preparing') bg-blue-100 text-blue-800
                                @elseif($order->status == 'ready') bg-purple-100 text-purple-800
                                @elseif($order->status == 'on_the_way') bg-indigo-100 text-indigo-800
                                @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                    @if(in_array($order->status, ['delivered', 'cancelled']))
                        <i class="fas fa-lock text-stone-400" title="Finalized order"></i>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Customer Information -->
                <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden fade-in"
                    style="animation-delay: 0.1s;">
                    <div class="p-6 border-b border-stone-100 bg-gradient-to-r from-stone-50 to-white">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                            <div class="p-2 bg-orange-50 text-orange-600 rounded-xl">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            Customer Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <div>
                                    <span class="text-sm text-stone-500 block mb-1">Name</span>
                                    <p class="font-medium text-gray-900">{{ $order->customer->name }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-stone-500 block mb-1">Phone</span>
                                    <p class="font-medium text-gray-900">{{ $order->customer_phone }}</p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-sm text-stone-500 block mb-1">Delivery Address</span>
                                    <p class="font-medium text-gray-900">{{ $order->delivery_address }}</p>
                                </div>
                            </div>
                        </div>
                        @if($order->special_instructions)
                            <div class="mt-6 p-4 bg-orange-50 border border-orange-100 rounded-xl">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-sticky-note text-orange-500 mt-0.5"></i>
                                    <div>
                                        <span class="text-sm font-bold text-orange-700 block mb-1">Special
                                            Instructions</span>
                                        <p class="text-orange-800">{{ $order->special_instructions }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden fade-in"
                    style="animation-delay: 0.2s;">
                    <div class="p-6 border-b border-stone-100 bg-gradient-to-r from-stone-50 to-white">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                            <div class="p-2 bg-purple-50 text-purple-600 rounded-xl">
                                <i class="fas fa-utensils"></i>
                            </div>
                            Order Items ({{ $order->items->count() }})
                        </h3>
                    </div>
                    <div class="divide-y divide-stone-100">
                        @foreach($order->items as $item)
                            <div class="p-6 hover:bg-stone-50/50 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-start gap-4">
                                            <div class="w-16 h-16 bg-stone-100 rounded-xl overflow-hidden">
                                                @if($item->menuItem->image_url)
                                                    <img src="{{ asset('storage/' . $item->menuItem->image_url) }}"
                                                        alt="{{ $item->menuItem->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-stone-400">
                                                        <i class="fas fa-utensils text-lg"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-bold text-gray-900">{{ $item->menuItem->name }}</h4>
                                                <p class="text-sm text-stone-500 mt-1">{{ $item->menuItem->description }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-stone-500">₱{{ number_format($item->unit_price, 2) }} ×
                                            {{ $item->quantity }}
                                        </p>
                                        <p class="font-bold text-lg text-gray-900 mt-1">
                                            ₱{{ number_format($item->total, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-8">
                <!-- Payment Information -->
                <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden fade-in"
                    style="animation-delay: 0.3s;">
                    <div class="p-6 border-b border-stone-100 bg-gradient-to-r from-stone-50 to-white">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                            <div class="p-2 bg-green-50 text-green-600 rounded-xl">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            Payment Information
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-stone-600">Payment Method</span>
                                <span class="font-bold capitalize">
                                    @if($order->payment_method == 'gcash')
                                        <span class="flex items-center gap-2">
                                            <i class="fas fa-mobile-alt text-green-600"></i>
                                            GCash
                                        </span>
                                    @else
                                        <span class="flex items-center gap-2">
                                            <i class="fas fa-money-bill text-blue-600"></i>
                                            Cash on Delivery
                                        </span>
                                    @endif
                                </span>
                            </div>

                            @if($order->payment_method === 'cash_on_delivery' && $order->cash_provided)
                                <div class="bg-blue-50 p-4 rounded-xl space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-stone-600">Cash Provided</span>
                                        <span
                                            class="font-bold text-green-600">₱{{ number_format($order->cash_provided, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-stone-600">Order Total</span>
                                        <span class="font-bold">₱{{ number_format($order->grand_total, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between pt-2 border-t border-blue-200">
                                        <span class="text-stone-600">Change Due</span>
                                        <span class="font-bold text-blue-600">
                                            ₱{{ number_format($order->cash_provided - $order->grand_total, 2) }}
                                        </span>
                                    </div>
                                </div>
                            @elseif($order->payment_method === 'gcash')
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-stone-600">Payment Status</span>
                                        <span class="px-3 py-1 text-sm font-bold rounded-full 
                                                                        @if($order->gcash_payment_status === 'verified') bg-green-100 text-green-800
                                                                        @elseif($order->gcash_payment_status === 'rejected') bg-red-100 text-red-800
                                                                        @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($order->gcash_payment_status ?? 'pending') }}
                                        </span>
                                    </div>
                                    @if($order->gcash_reference_number)
                                        <div class="flex justify-between">
                                            <span class="text-stone-600">Reference Number</span>
                                            <span class="font-mono font-bold">{{ $order->gcash_reference_number }}</span>
                                        </div>
                                    @endif
                                    @if($order->gcash_receipt_path)
                                        <div class="pt-3 border-t border-stone-200">
                                            <a href="{{ route('owner.orders.gcash-receipt', $order) }}" target="_blank"
                                                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
                                                <i class="fas fa-receipt"></i>
                                                View GCash Receipt
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-gradient-to-br from-stone-900 to-stone-800 text-white rounded-3xl shadow-lg overflow-hidden fade-in"
                    style="animation-delay: 0.4s;">
                    <div class="p-6 border-b border-stone-700">
                        <h3 class="text-xl font-bold flex items-center gap-3">
                            <div class="p-2 bg-white/10 rounded-xl">
                                <i class="fas fa-receipt"></i>
                            </div>
                            Order Summary
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <div class="flex justify-between">
                            <span class="text-stone-300">Subtotal</span>
                            <span class="font-medium">₱{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-stone-300">Delivery Fee</span>
                            <span class="font-medium">₱{{ number_format($order->delivery_fee, 2) }}</span>
                        </div>

                        <div class="pt-3 border-t border-stone-700">
                            <div class="flex justify-between">
                                <span class="font-bold text-lg">Total</span>
                                <span class="font-bold text-xl">₱{{ number_format($order->grand_total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden fade-in"
                    style="animation-delay: 0.5s;">
                    <div class="p-6 border-b border-stone-100 bg-gradient-to-r from-stone-50 to-white">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                            <div class="p-2 bg-orange-50 text-orange-600 rounded-xl">
                                <i class="fas fa-cogs"></i>
                            </div>
                            Order Actions
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Status Update -->
                        @if(!in_array($order->status, ['delivered', 'cancelled']))
                            <div>
                                <label class="block text-sm font-bold text-stone-700 mb-2">Update Order Status</label>
                                <form action="{{ route('owner.orders.update-status', $order) }}" method="POST"
                                    class="space-y-3">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" required
                                        class="w-full px-4 py-3 border border-stone-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>
                                            Preparing</option>
                                        <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                                        <option value="on_the_way" {{ $order->status == 'on_the_way' ? 'selected' : '' }}>On
                                            the Way</option>
                                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>
                                            Delivered</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled</option>
                                    </select>
                                    <button type="submit"
                                        class="w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg shadow-blue-500/30">
                                        Update Status
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="p-4 bg-stone-100 rounded-xl text-center">
                                <i class="fas fa-lock text-stone-400 text-2xl mb-2"></i>
                                <p class="text-stone-600 font-medium">Order is {{ $order->status }}</p>
                                <p class="text-sm text-stone-500 mt-1">Status cannot be changed</p>
                            </div>
                        @endif

                        <!-- Rider Information -->
                        <div class="pt-4 border-t border-stone-200">
                            @if($order->rider)
                                <div class="mb-4">
                                    <label class="block text-sm font-bold text-stone-700 mb-2">Assigned Rider</label>
                                    <div class="flex items-center gap-3 p-3 bg-stone-50 rounded-xl">
                                        <div class="p-2 bg-orange-50 text-orange-600 rounded-lg">
                                            <i class="fas fa-motorcycle"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900">{{ $order->rider->name }}</p>
                                            <p class="text-sm text-stone-500">{{ $order->rider->phone }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Assign/Change Rider Button -->
                            @if(!in_array($order->status, ['delivered', 'cancelled']))
                                <a href="{{ route('owner.orders.assign-rider-form', $order) }}"
                                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white font-bold rounded-xl hover:from-orange-700 hover:to-red-700 transition-all shadow-lg shadow-orange-500/30">
                                    <i class="fas fa-motorcycle"></i>
                                    {{ $order->rider ? 'Change Rider' : 'Assign Rider' }}
                                </a>
                            @endif
                        </div>

                        <!-- Delete Order -->
                        @if(in_array($order->status, ['pending', 'cancelled']))
                            <div class="pt-4 border-t border-stone-200">
                                <form action="{{ route('owner.orders.destroy', $order) }}" method="POST"
                                    id="delete-order-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete()"
                                        class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-red-600 to-rose-600 text-white font-bold rounded-xl hover:from-red-700 hover:to-rose-700 transition-all shadow-lg shadow-red-500/30">
                                        <i class="fas fa-trash"></i>
                                        Delete Order
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Auto-refresh logic
        setTimeout(function () {
            window.location.reload();
        }, 60000); // Refresh every minute

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
    </script>
</body>

</html>