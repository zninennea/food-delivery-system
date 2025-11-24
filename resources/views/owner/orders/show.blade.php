<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #{{ $order->order_number }} - NaNi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-800">NaNi - Order Details</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('owner.orders.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-arrow-left mr-1"></i>Back to Orders
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="max-w-4xl mx-auto mt-6 px-4">
            <div class="bg-orange-100 border border-orange-400 text-orange-700 px-4 py-3 rounded relative" role="alert">
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
        <div class="max-w-4xl mx-auto mt-6 px-4">
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
                            @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status == 'preparing') bg-blue-100 text-blue-800
                            @elseif($order->status == 'ready') bg-green-100 text-green-800
                            @elseif($order->status == 'on_the_way') bg-purple-100 text-purple-800
                            @elseif($order->status == 'delivered') bg-gray-100 text-gray-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Customer Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Name</p>
                        <p class="font-medium">{{ $order->customer->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Phone</p>
                        <p class="font-medium">{{ $order->customer_phone }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-600">Delivery Address</p>
                        <p class="font-medium">{{ $order->delivery_address }}</p>
                    </div>
                    @if($order->special_instructions)
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-600">Special Instructions</p>
                            <p class="font-medium text-orange-600">{{ $order->special_instructions }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Order Items</h3>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <p class="font-medium text-gray-900">{{ $item->menuItem->name }}</p>
                                    <p class="text-sm text-gray-500">₱{{ number_format($item->unit_price, 2) }} ×
                                        {{ $item->quantity }}
                                    </p>
                                </div>
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
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <form action="{{ route('owner.orders.update-status', $order) }}" method="POST"
                        class="flex items-center space-x-4">
                        @csrf
                        @method('PUT')
                        <select name="status"
                            class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing
                            </option>
                            <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                            <option value="on_the_way" {{ $order->status == 'on_the_way' ? 'selected' : '' }}>On the Way
                            </option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered
                            </option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>

                        </select>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Update Status
                        </button>
                    </form>

                    @if(in_array($order->status, ['pending', 'cancelled']))
                        <form action="{{ route('owner.orders.destroy', $order) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                                onclick="return confirm('Delete order #{{ $order->order_number }}?')">
                                <i class="fas fa-trash mr-2"></i>Delete Order
                            </button>
                        </form>
                    @endif
                </div>

                <div class="text-right">
                    @if($order->rider)
                        <p class="text-sm text-gray-600">Assigned Rider</p>
                        <p class="font-medium">{{ $order->rider->name }}</p>
                    @endif

                    <a href="{{ route('owner.orders.assign-rider-form', $order) }}"
                        class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 inline-flex items-center mt-2">
                        <i class="fas fa-motorcycle mr-2"></i>
                        {{ $order->rider ? 'Change Rider' : 'Assign Rider' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>