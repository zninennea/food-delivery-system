<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Rider - Order #{{ $order->order_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-800">Assign Rider to Order #{{ $order->order_number }}</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('owner.orders.show', $order) }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-arrow-left mr-1"></i>Back to Order
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="max-w-2xl mx-auto mt-6 px-4">
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
        <div class="max-w-2xl mx-auto mt-6 px-4">
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

    @if($errors->any())
        <div class="max-w-2xl mx-auto mt-6 px-4">
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
                        <strong class="font-bold">Please fix the following errors: </strong>
                        <ul class="mt-1 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-2xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Assign Rider</h2>
                <p class="text-gray-600 mt-1">Order #{{ $order->order_number }} - {{ $order->customer->name }}</p>
            </div>

            <form action="{{ route('owner.orders.assign-rider', $order) }}" method="POST" class="p-6">
                @csrf

                <div class="mb-6">
                    <label for="rider_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Select Rider
                    </label>
                    <select name="rider_id" id="rider_id" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <option value="">-- Select a Rider --</option>
                        @foreach($availableRiders as $rider)
                            <option value="{{ $rider->id }}" {{ $order->rider_id == $rider->id ? 'selected' : '' }}>
                                {{ $rider->name }}
                                ({{ $rider->vehicle_type }} - {{ $rider->vehicle_plate }})
                                - {{ $rider->status }}
                            </option>
                        @endforeach
                    </select>
                    @error('rider_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Order Summary -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Order Summary</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Customer:</span>
                            <span class="font-medium">{{ $order->customer->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Delivery Address:</span>
                            <span class="font-medium text-right">{{ $order->delivery_address }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Amount:</span>
                            <span class="font-medium">₱{{ number_format($order->grand_total, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Current Status:</span>
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'preparing') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('owner.orders.show', $order) }}"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
                        <i class="fas fa-motorcycle mr-2"></i>Assign Rider
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>