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
        /* Chat modal styling */
        .chat-message-left {
            background-color: #e3f2fd;
            border-radius: 18px 18px 18px 4px;
        }

        .chat-message-right {
            background-color: #dcf8c6;
            border-radius: 18px 18px 4px 18px;
        }

        /* Progress bar animation */
        #progress-bar {
            transition: width 1s ease-in-out;
        }

        /* Attachment styles */
        .chat-attachment-image {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .chat-attachment-image:hover {
            transform: scale(1.02);
        }

        .chat-attachment-file {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            text-decoration: none;
            transition: background 0.2s;
        }

        .chat-attachment-file:hover {
            background: rgba(255, 255, 255, 0.3);
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
                    <div id="progress-bar" class="bg-orange-600 h-2.5 rounded-full"
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

                                    <!-- Delivery Information -->
                                    <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                        <div class="flex items-start">
                                            <i class="fas fa-info-circle text-blue-500 mt-1 mr-2"></i>
                                            <div>
                                                <p class="text-sm font-medium text-blue-800">Delivery Information</p>
                                                <p class="text-xs text-blue-600 mt-1">
                                                    Your rider {{ $order->rider->name }} is delivering your order.
                                                    You can track their progress above and contact them through the chat.
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
                </div> <!-- Close left column -->

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

    <!-- Chat Modal -->
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

    <!-- Cancel Order Modal -->
    <div id="cancel-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Cancel Order</h3>
            <p class="text-gray-600 mb-4">Please select a reason for cancellation:</p>

            <form id="cancel-form" action="{{ route('customer.orders.cancel', $order) }}" method="POST">
                @csrf
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
                    progress = 60;
                    if (hasRider) {
                        text = 'Order is ready - Please wait for rider pickup';
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
        document.getElementById('cancel-order-btn')?.addEventListener('click', function () {
            document.getElementById('cancel-modal').classList.remove('hidden');
        });

        document.getElementById('close-cancel-modal')?.addEventListener('click', function () {
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

        // Share trip details
        function shareTripDetails() {
            const tripInfo = `NaNi Delivery - Order #{{ $order->order_number }}
Rider: {{ $order->rider->name }}
Vehicle: {{ $order->rider->vehicle_type }} ({{ $order->rider->vehicle_plate }})
Status: {{ ucfirst(str_replace('_', ' ', $order->status)) }}
Tracking: ${window.location.href}`;

            if (navigator.share) {
                navigator.share({
                    title: 'My NaNi Food Delivery',
                    text: tripInfo,
                    url: window.location.href
                }).catch(error => {
                    console.log('Error sharing:', error);
                    copyToClipboard(tripInfo);
                });
            } else {
                copyToClipboard(tripInfo);
            }
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                showNotification('Trip details copied to clipboard! You can share it with friends/family.', 'success');
            }).catch(err => {
                console.error('Failed to copy:', err);
                showNotification('Failed to copy. Please manually copy the trip details.', 'error');
            });
        }

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

        // Initialize
        document.addEventListener('DOMContentLoaded', function () {
            updateProgressBar();
        });
    </script>

    <!-- Chat functionality (keep existing chat functionality) -->
    <script>
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
                const url = `/customer/orders/${orderId}/messages`;

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
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            function sendMessage() {
                const message = chatInput.value.trim();

                if (!message) {
                    return;
                }

                const url = `/customer/orders/${orderId}/messages`;

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