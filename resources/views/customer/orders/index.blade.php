<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - NaNi</title>
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
                        <p class="text-xs text-gray-500 -mt-1">My Orders</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('customer.dashboard') }}" 
                       class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <a href="{{ route('customer.orders.index') }}" 
                       class="text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-list mr-1"></i>My Orders
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

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">My Orders</h2>
                <p class="text-gray-600">Your order history</p>
            </div>

            @if($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Order #
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Items
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $order->created_at->format('M j, Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->created_at->format('g:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            @foreach($order->items as $item)
                                                {{ $item->quantity }}x {{ $item->menuItem->name }}<br>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">₱{{ number_format($order->total_amount, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status == 'preparing') bg-blue-100 text-blue-800
                                            @elseif($order->status == 'ready') bg-green-100 text-green-800
                                            @elseif($order->status == 'on_the_way') bg-purple-100 text-purple-800
                                            @elseif($order->status == 'delivered') bg-gray-100 text-gray-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('customer.track-order', $order) }}" 
                                           class="text-blue-600 hover:text-blue-900">Track Order</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $orders->links() }}
                    </div>
                @endif

            @else
                <div class="p-6 text-center">
                    <i class="fas fa-shopping-bag text-gray-400 text-4xl mb-3"></i>
                    <p class="text-gray-500">You haven't placed any orders yet.</p>
                    <a href="{{ route('customer.menu') }}" 
                       class="mt-4 inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
                        <i class="fas fa-utensils mr-2"></i>Browse Menu
                    </a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>