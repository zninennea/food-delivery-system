<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - NaNi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div data-user-role="{{ Auth::user()->role }}" style="display: none;"></div>
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
                            <p class="text-xs text-gray-500 -mt-1">Admin Dashboard</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('owner.dashboard') }}"
                        class="text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <a href="{{ route('owner.menu.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-utensils mr-1"></i>Menu
                    </a>
                    <a href="{{ route('owner.orders.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-shopping-cart mr-1"></i>Orders
                    </a>
                    <a href="{{ route('owner.analytics.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-chart-bar mr-1"></i>Analytics
                    </a>
                    <a href="{{ route('owner.reviews.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-star mr-1"></i>Reviews
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

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Restaurant Header - DYNAMIC DATA -->
        <div class="bg-white rounded-lg shadow mb-6 p-6">
            <h2 class="text-2xl font-bold text-gray-800">Welcome to {{ $restaurant->name }}</h2>
            <p class="text-gray-600">{{ $restaurant->address }} • {{ $restaurant->phone }}</p>
        </div>

        <!-- Stats Cards - DYNAMIC DATA -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Today's Orders -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-lg">
                        <i class="fas fa-shopping-cart text-orange-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Today's Orders</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['today_orders'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Active Orders -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-clock text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active Orders</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['active_orders'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Pending -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <i class="fas fa-hourglass-half text-yellow-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_orders'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Average Rating -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i class="fas fa-star text-purple-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Avg. Rating</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['average_rating'], 1) }}/5
                        </p>
                        <p class="text-xs text-gray-500">{{ $stats['total_reviews'] }} reviews</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <a href="{{ route('owner.menu.create') }}"
                class="bg-white rounded-lg shadow p-6 text-left hover:bg-gray-50 transition duration-200 block">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Add New Item</h3>
                        <p class="text-gray-600 mt-1">Add new menu items to your restaurant</p>
                    </div>
                    <i class="fas fa-plus-circle text-2xl text-orange-600"></i>
                </div>
            </a>


        </div>

        <!-- Active Orders Section - DYNAMIC DATA -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Active Orders</h3>
            </div>

            @if($activeOrders->count() > 0)
                <div class="p-6 space-y-4">
                    @foreach($activeOrders as $order)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Order #{{ $order->order_number }}</h3>
                                    <p class="text-gray-600">Customer: {{ $order->customer->name }}</p>
                                    <p class="text-gray-600">Address: {{ $order->delivery_address }}</p>
                                    <p class="text-gray-600">Phone: {{ $order->customer_phone }}</p>
                                    @if($order->rider)
                                        <p class="text-gray-600">Rider: {{ $order->rider->name }}</p>
                                    @else
                                        <p class="text-gray-600 text-yellow-600">No rider assigned</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                                                        @if($order->status == 'preparing') bg-yellow-100 text-yellow-800
                                                                        @elseif($order->status == 'ready') bg-green-100 text-green-800
                                                                        @elseif($order->status == 'on_the_way') bg-blue-100 text-blue-800
                                                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                    <p class="text-sm text-gray-500 mt-1">{{ $order->created_at->format('M j, Y g:i A') }}</p>
                                </div>
                            </div>
                            <div class="mt-4 flex space-x-3">
                                <a href="{{ route('owner.orders.show', $order) }}"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">
                                    View Details
                                </a>

                                @if(!$order->rider)
                                    <a href="{{ route('owner.orders.assign-rider-form', $order) }}"
                                        class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 text-sm">
                                        Assign Rider
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-6 text-center">
                    <i class="fas fa-shopping-cart text-gray-400 text-4xl mb-3"></i>
                    <p class="text-gray-500">No active orders at the moment.</p>
                </div>
            @endif
        </div>
    </div>
    <!-- Recent Reviews Section -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">Recent Customer Reviews</h3>
            <a href="{{ route('owner.reviews.index') }}"
                class="text-sm text-orange-600 hover:text-orange-800 font-medium">
                View All Reviews <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        @if($recentReviews->count() > 0)
            <div class="p-6 space-y-4">
                @foreach($recentReviews as $review)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center">
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
                                        <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                Order #{{ $review->order->order_number }}
                            </span>
                        </div>

                        @if($review->comment)
                            <p class="text-gray-700 text-sm mb-2">"{{ Str::limit($review->comment, 100) }}"</p>
                        @else
                            <p class="text-gray-400 italic text-sm mb-2">No comment provided</p>
                        @endif

                        @if($review->rider_rating)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-motorcycle mr-2"></i>
                                <span class="mr-2">Rider Rating:</span>
                                <div class="flex items-center text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= $review->rider_rating ? '' : '-o' }} text-xs"></i>
                                    @endfor
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-6 text-center">
                <i class="fas fa-comments text-gray-400 text-4xl mb-3"></i>
                <p class="text-gray-500">No reviews yet.</p>
            </div>
        @endif
    </div>

    <script>
        // Add any JavaScript functionality here
        // Auto-refresh dashboard every 30 seconds to show real-time updates
        setTimeout(function () {
            window.location.reload();
        }, 30000); // 30 seconds
    </script>
</body>

</html>