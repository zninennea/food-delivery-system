<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - NaNi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-50">
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
                            <p class="text-xs text-gray-500 -mt-1">Customer Dashboard</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Welcome, {{ Auth::user()->name }}!</span>
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

    <!-- Hero Section -->
    <div class="bg-cover bg-center h-64"
        style="background-image: url('https://images.unsplash.com/photo-1555939594-58d7cb5f3adf?q=80&w=2070');">
        <div class="bg-black bg-opacity-50 h-full flex items-center justify-center">
            <div class="text-center text-white">
                <h2 class="text-4xl font-bold mb-4">Welcome to NaNi</h2>
                <p class="text-xl">Authentic Japanese Cuisine</p>
                <a href="{{ route('customer.menu') }}"
                    class="mt-6 inline-block bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700">
                    Order Now
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Featured Items -->
        <div class="mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Featured Items</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($featuredItems as $item)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <img src="{{ $item->image ?? 'https://via.placeholder.com/300x200' }}" alt="{{ $item->name }}"
                            class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h4 class="font-bold text-lg mb-2">{{ $item->name }}</h4>
                            <p class="text-gray-600 text-sm mb-4">{{ Str::limit($item->description, 100) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-lg">₱{{ number_format($item->price, 2) }}</span>
                                <form action="{{ route('customer.cart.add', $item) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit"
                                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                        Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Recent Orders</h3>
            </div>
            <div class="p-6">
                @forelse($recentOrders as $order)
                    <div class="border border-gray-200 rounded-lg p-4 mb-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h4 class="font-medium text-gray-900">Order #{{ $order->order_number }}</h4>
                                <p class="text-gray-600">{{ $order->created_at->format('M j, Y g:i A') }}</p>
                                <p class="text-sm">Total: ₱{{ number_format($order->total_amount, 2) }}</p>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status == 'preparing') bg-blue-100 text-blue-800
                                            @elseif($order->status == 'ready') bg-green-100 text-green-800
                                            @elseif($order->status == 'on_the_way') bg-purple-100 text-purple-800
                                            @elseif($order->status == 'delivered') bg-gray-100 text-gray-800
                                            @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                                <div class="mt-2">
                                    <a href="{{ route('customer.track-order', $order) }}"
                                        class="text-blue-600 hover:text-blue-900 text-sm">
                                        Track Order
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No recent orders. <a href="{{ route('customer.menu') }}"
                            class="text-blue-600 hover:text-blue-900">Start ordering!</a></p>
                @endforelse
            </div>
        </div>
    </div>
</body>

</html>