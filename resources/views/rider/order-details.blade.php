<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Order #{{ $order->order_number }} - NaNi Rider</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5 {
            font-family: 'Playfair Display', serif;
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0;
            transform: translateY(10px);
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Chat Bubbles */
        .chat-message-left {
            background-color: #f3f4f6;
            border-radius: 18px 18px 18px 4px;
            color: #1f2937;
        }

        .chat-message-right {
            background-color: #3b82f6;
            border-radius: 18px 18px 4px 18px;
            color: white;
        }

        .info-card {
            background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%);
            border: 1px solid rgba(229, 231, 235, 0.5);
        }
    </style>
</head>

<body class="bg-stone-50 text-gray-800 antialiased">
    <!-- Navigation -->
    <nav
        class="fixed w-full top-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-2 group">
                    <img src="https://i.imgur.com/vPOu1H2.png" alt="NaNi Icon"
                        class="h-20 w-auto group-hover:rotate-12 transition-transform duration-300">
                    <div class="ml-2">
                        <a href="/" class="text-xl font-bold text-gray-800 font-serif">NaNi</a>
                        <p class="text-xs text-gray-500 -mt-1">Order Details</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('rider.dashboard') }}"
                        class="text-gray-600 hover:text-orange-600 hover:bg-gray-50 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="max-w-4xl mx-auto mt-28 px-4 fade-in">
            <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-2xl shadow-sm flex items-center gap-4"
                role="alert">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div>
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    <div class="pt-32 pb-16 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Order Header -->
        <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 mb-8 fade-in">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-3xl font-bold text-gray-900 font-serif">Order #{{ $order->order_number }}</h1>
                        <span class="px-3 py-1 text-sm font-bold rounded-full uppercase tracking-wider
                            @if($order->status == 'preparing') bg-yellow-100 text-yellow-800
                            @elseif($order->status == 'ready') bg-blue-100 text-blue-800
                            @elseif($order->status == 'on_the_way') bg-green-100 text-green-800
                            @elseif($order->status == 'delivered') bg-gray-100 text-gray-800
                            @else bg-orange-100 text-orange-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </div>
                    <p class="text-stone-500 mt-2 flex items-center gap-2">
                        <i class="far fa-clock"></i> Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 fade-in" style="animation-delay: 0.1s;">
            <!-- Left Column: Customer & Restaurant Info -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Customer Information -->
                <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden">
                    <div class="p-6 border-b border-stone-100 bg-stone-50/50">
                        <h3 class="text-xl font-bold text-gray-900 font-serif">Customer Information</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500">
                                <i class="fas fa-user text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-500 mb-1">Name</p>
                                <p class="font-bold text-gray-900 text-lg">{{ $order->customer->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-500">
                                <i class="fas fa-phone text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-500 mb-1">Phone</p>
                                <p class="font-bold text-gray-900 text-lg">{{ $order->customer_phone }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-500">
                                <i class="fas fa-map-marker-alt text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-500 mb-1">Delivery Address</p>
                                <p class="font-bold text-gray-900">{{ $order->delivery_address }}</p>
                            </div>
                        </div>

                        @if($order->special_instructions)
                            <div class="flex items-start gap-4 mt-4">
                                <div
                                    class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center text-orange-500">
                                    <i class="fas fa-sticky-note text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 mb-1">Special Instructions</p>
                                    <p class="font-medium text-orange-600 bg-orange-50 p-3 rounded-xl">
                                        {{ $order->special_instructions }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Restaurant Information -->
                <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden">
                    <div class="p-6 border-b border-stone-100 bg-stone-50/50">
                        <h3 class="text-xl font-bold text-gray-900 font-serif">Restaurant Information</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-500">
                                <i class="fas fa-store text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-500 mb-1">Name</p>
                                <p class="font-bold text-gray-900 text-lg">{{ $order->restaurant->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-500">
                                <i class="fas fa-map-marker-alt text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-500 mb-1">Address</p>
                                <p class="font-bold text-gray-900">{{ $order->restaurant->address }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-teal-50 rounded-xl flex items-center justify-center text-teal-500">
                                <i class="fas fa-phone text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-500 mb-1">Phone</p>
                                <p class="font-bold text-gray-900 text-lg">{{ $order->restaurant->phone }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden">
                    <div class="p-6 border-b border-stone-100 bg-stone-50/50">
                        <h3 class="text-xl font-bold text-gray-900 font-serif">Order Items</h3>
                    </div>
                    <div class="p-6 divide-y divide-stone-100">
                        @foreach($order->items as $item)
                            <div class="py-4 first:pt-0 last:pb-0 flex justify-between items-center">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center text-orange-500 font-bold">
                                        {{ $item->quantity }}x
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $item->menuItem->name }}</p>
                                        <p class="text-sm text-stone-500">₱{{ number_format($item->unit_price, 2) }} each
                                        </p>
                                    </div>
                                </div>
                                <p class="font-bold text-gray-900">₱{{ number_format($item->total, 2) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column: Summary & Actions -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Order Summary -->
                <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden">
                    <div class="p-6 border-b border-stone-100 bg-stone-50/50">
                        <h3 class="text-xl font-bold text-gray-900 font-serif">Order Summary</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium">₱{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-t border-stone-100">
                            <span class="text-gray-600">Delivery Fee</span>
                            <span class="font-medium">₱{{ number_format($order->delivery_fee, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-4 border-t border-stone-200 mt-2">
                            <span class="text-lg font-bold text-gray-900">Total</span>
                            <span
                                class="text-2xl font-bold text-orange-600">₱{{ number_format($order->grand_total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden">
                    <div class="p-6 border-b border-stone-100 bg-stone-50/50">
                        <h3 class="text-xl font-bold text-gray-900 font-serif">Payment Information</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <span class="text-gray-600">Payment Method:</span>
                            <span
                                class="font-bold capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                        </div>

                        @if($order->payment_method === 'cash_on_delivery' && $order->cash_provided)
                            <div class="space-y-3">
                                <div
                                    class="flex justify-between items-center p-3 bg-green-50 rounded-xl border border-green-100">
                                    <span class="text-gray-600">Cash Provided:</span>
                                    <span
                                        class="font-bold text-green-600">₱{{ number_format($order->cash_provided, 2) }}</span>
                                </div>
                                <div
                                    class="flex justify-between items-center p-3 bg-blue-50 rounded-xl border border-blue-100">
                                    <span class="text-gray-600">Change Due:</span>
                                    <span
                                        class="font-bold text-blue-600">₱{{ number_format($order->cash_provided - $order->grand_total, 2) }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden">
                    <div class="p-6 border-b border-stone-100 bg-stone-50/50">
                        <h3 class="text-xl font-bold text-gray-900 font-serif">Actions</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($order->status == 'ready')
                            <form action="{{ route('rider.orders.update-status', $order) }}" method="POST" class="w-full"
                                id="start-delivery-form">
                                @csrf
                                <input type="hidden" name="status" value="on_the_way">
                                <button type="button"
                                    class="start-delivery-btn w-full bg-gradient-to-r from-green-600 to-green-700 text-white py-4 rounded-xl font-bold shadow-lg shadow-green-500/30 hover:-translate-y-1 transition-all flex justify-center items-center gap-3">
                                    <i class="fas fa-play text-lg"></i>
                                    Start Delivery
                                </button>
                            </form>
                        @endif

                        @if($order->status == 'on_the_way')
                            <form action="{{ route('rider.orders.update-status', $order) }}" method="POST" class="w-full"
                                id="mark-delivered-form">
                                @csrf
                                <input type="hidden" name="status" value="delivered">
                                <button type="button"
                                    class="mark-delivered-btn w-full bg-gradient-to-r from-green-600 to-green-700 text-white py-4 rounded-xl font-bold shadow-lg shadow-green-500/30 hover:-translate-y-1 transition-all flex justify-center items-center gap-3">
                                    <i class="fas fa-check text-lg"></i>
                                    Mark as Delivered
                                </button>
                            </form>
                        @endif

                        @if($order->status == 'delivered')
                            <div class="text-center p-4 bg-green-50 rounded-xl border border-green-100">
                                <div
                                    class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                                </div>
                                <p class="text-green-700 font-bold mb-1">Order Delivered Successfully</p>
                                <p class="text-gray-500 text-sm">Delivered at {{ $order->updated_at->format('g:i A') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Chat with Customer -->
                @if($order->customer)
                    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden">
                        <div class="p-6 border-b border-stone-100 bg-stone-50/50">
                            <h3 class="text-xl font-bold text-gray-900 font-serif">Chat</h3>
                        </div>
                        <div class="p-6">
                            <button onclick="openChat({{ $order->id }}, '{{ $order->order_number }}')"
                                class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-4 rounded-xl font-bold shadow-lg shadow-blue-500/30 hover:-translate-y-1 transition-all flex justify-center items-center gap-3">
                                <i class="fas fa-comment-dots text-lg"></i>
                                Chat with Customer
                            </button>
                        </div>
                    </div>
                @else
                    <div class="bg-yellow-50 rounded-3xl border border-yellow-200 p-6 text-center">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-info-circle text-yellow-500 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-yellow-800 mb-2">Chat Not Available</h3>
                        <p class="text-yellow-700 text-sm">Customer information is not available for this order.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Chat Modal -->
    <div id="chat-modal"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col h-[500px]">
            <div class="bg-stone-900 text-white p-4 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm">Customer Chat</h3>
                        <p class="text-xs text-stone-400">Order #{{ $order->order_number }}</p>
                    </div>
                </div>
                <button id="close-chat-modal" class="text-stone-400 hover:text-white transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4 bg-stone-50">
            </div>

            <div class="p-4 bg-white border-t border-stone-100">
                <div class="flex gap-2">
                    <input type="text" id="chat-input" placeholder="Type a message..."
                        class="flex-1 bg-stone-100 border-none rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 text-sm">
                    <button id="send-chat-btn"
                        class="bg-blue-600 text-white w-12 h-12 rounded-xl flex items-center justify-center hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Initialize SweetAlert2 with custom theme
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#1f2937',
            color: '#f9fafb',
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        // Delivery Confirmation Functions
        function confirmStartDelivery() {
            const orderNumber = '{{ $order->order_number }}';
            const form = document.getElementById('start-delivery-form');

            Swal.fire({
                title: 'Start Delivery',
                html: `<div class="text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-play text-green-600 text-2xl"></i>
                    </div>
                    <p class="text-lg font-bold text-gray-900">Order #${orderNumber}</p>
                    <p class="text-gray-600 mt-2">Are you ready to start the delivery?</p>
                    <div class="mt-4 p-3 bg-blue-50 rounded-xl border border-blue-100">
                        <p class="text-sm text-blue-700">
                            <i class="fas fa-info-circle mr-2"></i>
                            You will be responsible for picking up and delivering this order.
                        </p>
                    </div>
                </div>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-play mr-2"></i>Start Delivery',
                cancelButtonText: '<i class="fas fa-times mr-2"></i>Not Yet',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-3 font-medium',
                    cancelButton: 'rounded-xl px-6 py-3 font-medium'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Toast.fire({
                        icon: 'success',
                        title: `Delivery started for order #${orderNumber}`
                    });
                    setTimeout(() => {
                        form.submit();
                    }, 1500);
                }
            });
        }

        function confirmMarkDelivered() {
            const orderNumber = '{{ $order->order_number }}';
            const form = document.getElementById('mark-delivered-form');

            Swal.fire({
                title: 'Confirm Delivery',
                html: `<div class="text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                    <p class="text-lg font-bold text-gray-900">Order #${orderNumber}</p>
                    <p class="text-gray-600 mt-2">Have you successfully delivered this order to the customer?</p>
                    
                    <div class="mt-4 p-4 bg-yellow-50 rounded-xl border border-yellow-100 text-left">
                        <p class="text-sm font-medium text-yellow-800 mb-2">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Please ensure:
                        </p>
                        <ul class="text-sm text-yellow-700 space-y-1">
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-xs mr-2"></i>
                                Order was received by the correct customer
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-xs mr-2"></i>
                                Payment was collected (if COD)
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-xs mr-2"></i>
                                Customer is satisfied with the delivery
                            </li>
                        </ul>
                    </div>
                </div>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-check mr-2"></i>Yes, Delivered',
                cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
                reverseButtons: true,
                showCloseButton: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-3 font-medium',
                    cancelButton: 'rounded-xl px-6 py-3 font-medium'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Toast.fire({
                        icon: 'success',
                        title: `Order #${orderNumber} marked as delivered`
                    });
                    setTimeout(() => {
                        form.submit();
                    }, 1500);
                }
            });
        }

        // Chat functionality
        const chatModal = document.getElementById('chat-modal');
        const closeChatBtn = document.getElementById('close-chat-modal');
        const chatMessages = document.getElementById('chat-messages');
        const chatInput = document.getElementById('chat-input');
        const sendChatBtn = document.getElementById('send-chat-btn');
        const orderId = {{ $order->id }};
        let pollingInterval;

        window.openChat = function () {
            chatModal.classList.remove('hidden');
            loadMessages();
            pollingInterval = setInterval(loadMessages, 3000);
        }

        function closeChat() {
            chatModal.classList.add('hidden');
            clearInterval(pollingInterval);
        }

        closeChatBtn.addEventListener('click', closeChat);

        chatModal.addEventListener('click', function (e) {
            if (e.target === chatModal) {
                closeChat();
            }
        });

        function loadMessages() {
            fetch(`/rider/orders/${orderId}/messages`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(res => res.json())
                .then(data => {
                    const msgs = data.messages || data;
                    chatMessages.innerHTML = '';
                    msgs.forEach(msg => {
                        const isMe = msg.is_own_message;
                        const div = document.createElement('div');
                        div.className = `flex ${isMe ? 'justify-end' : 'justify-start'}`;
                        div.innerHTML = `
                        <div class="${isMe ? 'chat-message-right' : 'chat-message-left'} max-w-[80%] p-3 text-sm">
                            <p class="font-medium text-xs opacity-80 mb-1">${msg.sender_name}</p>
                            <p class="mt-1">${msg.message}</p>
                            <span class="text-[10px] opacity-70 block text-right mt-1">${msg.created_at}</span>
                        </div>
                    `;
                        chatMessages.appendChild(div);
                    });
                    chatMessages.scrollTop = chatMessages.scrollHeight;
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

        function sendMessage() {
            const msg = chatInput.value.trim();
            if (!msg) return;

            const formData = new FormData();
            formData.append('message', msg);
            formData.append('_token', '{{ csrf_token() }}');

            fetch(`/rider/orders/${orderId}/messages`, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(() => {
                    chatInput.value = '';
                    loadMessages();
                })
                .catch(error => {
                    console.error('Error sending message:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Error sending message. Please try again.'
                    });
                });
        }

        sendChatBtn.addEventListener('click', sendMessage);
        chatInput.addEventListener('keypress', (e) => { if (e.key === 'Enter') sendMessage(); });

        // Close with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !chatModal.classList.contains('hidden')) {
                closeChat();
            }
        });

        // Fade-in animation
        document.addEventListener('DOMContentLoaded', function () {
            const fadeElements = document.querySelectorAll('.fade-in');
            fadeElements.forEach((element, index) => {
                element.style.animationDelay = `${index * 0.1}s`;
            });

            // Attach SweetAlert2 confirmations to buttons
            const startDeliveryBtn = document.querySelector('.start-delivery-btn');
            const markDeliveredBtn = document.querySelector('.mark-delivered-btn');

            if (startDeliveryBtn) {
                startDeliveryBtn.addEventListener('click', confirmStartDelivery);
            }

            if (markDeliveredBtn) {
                markDeliveredBtn.addEventListener('click', confirmMarkDelivered);
            }
        });
    </script>
</body>

</html>