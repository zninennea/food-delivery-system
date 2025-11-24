<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order #{{ $order->order_number }} - NaNi</title>
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
                        <p class="text-xs text-gray-500 -mt-1">Track Order</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('customer.dashboard') }}" class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <a href="{{ route('customer.orders.index') }}" class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-list mr-1"></i>My Orders
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

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg">
            <!-- Order Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Order #{{ $order->order_number }}</h2>
                        <p class="text-gray-600">Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                            @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status == 'preparing') bg-blue-100 text-blue-800
                            @elseif($order->status == 'ready') bg-green-100 text-green-800
                            @elseif($order->status == 'on_the_way') bg-purple-100 text-purple-800
                            @elseif($order->status == 'delivered') bg-gray-100 text-gray-800
                            @elseif($order->status == 'modification_requested') bg-orange-100 text-orange-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Order Progress</span>
                    <span class="text-sm text-gray-500" id="progress-text">Estimating time...</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div id="progress-bar" class="bg-orange-600 h-2.5 rounded-full transition-all duration-1000" 
                         style="width: {{ $order->getProgressPercentage() }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mt-1">
                    <span>Order Received</span>
                    <span>Preparing</span>
                    <span>Ready</span>
                    <span>On the Way</span>
                    <span>Delivered</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6">
                <!-- Left Column - Order Details & Actions -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Items -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Items</h3>
                        <div class="space-y-3">
                            @foreach($order->items as $item)
                            <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $item->menuItem->name }}</p>
                                    <p class="text-sm text-gray-500">₱{{ number_format($item->unit_price, 2) }} × {{ $item->quantity }}</p>
                                </div>
                                <p class="font-medium text-gray-900">₱{{ number_format($item->total, 2) }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Actions -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Actions</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Modify Order Button -->
                            <button id="modify-order-btn" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed"
                                {{ !$order->canBeModified() ? 'disabled' : '' }}>
                                <i class="fas fa-edit mr-2"></i>Modify Order
                            </button>

                            <!-- Cancel Order Button -->
                            <button id="cancel-order-btn"
                                class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 disabled:bg-gray-400 disabled:cursor-not-allowed"
                                {{ !$order->canBeCancelled() ? 'disabled' : '' }}>
                                <i class="fas fa-times mr-2"></i>Cancel Order
                            </button>

                            <!-- Confirm Delivery / Write Review Button -->
                            @if($order->status == 'delivered')
                                @if($order->review)
                                    <button class="bg-green-600 text-white px-4 py-2 rounded-md" disabled>
                                        <i class="fas fa-check mr-2"></i>Review Submitted
                                    </button>
                                @else
                                    <button id="write-review-btn" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                        <i class="fas fa-star mr-2"></i>Write Review
                                    </button>
                                @endif
                            @elseif($order->status == 'on_the_way')
                                <button id="confirm-delivery-btn" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                    <i class="fas fa-check mr-2"></i>Confirm Delivery
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Rider Information -->
                    @if($order->rider)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Rider Information</h3>
                        <div class="flex items-center space-x-4">
                            @if($order->rider->profile_picture)
                                <img src="{{ asset('storage/' . $order->rider->profile_picture) }}" 
                                     alt="{{ $order->rider->name }}" class="h-12 w-12 rounded-full object-cover">
                            @else
                                <div class="h-12 w-12 bg-gray-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-900">{{ $order->rider->name }}</p>
                                <p class="text-sm text-gray-500">{{ $order->rider->vehicle_type }} • {{ $order->rider->vehicle_plate }}</p>
                                <p class="text-sm text-gray-500">License: {{ $order->rider->drivers_license }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right Column - Order Summary & Chat -->
                <div class="space-y-6">
                    <!-- Order Summary -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium">₱{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Delivery Fee</span>
                                <span class="font-medium">₱{{ number_format($order->delivery_fee, 2) }}</span>
                            </div>
                            <div class="flex justify-between border-t border-gray-200 pt-2">
                                <span class="font-medium text-gray-900">Total</span>
                                <span class="font-bold text-gray-900">₱{{ number_format($order->grand_total, 2) }}</span>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <h4 class="font-medium text-gray-900 mb-2">Payment</h4>
                            <p class="text-sm text-gray-600 capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</p>
                            <p class="text-sm {{ $order->payment_status == 'approved' ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ ucfirst($order->payment_status) }}
                            </p>
                        </div>
                    </div>

                    <!-- Chat with Rider -->
                    @if($order->rider)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Chat with Rider</h3>
                        <div class="space-y-3" id="chat-messages" style="max-height: 200px; overflow-y: auto;">
                            <!-- Chat messages will be loaded here -->
                        </div>
                        <div class="mt-4">
                            <div class="flex space-x-2">
                                <input type="text" id="chat-input" placeholder="Type a message..." 
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <button id="send-chat-btn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modify Order Modal -->
    <div id="modify-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4 max-h-96 overflow-y-auto">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Modify Your Order</h3>
            <p class="text-gray-600 mb-4">You can add more items to your order. The restaurant will review your changes.</p>
            
            <div class="mb-4">
                <a href="{{ route('customer.menu') }}?modify_order={{ $order->id }}" 
                   class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 inline-block">
                   <i class="fas fa-plus mr-2"></i>Browse Menu to Add Items
                </a>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" id="close-modify-modal" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- Cancel Order Modal -->
    <div id="cancel-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Cancel Order</h3>
            <p class="text-gray-600 mb-4">Please select a reason for cancellation:</p>
            
            <form id="cancel-form" action="{{ route('customer.orders.cancel', $order) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-2 mb-4">
                    <div class="flex items-center">
                        <input type="radio" id="reason1" name="cancellation_reason" value="Order is taking too long" class="mr-2">
                        <label for="reason1">Order is taking too long</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="reason2" name="cancellation_reason" value="Placed by mistake" class="mr-2">
                        <label for="reason2">Placed by mistake</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="reason3" name="cancellation_reason" value="Change of plans" class="mr-2">
                        <label for="reason3">Change of plans</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="reason4" name="cancellation_reason" value="Other" class="mr-2">
                        <label for="reason4">Other reason</label>
                    </div>
                </div>
                
                <textarea id="other-reason" name="cancellation_reason_other" placeholder="Please specify..." 
                    class="mt-2 w-full px-3 py-2 border border-gray-300 rounded-md hidden"></textarea>

                <div class="flex justify-end space-x-3 mt-4">
                    <button type="button" id="close-cancel-modal" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Keep Order
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Confirm Cancellation
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Progress bar simulation
        function updateProgressBar() {
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');
            const status = '{{ $order->status }}';
            
            let progress = 0;
            let text = '';
            
            switch(status) {
                case 'pending':
                    progress = 10;
                    text = 'Order received - Preparing will start soon';
                    break;
                case 'preparing':
                    progress = 40;
                    text = 'Food is being prepared - 15-20 minutes';
                    break;
                case 'ready':
                    progress = 70;
                    text = 'Order is ready - Rider is on the way';
                    break;
                case 'on_the_way':
                    progress = 90;
                    text = 'Rider is delivering your order';
                    break;
                case 'delivered':
                    progress = 100;
                    text = 'Order delivered - Enjoy your meal!';
                    break;
                default:
                    progress = 0;
                    text = 'Order processing';
            }
            
            progressBar.style.width = progress + '%';
            progressText.textContent = text;
        }

        // Modal handlers
        document.getElementById('modify-order-btn').addEventListener('click', function() {
            document.getElementById('modify-modal').classList.remove('hidden');
        });

        document.getElementById('cancel-order-btn').addEventListener('click', function() {
            document.getElementById('cancel-modal').classList.remove('hidden');
        });

        document.getElementById('close-modify-modal').addEventListener('click', function() {
            document.getElementById('modify-modal').classList.add('hidden');
        });

        document.getElementById('close-cancel-modal').addEventListener('click', function() {
            document.getElementById('cancel-modal').classList.add('hidden');
        });

        // Other reason textarea
        document.querySelectorAll('input[name="cancellation_reason"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const otherReason = document.getElementById('other-reason');
                otherReason.classList.toggle('hidden', this.value !== 'Other');
                if (this.value !== 'Other') {
                    otherReason.value = '';
                }
            });
        });

        // Auto-refresh order status every 30 seconds
        setInterval(() => {
            // In a real app, you'd fetch the latest order status from the server
            console.log('Checking for order status updates...');
        }, 30000);

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateProgressBar();
            
            // Simulate progress updates for demo
            if ('{{ $order->status }}' === 'preparing') {
                setTimeout(() => {
                    // This would be a real API call in production
                    console.log('Order progress updated');
                }, 15000);
            }
        });
    </script>
</body>
</html>