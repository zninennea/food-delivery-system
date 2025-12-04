<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Order #{{ $order->order_number }} - NaNi Rider</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Chat modal styling */
        .chat-message-left {
            background-color: #e3f2fd;
            border-radius: 18px 18px 18px 4px;
        }

        .chat-message-right {
            background-color: #dcf8c6;
            border-radius: 18px 18px 4px 18px;
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
                        <p class="text-xs text-gray-500 -mt-1">Order Details</p>
                    </div>
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

    @if($order->customer_id)
        @include('components.chat-popup', ['order' => $order])
    @endif

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="max-w-4xl mx-auto mt-6 px-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
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

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <!-- Order Header -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-orange-600">Order #{{ $order->order_number }}</h2>
                        <p class="text-gray-600">Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                            @if($order->status == 'preparing') bg-yellow-100 text-yellow-800
                            @elseif($order->status == 'ready') bg-blue-100 text-blue-800
                            @elseif($order->status == 'on_the_way') bg-green-100 text-green-800
                            @elseif($order->status == 'delivered') bg-gray-100 text-gray-800
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
                                <p class="text-sm text-gray-500">₱{{ number_format($item->unit_price, 2) }} ×
                                    {{ $item->quantity }}
                                </p>
                            </div>
                            <p class="font-medium text-gray-900">₱{{ number_format($item->total, 2) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary -->
            <div class="px-6 py-4 border-b border-gray-200">
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
            </div>

            <!-- Payment Information -->
            <div class="bg-gray-50 rounded-lg p-4 mt-4">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Information</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Payment Method:</span>
                        <span class="font-medium capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                    </div>

                    @if($order->payment_method === 'cash_on_delivery' && $order->cash_provided)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Cash Provided:</span>
                            <span class="font-medium text-green-600">₱{{ number_format($order->cash_provided, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Change Due:</span>
                            <span
                                class="font-medium text-blue-600">₱{{ number_format($order->cash_provided - $order->grand_total, 2) }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between border-t border-gray-200 pt-2">
                        <span class="font-medium text-gray-900">Order Total:</span>
                        <span class="font-bold text-gray-900">₱{{ number_format($order->grand_total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex justify-center space-x-4">
                    @if($order->status == 'ready')
                        <form action="{{ route('rider.orders.update-status', $order) }}" method="POST">
                            @csrf
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
                            <input type="hidden" name="status" value="delivered">
                            <button type="submit"
                                class="bg-green-600 text-white px-6 py-3 rounded-md hover:bg-green-700 font-medium"
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

        <!-- Chat with Customer Button -->
        @if($order->customer)
            <div class="bg-gray-50 rounded-lg p-4 mt-6">
                <button onclick="openChat({{ $order->id }}, '{{ $order->order_number }}')"
                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 font-medium">
                    <i class="fas fa-comments mr-2"></i>Chat with {{ $order->customer->name }}
                </button>
            </div>
        @else
            <div class="bg-yellow-50 rounded-lg p-4 mt-6 border border-yellow-200">
                <h3 class="text-lg font-medium text-yellow-800 mb-2">Chat Not Available</h3>
                <p class="text-yellow-700">
                    <i class="fas fa-info-circle mr-2"></i>
                    Customer information is not available for this order.
                </p>
            </div>
        @endif
    </div>

    <!-- Chat Modal -->
    <div id="chat-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <!-- Modal Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-comments mr-2"></i>Chat with Customer
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

    <script>
        // Chat functionality
        document.addEventListener('DOMContentLoaded', function () {
            const chatModal = document.getElementById('chat-modal');
            const closeChatBtn = document.getElementById('close-chat-modal');
            const chatMessages = document.getElementById('chat-messages');
            const chatInput = document.getElementById('chat-input');
            const sendChatBtn = document.getElementById('send-chat-btn');
            const orderId = {{ $order->id }};
            const userId = {{ auth()->id() }};

            let pollingInterval;

            // Open chat modal
            window.openChat = function (orderId, orderNumber) {
                console.log('Opening chat modal for order:', orderId);
                chatModal.classList.remove('hidden');
                loadMessages();
                startPolling();
            };

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

            function loadMessages() {
                const url = `/rider/orders/${orderId}/messages`;

                fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        const messages = Array.isArray(data) ? data : (data.messages || []);
                        renderMessages(messages);
                    })
                    .catch(error => {
                        console.error('Error loading messages:', error);
                        chatMessages.innerHTML = `
                            <div class="text-center text-red-500 py-4">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Chat temporarily unavailable
                            </div>
                        `;
                    });
            }

            function renderMessages(messages) {
                chatMessages.innerHTML = '';

                if (messages.length === 0) {
                    const noMessages = document.createElement('div');
                    noMessages.className = 'text-center text-gray-500 py-4';
                    noMessages.textContent = 'No messages yet. Start the conversation!';
                    chatMessages.appendChild(noMessages);
                } else {
                    messages.forEach(msg => {
                        const messageDiv = document.createElement('div');
                        messageDiv.className = msg.is_own_message ? 'chat-message-right p-3 ml-12' : 'chat-message-left p-3 mr-12';
                        messageDiv.innerHTML = `
                            <p class="text-sm font-medium">${msg.sender_name}</p>
                            <p class="mt-1">${msg.message}</p>
                            <p class="text-xs text-gray-500 mt-1 text-right">${msg.created_at}</p>
                        `;
                        chatMessages.appendChild(messageDiv);
                    });
                }

                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            function sendMessage() {
                const message = chatInput.value.trim();

                if (!message) {
                    return;
                }

                const url = `/rider/orders/${orderId}/messages`;

                sendChatBtn.disabled = true;
                sendChatBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                const formData = new FormData();
                formData.append('message', message);
                formData.append('_token', '{{ csrf_token() }}');

                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        chatInput.value = '';
                        loadMessages();
                    })
                    .catch(error => {
                        console.error('Error sending message:', error);
                        showNotification('Error sending message. Please try again.', 'error');
                    })
                    .finally(() => {
                        sendChatBtn.disabled = false;
                        sendChatBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
                    });
            }

            // Notification function
            function showNotification(message, type = 'info') {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-md ${type === 'success' ? 'bg-green-100 border border-green-300 text-green-800' : 'bg-red-100 border border-red-300 text-red-800'}`;

                notification.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} mr-3"></i>
                        <div class="flex-1">
                            <p class="text-sm">${message}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;

                document.body.appendChild(notification);
                setTimeout(() => notification.remove(), 5000);
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
</body>

</html>