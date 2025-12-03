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
    <div data-user-role="{{ Auth::user()->role }}" style="display: none;"></div>
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <img src="{{ asset('images/nani-logo.png') }}" alt="NaNi Logo" class="h-10 w-10 mr-3">
                    <div>
                        <a href="/" class="text-xl font-bold text-gray-800">NaNi</a>
                        <p class="text-xs text-gray-500 -mt-1">Japanese Restaurant</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('customer.dashboard') }}"
                        class="text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <a href="{{ route('customer.menu') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-utensils mr-1"></i>Menu
                    </a>
                    <a href="{{ route('customer.cart.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-shopping-cart mr-1"></i>Cart ({{ $cartCount }})
                    </a>
                    <a href="{{ route('customer.orders.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-list mr-1"></i>My Orders
                    </a>
                    <a href="{{ route('customer.profile.show') }}"
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

    <!-- Hero Section -->
    <div class="bg-orange-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-4">Welcome to {{ $restaurant->name }}!</h1>
            <p class="text-xl mb-6">Authentic Japanese Cuisine Delivered to Your Doorstep</p>
            <a href="{{ route('customer.menu') }}"
                class="bg-white text-orange-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-200">
                <i class="fas fa-utensils mr-2"></i>Order Now
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-8 px-4">
        <!-- Restaurant Info -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $restaurant->name }}</h2>
                    <p class="text-gray-600 mt-1">{{ $restaurant->address }}</p>
                    <p class="text-gray-600">{{ $restaurant->phone }}</p>
                </div>
                <div class="text-right">
                    <div class="flex items-center text-yellow-400 mb-2">
                        @php
                            $averageRating = $averageRating ?? 4.5; // Fallback to 4.5 if not set
                            $totalReviews = $totalReviews ?? 128; // Fallback to 128 if not set
                            $fullStars = floor($averageRating);
                            $hasHalfStar = $averageRating - $fullStars >= 0.5;
                        @endphp

                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $fullStars)
                                <i class="fas fa-star"></i>
                            @elseif($i == $fullStars + 1 && $hasHalfStar)
                                <i class="fas fa-star-half-alt"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                        <span class="text-gray-600 ml-2">{{ number_format($averageRating, 1) }} ({{ $totalReviews }}
                            reviews)</span>
                    </div>
                    <p class="text-gray-600">Open until 10:00 PM</p>
                </div>
            </div>
        </div>

        <!-- Featured Items -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Featured Items</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($featuredItems as $item)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                                class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-utensils text-gray-400 text-4xl"></i>
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $item->name }}</h3>
                            <p class="text-gray-600 text-sm mt-1">{{ Str::limit($item->description, 80) }}</p>
                            <div class="flex justify-between items-center mt-3">
                                <span class="text-lg font-bold text-orange-600">₱{{ number_format($item->price, 2) }}</span>
                                <a href="{{ route('customer.menu-item', $item) }}"
                                    class="bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600 text-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('customer.menu') }}"
                class="bg-white rounded-lg shadow p-6 text-center hover:shadow-md transition duration-200">
                <i class="fas fa-utensils text-3xl text-orange-600 mb-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Browse Full Menu</h3>
                <p class="text-gray-600 mt-1">Explore all our delicious offerings</p>
            </a>

            <a href="{{ route('customer.orders.index') }}"
                class="bg-white rounded-lg shadow p-6 text-center hover:shadow-md transition duration-200">
                <i class="fas fa-clock text-3xl text-blue-600 mb-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Track Your Order</h3>
                <p class="text-gray-600 mt-1">Check order status in real-time</p>
            </a>

            <a href="{{ route('customer.profile.show') }}"
                class="bg-white rounded-lg shadow p-6 text-center hover:shadow-md transition duration-200">
                <i class="fas fa-user text-3xl text-green-600 mb-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Your Profile</h3>
                <p class="text-gray-600 mt-1">Manage your account details</p>
            </a>
        </div>

        <!-- Recent Orders -->
        @if($recentOrders->count() > 0)
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Recent Orders</h2>
                <div class="space-y-4">
                    @foreach($recentOrders as $order)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-medium text-gray-900">Order #{{ $order->order_number }}</h4>
                                    <p class="text-gray-600 text-sm">Placed on {{ $order->created_at->format('M j, Y g:i A') }}
                                    </p>
                                    <p class="text-gray-600 text-sm">Total: ₱{{ number_format($order->total_amount, 2) }}</p>
                                </div>
                                <span
                                    class="px-3 py-1 rounded-full text-sm font-medium 
                                                                                                                                            @if($order->status == 'delivered') bg-green-100 text-green-800
                                                                                                                                            @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800
                                                                                                                                            @else bg-blue-100 text-blue-800 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div class="mt-2 flex space-x-2">
                                <a href="{{ route('customer.track-order', $order) }}"
                                    class="text-orange-600 hover:text-orange-800 text-sm">
                                    Track Order
                                </a>
                                @if($order->status == 'delivered')
                                    <a href="{{ route('customer.reviews.create', $order) }}"
                                        class="text-blue-600 hover:text-blue-800 text-sm">
                                        Write Review
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Customer Reviews Section - Show ALL customer reviews -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-900">Customer Reviews</h2>
                <a href="{{ route('customer.reviews.index') }}"
                    class="text-sm text-orange-600 hover:text-orange-800 font-medium">
                    <i class="fas fa-comments mr-1"></i>View All Reviews
                </a>
            </div>

            @if($recentReviews->count() > 0)
                <div class="space-y-6">
                    @foreach($recentReviews as $review)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition duration-150">
                            <!-- Review Header with customer info -->
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center">
                                    <!-- Customer Avatar -->
                                    @if($review->customer->profile_picture)
                                        <img src="{{ asset('storage/' . $review->customer->profile_picture) }}"
                                            alt="{{ $review->customer->name }}" class="h-10 w-10 rounded-full object-cover mr-3">
                                    @else
                                        <div class="h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $review->customer->name }}</p>
                                        <div class="flex items-center">
                                            <div class="flex items-center text-yellow-400 mr-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i
                                                        class="fas fa-star{{ $i <= $review->restaurant_rating ? '' : '-o' }} text-sm"></i>
                                                @endfor
                                            </div>
                                            <span
                                                class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                    Order #{{ $review->order->order_number }}
                                </span>
                            </div>

                            <!-- Review Comment -->
                            @if($review->comment)
                                <p class="text-gray-700 mb-3">"{{ $review->comment }}"</p>
                            @else
                                <p class="text-gray-500 italic mb-3">No comment provided</p>
                            @endif

                            <!-- Ordered Items -->
                            @if($review->order && $review->order->items->count() > 0)
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Ordered:</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($review->order->items as $item)
                                            <div class="flex items-center text-sm bg-gray-100 rounded-full px-3 py-1">
                                                <span class="text-gray-700">{{ $item->quantity }}x {{ $item->menuItem->name }}</span>
                                                <span class="text-gray-500 ml-1">• ₱{{ number_format($item->unit_price, 2) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="flex justify-between items-center mt-3 text-sm">
                                        <span class="text-gray-600">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            {{ $review->order->created_at->format('M d, Y') }}
                                        </span>
                                        <span class="font-medium text-gray-900">
                                            Total: ₱{{ number_format($review->order->total_amount, 2) }}
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <!-- Rider Rating (if exists) -->
                            @if($review->rider_rating)
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <div class="flex items-center">
                                        <i class="fas fa-motorcycle text-gray-400 mr-2"></i>
                                        <span class="text-sm text-gray-600 mr-2">Rider:</span>
                                        <div class="flex items-center text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $review->rider_rating ? '' : '-o' }} text-xs"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- View All Reviews Link -->
                <div class="mt-6 text-center">
                    <a href="{{ route('customer.reviews.index') }}"
                        class="inline-flex items-center text-orange-600 hover:text-orange-800 font-medium">
                        <i class="fas fa-arrow-right mr-2"></i>See More Reviews
                    </a>
                </div>
            @else
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-comments text-4xl mb-2"></i>
                    <p>You have no reviews yet.</p>
                    <p class="text-sm mt-2">Share your experience after your next order!</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('images/nani-logo.png') }}" alt="NaNi Logo" class="h-16 w-16">
            </div>
            <h4 class="text-lg font-semibold">{{ $restaurant->name }}</h4>
            <p class="mt-2 text-gray-400">{{ $restaurant->address }}</p>
            <p class="text-gray-400">{{ $restaurant->phone }}</p>
            <div class="mt-4">
                <a href="#" class="text-gray-400 hover:text-white mx-2">
                    <i class="fab fa-facebook text-xl"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-white mx-2">
                    <i class="fab fa-instagram text-xl"></i>
                </a>
            </div>
        </div>
    </footer>
</body>

</html>
<!-- Add this debug section to dashboard.blade.php -->
@php
    $allRoutes = Route::getRoutes()->getRoutesByName();
    $reviewRoutes = [];
    foreach ($allRoutes as $name => $route) {
        if (str_contains($name, 'review')) {
            $reviewRoutes[$name] = $route->uri();
        }
    }
@endphp

<div style="display: none;">
    <pre>{{ print_r($reviewRoutes, true) }}</pre>
</div>