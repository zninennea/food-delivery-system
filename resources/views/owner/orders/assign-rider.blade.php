<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Rider - NaNi Owner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        h1, h2, h3, h4, h5 {
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
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-stone-50 text-gray-800 antialiased">

    <nav class="fixed w-full top-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
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

    <div class="pt-32 pb-16 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 fade-in">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl shadow-sm">
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
                <div class="bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl shadow-sm">
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

        <!-- Back Button -->
        <div class="mb-6 fade-in">
            <a href="{{ route('owner.orders.show', $order) }}"
                class="inline-flex items-center gap-2 text-stone-600 hover:text-orange-600 font-medium transition-colors group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                Back to Order Details
            </a>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden fade-in" style="animation-delay: 0.1s;">
            <!-- Header -->
            <div class="bg-gradient-to-r from-stone-900 to-stone-800 text-white p-8">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold mb-2">Assign Rider</h1>
                        <p class="text-stone-300">Order #{{ $order->order_number }} • {{ $order->customer->name }}</p>
                    </div>
                    <div class="p-3 bg-white/10 rounded-xl">
                        <i class="fas fa-motorcycle text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <form action="{{ route('owner.orders.assign-rider', $order) }}" method="POST" class="space-y-8">
                    @csrf

                    <!-- Rider Selection -->
                    <div class="space-y-4">
                        <label class="block">
                            <span class="text-sm font-bold text-stone-700 mb-2 block">Select Rider</span>
                            <select name="rider_id" id="rider_id" required
                                class="w-full px-4 py-3 border border-stone-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all appearance-none bg-white">
                                <option value="" class="text-stone-400">-- Choose a rider --</option>
                                @foreach($availableRiders as $rider)
                                    <option value="{{ $rider->id }}" 
                                            class="py-2"
                                            {{ $order->rider_id == $rider->id ? 'selected' : '' }}>
                                        {{ $rider->name }}
                                        <span class="text-stone-500">
                                            • {{ $rider->vehicle_type }} ({{ $rider->vehicle_plate }})
                                            • <span class="font-bold {{ $rider->status == 'active' ? 'text-green-600' : 'text-yellow-600' }}">
                                                {{ ucfirst($rider->status) }}
                                            </span>
                                        </span>
                                    </option>
                                @endforeach
                            </select>
                        </label>
                        @error('rider_id')
                            <p class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Order Summary -->
                    <div class="bg-stone-50 rounded-2xl p-6 space-y-4 border border-stone-200">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-receipt text-orange-500"></i>
                            Order Summary
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <div>
                                    <span class="text-sm text-stone-500">Customer</span>
                                    <p class="font-medium">{{ $order->customer->name }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-stone-500">Phone</span>
                                    <p class="font-medium">{{ $order->customer_phone }}</p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-sm text-stone-500">Address</span>
                                    <p class="font-medium">{{ $order->delivery_address }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-stone-500">Total Amount</span>
                                    <p class="font-bold text-lg text-gray-900">₱{{ number_format($order->grand_total ?? $order->total_amount, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-stone-200">
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-stone-500">Current Status:</span>
                                <span class="px-3 py-1 text-sm font-bold rounded-full 
                                        @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status == 'preparing') bg-blue-100 text-blue-800
                                        @elseif($order->status == 'ready') bg-purple-100 text-purple-800
                                        @elseif($order->status == 'on_the_way') bg-indigo-100 text-indigo-800
                                        @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                        @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </div>
                            
                            @if($order->special_instructions)
                                <div class="text-sm text-stone-600 italic">
                                    <i class="fas fa-info-circle text-orange-500 mr-1"></i>
                                    Has special instructions
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-3 pt-6 border-t border-stone-200">
                        <a href="{{ route('owner.orders.show', $order) }}"
                            class="px-6 py-3 bg-stone-200 text-stone-700 font-bold rounded-xl hover:bg-stone-300 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white font-bold rounded-xl hover:from-orange-700 hover:to-red-700 transition-all shadow-lg shadow-orange-500/30">
                            <i class="fas fa-motorcycle mr-2"></i>
                            Assign Rider
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Available Riders Grid -->
        @if($availableRiders->count() > 0)
            <div class="mt-8 fade-in" style="animation-delay: 0.2s;">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Available Riders</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($availableRiders as $rider)
                        <div class="bg-white p-4 rounded-2xl border border-stone-200 hover-card">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-orange-50 text-orange-600 rounded-xl">
                                        <i class="fas fa-motorcycle"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900">{{ $rider->name }}</h4>
                                        <p class="text-sm text-stone-500">{{ $rider->vehicle_type }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold px-2 py-1 rounded-full 
                                              {{ $rider->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($rider->status) }}
                                </span>
                            </div>
                            <div class="mt-4 grid grid-cols-2 gap-2 text-sm">
                                <div>
                                    <span class="text-stone-500">Plate:</span>
                                    <span class="font-medium ml-1">{{ $rider->vehicle_plate }}</span>
                                </div>
                                <div>
                                    <span class="text-stone-500">Phone:</span>
                                    <span class="font-medium ml-1">{{ $rider->phone }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
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