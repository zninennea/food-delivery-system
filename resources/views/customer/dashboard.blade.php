<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - NaNi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <img src="{{ asset('images/nani-logo.png') }}" alt="NaNi Logo" class="h-10 w-10 mr-3">
                    <div>
                        <a href="/" class="text-xl font-bold text-gray-800">NaNi</a>
                        <p class="text-xs text-gray-500 -mt-1">Customer Dashboard</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('customer.dashboard') }}" class="text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <a href="{{ route('customer.cart.index') }}" class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-shopping-cart mr-1"></i>Cart ({{ $cartCount }})
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-sign-out-alt mr-1"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Welcome Message -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Welcome, {{ Auth::user()->name }}!</h1>
            <p class="text-gray-600">Ready to order some delicious Japanese food?</p>
        </div>

        <!-- Featured Items -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Featured Items</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($featuredItems as $item)
                <div class="bg-white rounded-lg shadow p-4">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-48 object-cover rounded-md mb-4">
                    @else
                        <div class="w-full h-48 bg-gray-200 rounded-md mb-4 flex items-center justify-center">
                            <i class="fas fa-utensils text-gray-400 text-2xl"></i>
                        </div>
                    @endif
                    <h3 class="text-lg font-semibold text-gray-900">{{ $item->name }}</h3>
                    <p class="text-gray-600 text-sm mt-1">{{ Str::limit($item->description, 50) }}</p>
                    <div class="flex justify-between items-center mt-3">
                        <span class="text-lg font-bold text-orange-600">₱{{ number_format($item->price, 2) }}</span>
                        <button class="bg-orange-500 text-white px-3 py-1 rounded-md text-sm hover:bg-orange-600 add-to-cart" data-item-id="{{ $item->id }}">
                            Add to Cart
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Orders -->
        @if($recentOrders->count() > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Recent Orders</h2>
            <div class="space-y-4">
                @foreach($recentOrders as $order)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="font-medium text-gray-900">Order #{{ $order->order_number }}</h4>
                            <p class="text-gray-600 text-sm">Placed on {{ $order->created_at->format('M j, Y g:i A') }}</p>
                            <p class="text-gray-600 text-sm">Total: ₱{{ number_format($order->total_amount, 2) }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                            @if($order->status == 'delivered') bg-green-100 text-green-800
                            @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800
                            @else bg-blue-100 text-blue-800 @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('customer.track-order', $order) }}" class="text-orange-600 hover:text-orange-800 text-sm">
                            View Order Details
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add to cart functionality
            document.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', function() {
                    const itemId = this.getAttribute('data-item-id');
                    
                    fetch(`/customer/cart/${itemId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            quantity: 1
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            alert('Item added to cart!');
                            // You could update the cart count dynamically here
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            });
        });
    </script>
</body>
</html>