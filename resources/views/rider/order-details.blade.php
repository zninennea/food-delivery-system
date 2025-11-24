<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #{{ $order->order_number }} - NaNi Rider</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-800">Order #{{ $order->order_number }}</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('rider.dashboard') }}" 
                       class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-arrow-left mr-1"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="max-w-4xl mx-auto mt-6 px-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <div class="flex items-center">
                    <div class="py-1">
                        <svg class="w-6 h-6 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <!-- Order Header -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Order #{{ $order->order_number }}</h2>
                        <p class="text-gray-600">Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                            @if($order->status == 'preparing') bg-yellow-100 text-yellow-800
                            @elseif($order->status == 'ready') bg-blue-100 text-blue-800
                            @elseif($order->status == 'on_the_way') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Customer & Restaurant Info -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Customer Information</h3>
                        <div class="space-y-2">
                            <div>
                                <p class="text-sm text-gray-600">Name</p>
                                <p class="font-medium">{{ $order->customer->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Phone</p>
                                <p class="font-medium">{{ $order->customer_phone }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Delivery Address</p>
                                <p class="font-medium">{{ $order->delivery_address }}</p>
                            </div>
                            @if($order->special_instructions)
                            <div>
                                <p class="text-sm text-gray-600">Special Instructions</p>
                                <p class="font-medium text-orange-600">{{ $order->special_instructions }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Restaurant Information</h3>
                        <div class="space-y-2">
                            <div>
                                <p class="text-sm text-gray-600">Name</p>
                                <p class="font-medium">{{ $order->restaurant->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Address</p>
                                <p class="font-medium">{{ $order->restaurant->address }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Phone</p>
                                <p class="font-medium">{{ $order->restaurant->phone }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Order Items</h3>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <div>
                            <p class="font-medium text-gray-900">{{ $item->menuItem->name }}</p>
                            <p class="text-sm text-gray-500">₱{{ number_format($item->unit_price, 2) }} × {{ $item->quantity }}</p>
                        </div>
                        <p class="font-medium text-gray-900">₱{{ number_format($item->total, 2) }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary -->
            <div class="px-6 py-4">
                <div class="flex justify-between items-center text-lg font-bold">
                    <span>Total Amount:</span>
                    <span>₱{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex justify-center space-x-4">
                    @if($order->status == 'ready')
                    <form action="{{ route('rider.orders.update-status', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="on_the_way">
                        <button type="submit" 
                                class="bg-green-600 text-white px-6 py-3 rounded-md hover:bg-green-700 font-medium">
                            <i class="fas fa-play mr-2"></i>Start Delivery
                        </button>
                    </form>
                    @endif

                    @if($order->status == 'on_the_way')
                    <form action="{{ route('rider.orders.update-status', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="delivered">
                        <button type="submit" 
                                class="bg-purple-600 text-white px-6 py-3 rounded-md hover:bg-purple-700 font-medium"
                                onclick="return confirm('Mark order #{{ $order->order_number }} as delivered?')">
                            <i class="fas fa-check mr-2"></i>Mark as Delivered
                        </button>
                    </form>
                    @endif

                    @if($order->status == 'delivered')
                    <div class="text-center">
                        <p class="text-green-600 font-medium">
                            <i class="fas fa-check-circle mr-2"></i>Order Delivered Successfully
                        </p>
                        <p class="text-gray-500 text-sm">Delivered at {{ $order->updated_at->format('g:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>