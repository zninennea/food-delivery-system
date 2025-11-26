<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Dashboard - NaNi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <img src="{{ asset('images/nani-logo.png') }}" alt="NaNi Logo" class="h-10 w-10 mr-3">
                    <div>
                        <a href="/" class="text-xl font-bold text-gray-800">NaNi</a>
                        <p class="text-xs text-gray-500 -mt-1">Rider Dashboard - {{ Auth::user()->name }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('rider.dashboard') }}"
                        class="text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-1"></i>Dashboard
                    </a>
                    <a href="{{ route('rider.order-history') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-history mr-1"></i>Order History
                    </a>
                    <!-- Profile Link - View Only -->
                    <a href="{{ route('rider.profile.show') }}"
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

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto mt-6 px-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <div class="flex items-center">
                    <div class="py-1">
                        <svg class="w-6 h-6 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <strong class="font-bold">Success! </strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto mt-6 px-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <div class="flex items-center">
                    <div class="py-1">
                        <svg class="w-6 h-6 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <strong class="font-bold">Error! </strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-motorcycle text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active Deliveries</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $activeDeliveries }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Today's Deliveries</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $todayDeliveries }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i class="fas fa-money-bill-wave text-purple-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Earnings</p>
                        <p class="text-2xl font-bold text-gray-900">₱{{ number_format($totalEarnings, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assigned Orders -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Assigned Orders</h3>
                <p class="text-gray-600 text-sm">Orders currently assigned to you</p>
            </div>

            @if($assignedOrders->count() > 0)
                <div class="p-6 space-y-4">
                    @foreach($assignedOrders as $order)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">Order #{{ $order->order_number }}</h4>
                                    <p class="text-gray-600 text-sm">{{ $order->customer->name }}</p>
                                    <p class="text-gray-600 text-sm">{{ $order->delivery_address }}</p>

                                    @if($order->payment_method === 'cash_on_delivery' && $order->cash_provided)
                                        <p class="text-green-600 text-sm font-medium">
                                            Cash: ₱{{ number_format($order->cash_provided, 2) }}
                                            (Change: ₱{{ number_format($order->cash_provided - $order->grand_total, 2) }})
                                        </p>
                                    @endif

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                                        <div>
                                            <p class="text-sm text-gray-600">Customer</p>
                                            <p class="font-medium">{{ $order->customer->name }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Restaurant</p>
                                            <p class="font-medium">{{ $order->restaurant->name }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Delivery Address</p>
                                            <p class="font-medium">{{ Str::limit($order->delivery_address, 40) }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                                    @if($order->status == 'preparing') bg-yellow-100 text-yellow-800
                                                                                    @elseif($order->status == 'ready') bg-blue-100 text-blue-800
                                                                                    @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex flex-col space-y-2 ml-4">
                                    <a href="{{ route('rider.orders.show', $order) }}"
                                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm text-center">
                                        <i class="fas fa-eye mr-1"></i>View Details
                                    </a>

                                    @if($order->status == 'ready')
                                        <form action="{{ route('rider.orders.update-status', $order) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="on_the_way">
                                            <button type="submit"
                                                class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 text-sm w-full">
                                                <i class="fas fa-play mr-1"></i>Start Delivery
                                            </button>
                                        </form>
                                    @endif

                                    @if($order->status == 'on_the_way')
                                        <form action="{{ route('rider.orders.update-status', $order) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="delivered">
                                            <button type="submit"
                                                class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 text-sm w-full"
                                                onclick="return confirm('Mark order #{{ $order->order_number }} as delivered?')">
                                                <i class="fas fa-check mr-1"></i>Mark Delivered
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-6 text-center">
                    <i class="fas fa-motorcycle text-gray-400 text-4xl mb-3"></i>
                    <p class="text-gray-500">No assigned orders at the moment.</p>
                    <p class="text-gray-400 text-sm">New orders will appear here when assigned to you.</p>
                </div>
            @endif
        </div>

        <!-- Recent Completed Orders -->
        @if($completedOrders->count() > 0)
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recent Completed Deliveries</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach($completedOrders as $order)
                            <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                                <div>
                                    <p class="font-medium text-gray-900">Order #{{ $order->order_number }}</p>
                                    <p class="text-sm text-gray-500">{{ $order->customer->name }} •
                                        {{ $order->restaurant->name }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">{{ $order->updated_at->format('M j, g:i A') }}</p>
                                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">Delivered</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('rider.order-history') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            View Full History <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Auto-refresh for new orders -->
    <script>
        // Auto-refresh every 30 seconds to check for new orders
        setTimeout(function () {
            window.location.reload();
        }, 30000);
    </script>
</body>

</html>