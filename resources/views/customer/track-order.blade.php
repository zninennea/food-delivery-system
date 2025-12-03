<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Track Order #{{ $order->order_number }} - NaNi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* License modal animations */
        #license-modal {
            animation: fadeIn 0.3s ease-out;
        }

        #license-image {
            transition: transform 0.3s ease;
            cursor: zoom-in;
        }

        #fullscreen-modal {
            animation: fadeIn 0.2s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Custom scrollbar for modal */
        #license-modal .overflow-y-auto {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e0 #f7fafc;
        }

        #license-modal .overflow-y-auto::-webkit-scrollbar {
            width: 8px;
        }

        #license-modal .overflow-y-auto::-webkit-scrollbar-track {
            background: #f7fafc;
            border-radius: 4px;
        }

        #license-modal .overflow-y-auto::-webkit-scrollbar-thumb {
            background-color: #cbd5e0;
            border-radius: 4px;
        }

        /* Safety checklist styling */
        #license-content input[type="checkbox"]:checked+label {
            color: #059669;
            font-weight: 500;
        }

        #license-content input[type="checkbox"]:checked {
            background-color: #059669;
            border-color: #059669;
        }
    </style>
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
                    <a href="{{ route('customer.dashboard') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <a href="{{ route('customer.orders.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
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

    @if($order->rider_id)
        @include('components.chat-popup', ['order' => $order])
    @else
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
            <p class="text-yellow-700">
                <i class="fas fa-info-circle mr-2"></i>
                Chat will be available once a rider is assigned to your order.
            </p>
        </div>
    @endif

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
                                        <p class="text-sm text-gray-500">₱{{ number_format($item->unit_price, 2) }} ×
                                            {{ $item->quantity }}
                                        </p>
                                    </div>
                                    <p class="font-medium text-gray-900">₱{{ number_format($item->total, 2) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Actions -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Actions</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <!-- Cancel Order Button (only if order can be cancelled) -->
                            @if($order->canBeCancelled())
                                <button id="cancel-order-btn"
                                    class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                                    <i class="fas fa-times mr-2"></i>Cancel Order
                                </button>
                            @endif

                            <!-- Review Button (only if order is delivered and not reviewed) -->
                            @if($order->status == 'delivered')
                                @php
                                    $hasReviewed = \App\Models\Review::where('order_id', $order->id)->exists();
                                @endphp

                                @if(!$hasReviewed)
                                    <a href="{{ route('customer.reviews.create', $order) }}"
                                        class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 text-center">
                                        <i class="fas fa-star mr-2"></i>Review This Order
                                    </a>
                                @else
                                    <button class="bg-gray-400 text-white px-4 py-2 rounded-md cursor-not-allowed" disabled>
                                        <i class="fas fa-check mr-2"></i>Already Reviewed
                                    </button>
                                @endif
                            @endif

                            <!-- Modification Button (only if order can be modified) -->
                            @if($order->canBeModified())
                                <form action="{{ route('customer.orders.request-modification', $order) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="w-full bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700">
                                        <i class="fas fa-edit mr-2"></i>Request Modification
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Rider Information - ONLY SHOW WHEN RIDER IS ASSIGNED -->
                    @if($order->rider)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Rider Information</h3>
                            <div class="flex items-center space-x-4">
                                @if($order->rider->profile_picture)
                                    <img src="{{ asset('storage/' . $order->rider->profile_picture) }}"
                                        alt="{{ $order->rider->name }}" class="h-16 w-16 rounded-full object-cover">
                                @else
                                    <div class="h-16 w-16 bg-gray-200 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-gray-400 text-2xl"></i>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-medium text-gray-900 text-lg">{{ $order->rider->name }}</p>
                                            <p class="text-sm text-gray-500">
                                                <i class="fas fa-motorcycle mr-1"></i>
                                                {{ $order->rider->vehicle_type }} • {{ $order->rider->vehicle_plate }}
                                            </p>
                                            <p class="text-sm text-gray-500 mt-1">
                                                <i class="fas fa-phone mr-1"></i>
                                                {{ $order->rider->phone ?? 'No phone provided' }}
                                            </p>
                                            <div class="mt-2">
                                                @if($order->rider->drivers_license)
                                                    <button type="button"
                                                        onclick="viewRiderLicense('{{ asset('storage/' . $order->rider->drivers_license) }}', '{{ $order->rider->name }}')"
                                                        class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm transition-colors">
                                                        <i class="fas fa-id-card mr-2"></i> View Rider ID
                                                    </button>
                                                @else
                                                    <span class="text-sm text-gray-500 italic">
                                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                                        Driver's license not available
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                                                                                            @if($order->rider->status == 'active') bg-green-100 text-green-800
                                                                                                                            @else bg-red-100 text-red-800 @endif">
                                                <i class="fas fa-circle text-xs mr-1"></i>
                                                {{ ucfirst($order->rider->status) }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Safety Information -->
                                    <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                        <div class="flex items-start">
                                            <i class="fas fa-shield-alt text-blue-500 mt-1 mr-2"></i>
                                            <div>
                                                <p class="text-sm font-medium text-blue-800">Safety Information</p>
                                                <p class="text-xs text-blue-600 mt-1">
                                                    For your safety, verify the rider's identity matches the ID shown above.
                                                    Always check the license plate matches
                                                    {{ $order->rider->vehicle_plate }}.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                            <h3 class="text-lg font-medium text-yellow-800 mb-2">Rider Assignment</h3>
                            <p class="text-yellow-700">
                                <i class="fas fa-clock mr-2"></i>
                                Waiting for restaurant to assign a rider to your order.
                            </p>
                            <p class="text-sm text-yellow-600 mt-1">
                                A rider will be assigned shortly and you'll be able to track your delivery.
                            </p>
                        </div>
                    @endif
                </div> <!-- Close left column (lg:col-span-2) -->

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
                                <span
                                    class="font-bold text-gray-900">₱{{ number_format($order->grand_total, 2) }}</span>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <h4 class="font-medium text-gray-900 mb-2">Payment</h4>
                            <p class="text-sm text-gray-600 capitalize">
                                {{ str_replace('_', ' ', $order->payment_method) }}
                            </p>
                            <p
                                class="text-sm {{ $order->payment_status == 'approved' ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ ucfirst($order->payment_status) }}
                            </p>
                        </div>
                    </div>

                    <!-- Chat with Rider - ONLY SHOW WHEN RIDER IS ASSIGNED -->
                    @if($order->rider)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Chat with Rider</h3>
                            <button onclick="openChat({{ $order->id }}, '{{ $order->order_number }}')"
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 w-full">
                                <i class="fas fa-comments mr-2"></i>Chat with Rider {{ $order->rider->name }}
                            </button>
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Chat</h3>
                            <button class="bg-gray-400 text-white px-4 py-2 rounded-md cursor-not-allowed w-full" disabled>
                                <i class="fas fa-comments mr-2"></i>Chat will be available when rider is assigned
                            </button>
                        </div>
                    @endif
                </div> <!-- Close right column -->
            </div> <!-- Close grid -->
        </div> <!-- Close main content -->
    </div> <!-- Close container -->

    <!-- Chat Popup Modal -->
    <div id="chat-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <!-- Modal Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-comments mr-2"></i>Chat with Rider
                </h3>
                <button id="close-chat-modal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Chat Messages -->
            <div class="p-4">
                <div id="chat-messages" class="h-64 overflow-y-auto mb-4 space-y-3">
                    <!-- Messages will be loaded here -->
                </div>

                <!-- Message Input -->
                <div class="flex space-x-2">
                    <input type="text" id="chat-input" placeholder="Type your message..."
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <button id="send-chat-btn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    </div>
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
                        <input type="radio" id="reason1" name="cancellation_reason" value="Order is taking too long"
                            class="mr-2">
                        <label for="reason1">Order is taking too long</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="reason2" name="cancellation_reason" value="Placed by mistake"
                            class="mr-2">
                        <label for="reason2">Placed by mistake</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="reason3" name="cancellation_reason" value="Change of plans"
                            class="mr-2">
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
                    <button type="button" id="close-cancel-modal"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
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
            const hasRider = {{ $order->rider ? 'true' : 'false' }};

            let progress = 0;
            let text = '';

            switch (status) {
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
                    if (hasRider) {
                        text = 'Order is ready - Rider is on the way';
                    } else {
                        text = 'Order is ready - Waiting for rider assignment';
                    }
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
        document.getElementById('cancel-order-btn').addEventListener('click', function () {
            document.getElementById('cancel-modal').classList.remove('hidden');
        });

        document.getElementById('close-cancel-modal').addEventListener('click', function () {
            document.getElementById('cancel-modal').classList.add('hidden');
        });

        // Other reason textarea
        document.querySelectorAll('input[name="cancellation_reason"]').forEach(radio => {
            radio.addEventListener('change', function () {
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
        document.addEventListener('DOMContentLoaded', function () {
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chatModal = document.getElementById('chat-modal');
            const openChatBtn = document.getElementById('open-chat-btn');
            const closeChatBtn = document.getElementById('close-chat-modal');
            const chatMessages = document.getElementById('chat-messages');
            const chatInput = document.getElementById('chat-input');
            const sendChatBtn = document.getElementById('send-chat-btn');
            const orderId = {{ $order->id }};
            const userId = {{ auth()->id() }};

            console.log('Chat system initialized - Order ID:', orderId, 'User ID:', userId);

            let pollingInterval;

            // Open chat modal
            openChatBtn.addEventListener('click', function () {
                console.log('Opening chat modal');
                chatModal.classList.remove('hidden');
                loadMessages();
                startPolling();
            });

            // Close chat modal
            closeChatBtn.addEventListener('click', function () {
                console.log('Closing chat modal');
                chatModal.classList.add('hidden');
                stopPolling();
            });

            // Close modal when clicking outside
            chatModal.addEventListener('click', function (e) {
                if (e.target === chatModal) {
                    console.log('Closing chat modal (outside click)');
                    chatModal.classList.add('hidden');
                    stopPolling();
                }
            });

            // Send message
            sendChatBtn.addEventListener('click', sendMessage);
            chatInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });

            function startPolling() {
                console.log('Starting message polling');
                pollingInterval = setInterval(loadMessages, 3000);
            }

            function stopPolling() {
                console.log('Stopping message polling');
                if (pollingInterval) {
                    clearInterval(pollingInterval);
                }
            }

            // Replace your current loadMessages function with this:
            function loadMessages() {
                const url = window.location.pathname.includes('customer')
                    ? `/customer/orders/${orderId}/messages`
                    : `/rider/orders/${orderId}/messages`;

                console.log('Attempting to load messages from:', url);

                fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (response.status === 500) {
                            throw new Error('Server error - check Laravel logs');
                        }
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);

                        if (data.error) {
                            throw new Error(data.error);
                        }

                        // Check if data is an array (messages) or has messages property
                        const messages = Array.isArray(data) ? data : (data.messages || []);
                        renderMessages(messages);
                    })
                    .catch(error => {
                        console.error('Error loading messages:', error);
                        const chatMessages = document.getElementById('chat-messages');
                        chatMessages.innerHTML = `
            <div class="text-center text-red-500 py-4">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Chat temporarily unavailable: ${error.message}
                <br><small>Check browser console for details</small>
            </div>
        `;
                    });
            }

            function sendMessage() {
                const message = chatInput.value.trim();
                console.log('Sending message:', message);

                if (!message) {
                    console.log('Message is empty, not sending');
                    return;
                }

                const url = window.location.pathname.includes('customer')
                    ? `/customer/orders/${orderId}/messages`
                    : `/rider/orders/${orderId}/messages`;

                console.log('Sending to URL:', url);

                // Show sending state
                sendChatBtn.disabled = true;
                sendChatBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                // Use FormData and explicitly include CSRF token
                const formData = new FormData();
                formData.append('message', message);
                formData.append('_token', '{{ csrf_token() }}'); // Explicitly add CSRF token

                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Also include in headers
                    }
                })
                    .then(response => {
                        console.log('Response status:', response.status);
                        console.log('Response headers:', Object.fromEntries(response.headers.entries()));

                        if (!response.ok) {
                            // Get more details about the error
                            return response.text().then(text => {
                                console.log('Error response body:', text);
                                throw new Error(`HTTP error! status: ${response.status}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Message sent successfully:', data);
                        chatInput.value = '';
                        loadMessages();
                    })
                    .catch(error => {
                        console.error('Error sending message:', error);
                        alert('Error sending message: ' + error.message);
                    })
                    .finally(() => {
                        sendChatBtn.disabled = false;
                        sendChatBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
                    });
            }

            // Close with Escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !chatModal.classList.contains('hidden')) {
                    chatModal.classList.add('hidden');
                    stopPolling();
                }
            });
        });
    </script>
    <script>
        // License Modal Functions
        let currentLicenseUrl = '';
        let zoomLevel = 1;

        function viewRiderLicense(licenseUrl, riderName) {
            console.log('Opening license modal for:', licenseUrl, riderName);

            currentLicenseUrl = licenseUrl;
            const modal = document.getElementById('license-modal');
            const title = document.getElementById('license-rider-name');
            const checkName = document.getElementById('check-rider-name');

            // Set rider name
            title.textContent = riderName;
            checkName.textContent = riderName;

            // Show modal
            modal.classList.remove('hidden');

            // Load license image
            loadLicenseImage(licenseUrl);

            // Reset zoom
            zoomLevel = 1;
            updateImageZoom();

            // Reset checkboxes
            document.querySelectorAll('#license-content input[type="checkbox"]').forEach(cb => {
                cb.checked = false;
                cb.disabled = false;
            });

            // Show/hide verify button based on checkboxes
            updateVerifyButton();
        }

        function loadLicenseImage(url) {
            const loading = document.getElementById('license-loading');
            const error = document.getElementById('license-error');
            const content = document.getElementById('license-content');
            const image = document.getElementById('license-image');
            const fullscreenImage = document.getElementById('fullscreen-image');

            // Show loading, hide others
            loading.classList.remove('hidden');
            error.classList.add('hidden');
            content.classList.add('hidden');

            // Preload image
            const img = new Image();
            img.onload = function () {
                console.log('License image loaded successfully');
                image.src = url;
                fullscreenImage.src = url;
                loading.classList.add('hidden');
                content.classList.remove('hidden');

                // Enable zoom controls
                document.getElementById('zoom-in-btn').disabled = false;
                document.getElementById('zoom-out-btn').disabled = false;
                document.getElementById('fullscreen-btn').disabled = false;
            };

            img.onerror = function () {
                console.error('Failed to load license image:', url);
                loading.classList.add('hidden');
                error.classList.remove('hidden');
                document.getElementById('license-error-message').textContent =
                    'Unable to load the driver\'s license. Please try again or contact support.';
            };

            img.src = url;
        }

        function retryLoadLicense() {
            if (currentLicenseUrl) {
                loadLicenseImage(currentLicenseUrl);
            }
        }

        function updateImageZoom() {
            const image = document.getElementById('license-image');
            image.style.transform = `scale(${zoomLevel})`;
            image.style.transformOrigin = 'center center';

            // Update button states
            document.getElementById('zoom-out-btn').disabled = zoomLevel <= 0.5;
            document.getElementById('zoom-in-btn').disabled = zoomLevel >= 3;
        }

        function updateVerifyButton() {
            // Check if already verified
            if (window.orderSafetyVerified) {
                console.log('Safety already verified, hiding checkboxes');
                document.querySelectorAll('#license-content input[type="checkbox"]').forEach(cb => {
                    cb.disabled = true;
                    cb.checked = true;
                });
                return;
            }

            const checkboxes = document.querySelectorAll('#license-content input[type="checkbox"]');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            const verifyBtn = document.getElementById('verify-btn');

            if (allChecked) {
                verifyBtn.classList.remove('hidden');
                verifyBtn.disabled = false;
            } else {
                verifyBtn.classList.add('hidden');
            }
        }

        function verifySafety() {
            if (!confirm('Are you sure you have verified all safety checks and the rider is legitimate?')) {
                return;
            }

            const verifyBtn = document.getElementById('verify-btn');
            verifyBtn.disabled = true;
            verifyBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Verifying...';

            // Send verification to server
            fetch(`/customer/orders/${window.currentOrderId}/verify-safety`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    verified: true
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Safety verification completed! Thank you for helping keep our community safe.');
                        window.orderSafetyVerified = true;

                        // Disable all checkboxes
                        document.querySelectorAll('#license-content input[type="checkbox"]').forEach(cb => {
                            cb.disabled = true;
                            cb.checked = true;
                        });

                        // Hide verify button
                        verifyBtn.classList.add('hidden');

                        // Show success message
                        const safetyDiv = document.getElementById('safety-checklist');
                        safetyDiv.innerHTML += `
                <div class="mt-4 p-3 bg-green-100 rounded border border-green-200">
                    <p class="text-sm text-green-800 flex items-start">
                        <i class="fas fa-check-circle mr-2 mt-0.5"></i>
                        <span>Safety verification completed successfully!</span>
                    </p>
                </div>
            `;

                        // Close modal after 2 seconds
                        setTimeout(() => {
                            document.getElementById('license-modal').classList.add('hidden');
                        }, 2000);
                    } else {
                        alert('Verification failed: ' + (data.message || 'Please try again'));
                        verifyBtn.disabled = false;
                        verifyBtn.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Verified & Safe';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Network error. Please check your connection and try again.');
                    verifyBtn.disabled = false;
                    verifyBtn.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Verified & Safe';
                });
        }

        // Update the viewRiderLicense function to set currentOrderId
        function viewRiderLicense(licenseUrl, riderName) {
            console.log('Opening license modal for:', licenseUrl, riderName);

            window.currentOrderId = {{ $order->id }};
            window.orderSafetyVerified = {{ $order->safety_verified ? 'true' : 'false' }};

            currentLicenseUrl = licenseUrl;
            const modal = document.getElementById('license-modal');
            const title = document.getElementById('license-rider-name');
            const checkName = document.getElementById('check-rider-name');

            // Set rider name
            title.textContent = riderName;
            checkName.textContent = riderName;

            // Show modal
            modal.classList.remove('hidden');

            // Load license image
            loadLicenseImage(licenseUrl);

            // Reset zoom
            zoomLevel = 1;
            updateImageZoom();

            // If already verified, disable checkboxes
            if (window.orderSafetyVerified) {
                document.querySelectorAll('#license-content input[type="checkbox"]').forEach(cb => {
                    cb.checked = true;
                    cb.disabled = true;
                });
                document.getElementById('verify-btn').classList.add('hidden');
            } else {
                // Reset checkboxes
                document.querySelectorAll('#license-content input[type="checkbox"]').forEach(cb => {
                    cb.checked = false;
                    cb.disabled = false;
                });
                updateVerifyButton();
            }
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function () {
            // License modal close buttons
            document.getElementById('close-license-modal').addEventListener('click', function () {
                document.getElementById('license-modal').classList.add('hidden');
            });

            document.getElementById('close-license-btn').addEventListener('click', function () {
                document.getElementById('license-modal').classList.add('hidden');
            });

            // Close modal when clicking outside
            document.getElementById('license-modal').addEventListener('click', function (e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });

            // Download license button
            document.getElementById('download-license-btn').addEventListener('click', function () {
                if (currentLicenseUrl) {
                    const link = document.createElement('a');
                    link.href = currentLicenseUrl;
                    link.download = 'rider-license.jpg';
                    link.target = '_blank';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            });

            // Zoom controls
            document.getElementById('zoom-in-btn').addEventListener('click', function () {
                if (zoomLevel < 3) {
                    zoomLevel += 0.25;
                    updateImageZoom();
                }
            });

            document.getElementById('zoom-out-btn').addEventListener('click', function () {
                if (zoomLevel > 0.5) {
                    zoomLevel -= 0.25;
                    updateImageZoom();
                }
            });

            // Fullscreen
            document.getElementById('fullscreen-btn').addEventListener('click', function () {
                const fullscreenModal = document.getElementById('fullscreen-modal');
                fullscreenModal.classList.remove('hidden');
            });

            // Close fullscreen
            document.getElementById('close-fullscreen').addEventListener('click', function () {
                document.getElementById('fullscreen-modal').classList.add('hidden');
            });

            // Close fullscreen on ESC
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    document.getElementById('fullscreen-modal').classList.add('hidden');
                }
            });

            // Checkbox change listeners
            document.querySelectorAll('#license-content input[type="checkbox"]').forEach(checkbox => {
                checkbox.addEventListener('change', updateVerifyButton);
            });

            // Verify button
            document.getElementById('verify-btn').addEventListener('click', verifySafety);
        });

        // Also add this to handle Escape key for license modal
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const licenseModal = document.getElementById('license-modal');
                if (!licenseModal.classList.contains('hidden')) {
                    licenseModal.classList.add('hidden');
                }
            }
        });
        // Add this to your existing JavaScript section
        document.addEventListener('DOMContentLoaded', function () {
            // Review button handler
            const reviewBtn = document.getElementById('review-order-btn');
            if (reviewBtn) {
                reviewBtn.addEventListener('click', function () {
                    window.location.href = '/customer/reviews/{{ $order->id }}/create';
                });
            }

            // Only show cancel modal if order can be cancelled
            const cancelBtn = document.getElementById('cancel-order-btn');
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function () {
                    if (this.disabled) return;

                    // Check if order is still cancellable
                    fetch('/api/orders/{{ $order->id }}/can-cancel')
                        .then(response => response.json())
                        .then(data => {
                            if (data.can_cancel) {
                                document.getElementById('cancel-modal').classList.remove('hidden');
                            } else {
                                alert('This order can no longer be cancelled as preparation has started.');
                            }
                        })
                        .catch(error => {
                            console.error('Error checking cancellation:', error);
                            document.getElementById('cancel-modal').classList.remove('hidden');
                        });
                });
            }
        });
        // Make sure this function exists globally
        window.openChat = function (orderId, orderNumber) {
            console.log('Opening chat for order:', orderId, orderNumber);
            // This function should be defined in your chat-popup.blade.php
            if (window.chatPopup && window.chatPopup.open) {
                window.chatPopup.open(orderId, orderNumber);
            } else {
                console.error('Chat system not initialized');
                alert('Chat system is not available. Please refresh the page.');
            }
        };
    </script>

    <!-- Rider License Modal -->
    <div id="license-modal"
        class="fixed inset-0 bg-black bg-opacity-75 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
            <!-- Modal Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b bg-blue-600 text-white">
                <div>
                    <h3 class="text-lg font-medium" id="license-modal-title">
                        <i class="fas fa-id-card mr-2"></i>Rider Identification
                    </h3>
                    <p class="text-sm opacity-75" id="license-rider-name"></p>
                </div>
                <div class="flex items-center space-x-3">
                    <button id="download-license-btn" class="text-white hover:text-blue-200">
                        <i class="fas fa-download text-xl"></i>
                    </button>
                    <button id="close-license-modal" class="text-white hover:text-gray-200">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
            </div>

            <!-- License Content -->
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-140px)]">
                <!-- Loading State -->
                <div id="license-loading" class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-4xl text-blue-600 mb-4"></i>
                    <p class="text-gray-600">Loading rider identification...</p>
                </div>

                <!-- Error State -->
                <div id="license-error" class="text-center py-8 hidden">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-600 mb-4"></i>
                    <p class="text-gray-900 font-medium">Unable to load identification</p>
                    <p class="text-gray-600 mt-2" id="license-error-message"></p>
                    <button onclick="retryLoadLicense()"
                        class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        <i class="fas fa-redo mr-2"></i>Retry
                    </button>
                </div>

                <!-- Success State -->
                <div id="license-content" class="hidden">
                    <!-- License Image -->
                    <div class="mb-6">
                        <div class="flex justify-center mb-4">
                            <div class="relative">
                                <img id="license-image" src="" alt="Rider's Driver License"
                                    class="max-w-full h-auto rounded-lg shadow-md border border-gray-300 max-h-[500px]">
                                <div
                                    class="absolute top-2 right-2 bg-black bg-opacity-50 text-white px-2 py-1 rounded text-xs">
                                    <i class="fas fa-camera mr-1"></i>Official ID
                                </div>
                            </div>
                        </div>

                        <!-- Image Controls -->
                        <div class="flex justify-center space-x-4 mt-4">
                            <button id="zoom-in-btn"
                                class="bg-gray-200 text-gray-700 px-3 py-2 rounded-md hover:bg-gray-300">
                                <i class="fas fa-search-plus"></i> Zoom In
                            </button>
                            <button id="zoom-out-btn"
                                class="bg-gray-200 text-gray-700 px-3 py-2 rounded-md hover:bg-gray-300">
                                <i class="fas fa-search-minus"></i> Zoom Out
                            </button>
                            <button id="fullscreen-btn"
                                class="bg-gray-200 text-gray-700 px-3 py-2 rounded-md hover:bg-gray-300">
                                <i class="fas fa-expand"></i> Fullscreen
                            </button>
                        </div>
                    </div>

                    <!-- Safety Verification Checklist -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200" id="safety-checklist">
                        <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-shield-alt text-green-600 mr-2"></i>
                            Safety Verification Checklist
                        </h4>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" id="check-face" class="mr-3 h-4 w-4 text-blue-600" {{ $order->safety_verified ? 'checked disabled' : '' }}>
                                <label for="check-face" class="text-sm text-gray-700">
                                    Rider's face matches ID photo
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="check-name" class="mr-3 h-4 w-4 text-blue-600" {{ $order->safety_verified ? 'checked disabled' : '' }}>
                                <label for="check-name" class="text-sm text-gray-700">
                                    Rider's name matches ID: <span id="check-rider-name" class="font-medium"></span>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="check-vehicle" class="mr-3 h-4 w-4 text-blue-600" {{ $order->safety_verified ? 'checked disabled' : '' }}>
                                <label for="check-vehicle" class="text-sm text-gray-700">
                                    Vehicle matches: <span
                                        class="font-medium">{{ $order->rider->vehicle_license ?? 'N/A' }}</span>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="check-expiry" class="mr-3 h-4 w-4 text-blue-600" {{ $order->safety_verified ? 'checked disabled' : '' }}>
                                <label for="check-expiry" class="text-sm text-gray-700">
                                    ID is not expired (check expiry date)
                                </label>
                            </div>
                        </div>

                        @if(!$order->safety_verified)
                            <div class="mt-4 p-3 bg-blue-100 rounded border border-blue-200">
                                <p class="text-sm text-blue-800 flex items-start">
                                    <i class="fas fa-info-circle mr-2 mt-0.5"></i>
                                    <span>For your safety, verify all items before accepting delivery.
                                        <strong class="block mt-1">If anything seems suspicious, contact restaurant support
                                            immediately:</strong>
                                        <div class="mt-2">
                                            <i class="fas fa-phone mr-1"></i>
                                            {{ $order->restaurant->phone ?? '09194445566' }}
                                            <br>
                                            <i class="fas fa-store mr-1 mt-1"></i>
                                            {{ $order->restaurant->name ?? 'NaNi Japanese Restaurant' }}
                                        </div>
                                    </span>
                                </p>
                            </div>
                        @else
                            <div class="mt-4 p-3 bg-green-100 rounded border border-green-200">
                                <p class="text-sm text-green-800 flex items-start">
                                    <i class="fas fa-check-circle mr-2 mt-0.5"></i>
                                    <span>Safety verification completed on
                                        {{ $order->safety_verified_at->format('M j, Y g:i A') }}</span>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t bg-gray-50">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            For verification purposes only. Do not share this ID.
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <button id="verify-btn"
                            class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 hidden">
                            <i class="fas fa-check-circle mr-2"></i>Verified & Safe
                        </button>
                        <button id="close-license-btn"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fullscreen Image Modal -->
    <div id="fullscreen-modal" class="fixed inset-0 bg-black hidden flex items-center justify-center z-[60]">
        <div class="relative w-full h-full flex items-center justify-center">
            <button id="close-fullscreen" class="absolute top-4 right-4 text-white text-3xl z-10">
                <i class="fas fa-times"></i>
            </button>
            <img id="fullscreen-image" src="" class="max-w-full max-h-full object-contain">
        </div>
    </div>
</body>

</html>