<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Track Order #{{ $order->order_number }} - NaNi</title>
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
            background-color: #ea580c;
            border-radius: 18px 18px 4px 18px;
            color: white;
        }

        /* Smooth Progress Bar */
        #progress-bar {
            transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>

<body class="bg-stone-50 text-gray-800 antialiased">

    <nav
        class="fixed w-full top-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('customer.dashboard') }}" class="flex-shrink-0 flex items-center gap-2 group">
                    <img src="https://i.imgur.com/vPOu1H2.png" alt="NaNi Icon"
                        class="h-20 w-auto group-hover:rotate-12 transition-transform duration-300">
                </a>

                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('customer.dashboard') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-home mr-1"></i> Home
                    </a>
                    <a href="{{ route('customer.orders.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-list mr-1"></i> My Orders
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline ml-2">
                        @csrf
                        <button type="submit"
                            class="text-gray-400 hover:text-red-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            title="Logout">
                            <i class="fas fa-sign-out-alt text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="pt-32 pb-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 fade-in">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Order #{{ $order->order_number }}</h1>
                    <span class="px-3 py-1 text-sm font-bold rounded-full uppercase tracking-wider
                        @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status == 'preparing') bg-blue-100 text-blue-800
                        @elseif($order->status == 'ready') bg-green-100 text-green-800
                        @elseif($order->status == 'on_the_way') bg-purple-100 text-purple-800
                        @elseif($order->status == 'delivered') bg-gray-100 text-gray-800
                        @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                        @else bg-orange-100 text-orange-800 @endif">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>
                <p class="text-stone-500 mt-2 flex items-center gap-2">
                    <i class="far fa-clock"></i> Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}
                </p>
            </div>

            @if($order->canBeCancelled())
                <button id="cancel-order-btn"
                    class="bg-white border border-red-200 text-red-600 px-6 py-2.5 rounded-xl hover:bg-red-50 transition-colors font-medium shadow-sm">
                    Cancel Order
                </button>
            @endif
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 mb-8 fade-in">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 font-serif">Status</h3>
                <span class="text-orange-600 font-medium text-sm animate-pulse" id="progress-text">Updating...</span>
            </div>

            <div class="relative pt-2">
                <div class="overflow-hidden h-3 text-xs flex rounded-full bg-stone-100 border border-stone-200">
                    <div id="progress-bar"
                        class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-orange-500 to-red-500 w-0">
                    </div>
                </div>
                <div class="flex justify-between text-xs text-stone-400 mt-3 font-medium uppercase tracking-wide">
                    <span>Received</span>
                    <span>Preparing</span>
                    <span>Ready</span>
                    <span>On the Way</span>
                    <span>Delivered</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 fade-in" style="animation-delay: 0.1s;">

            <div class="lg:col-span-2 space-y-8">

                <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden">
                    <div class="p-6 border-b border-stone-100 bg-stone-50/50">
                        <h3 class="text-xl font-bold text-gray-900 font-serif">Order Details</h3>
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
                                        <p class="text-sm text-stone-500">₱{{ number_format($item->unit_price, 2) }}</p>
                                    </div>
                                </div>
                                <p class="font-bold text-gray-900">₱{{ number_format($item->total, 2) }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="bg-stone-50 p-6 border-t border-stone-100 space-y-2">
                        <div class="flex justify-between text-sm text-stone-600">
                            <span>Subtotal</span>
                            <span>₱{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-stone-600">
                            <span>Delivery Fee</span>
                            <span>₱{{ number_format($order->delivery_fee, 2) }}</span>
                        </div>
                        <div
                            class="flex justify-between text-lg font-bold text-gray-900 pt-2 border-t border-stone-200 mt-2">
                            <span>Total</span>
                            <span class="text-orange-600">₱{{ number_format($order->grand_total, 2) }}</span>
                        </div>
                        <div class="pt-4 mt-2">
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-stone-200 text-stone-700 text-xs font-bold uppercase tracking-wider">
                                <i class="fas fa-credit-card"></i> {{ str_replace('_', ' ', $order->payment_method) }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-1 space-y-8">

                @if($order->rider)
                    <div class="bg-white rounded-3xl shadow-xl border border-stone-100 p-6 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-orange-50 rounded-bl-full -mr-4 -mt-4 opacity-50">
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 font-serif mb-6 relative z-10">Your Rider</h3>

                        <div class="flex items-center gap-4 mb-6">
                            @if($order->rider->profile_picture)
                                <img src="{{ asset('storage/' . $order->rider->profile_picture) }}" alt="Rider"
                                    class="w-16 h-16 rounded-full object-cover border-2 border-white shadow-md">
                            @else
                                <div
                                    class="w-16 h-16 bg-stone-200 rounded-full flex items-center justify-center text-stone-400 text-2xl">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                            <div>
                                <p class="font-bold text-gray-900 text-lg">{{ $order->rider->name }}</p>
                                <div class="flex items-center gap-1 text-sm text-stone-500">
                                    <i class="fas fa-star text-yellow-400"></i>
                                    <span>4.8</span>
                                    <span class="text-stone-300">•</span>
                                    <span>{{ $order->rider->vehicle_type }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3 mb-6">
                            <div class="flex items-center gap-3 text-sm text-stone-600 bg-stone-50 p-3 rounded-xl">
                                <i class="fas fa-motorcycle text-orange-500"></i>
                                <span>{{ $order->rider->vehicle_plate }}</span>
                            </div>
                            <div class="flex items-center gap-3 text-sm text-stone-600 bg-stone-50 p-3 rounded-xl">
                                <i class="fas fa-phone text-orange-500"></i>
                                <span>{{ $order->rider->phone ?? 'No contact info' }}</span>
                            </div>
                        </div>

                        <button onclick="openChat({{ $order->id }}, '{{ $order->order_number }}')"
                            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 rounded-xl font-bold shadow-lg shadow-blue-500/30 hover:-translate-y-1 transition-all flex justify-center items-center gap-2">
                            <i class="fas fa-comment-dots"></i> Chat with Rider
                        </button>
                    </div>
                @else
                    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 text-center">
                        <div
                            class="w-16 h-16 bg-stone-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                            <i class="fas fa-search text-stone-400 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Finding a Rider</h3>
                        <p class="text-stone-500 text-sm">We are currently assigning the nearest rider to your order.</p>
                    </div>
                @endif

                @if($order->status == 'delivered')
                    @php $hasReviewed = \App\Models\Review::where('order_id', $order->id)->exists(); @endphp
                    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 font-serif mb-4">How was it?</h3>
                        @if(!$hasReviewed)
                            <a href="{{ route('customer.reviews.create', $order) }}"
                                class="block w-full bg-stone-900 text-white text-center py-3 rounded-xl font-bold hover:bg-orange-600 transition-colors">
                                Write a Review
                            </a>
                        @else
                            <button disabled
                                class="block w-full bg-stone-100 text-stone-400 text-center py-3 rounded-xl font-bold cursor-not-allowed">
                                Reviewed <i class="fas fa-check ml-1"></i>
                            </button>
                        @endif
                    </div>
                @endif

            </div>
        </div>
    </div>

    <div id="chat-modal"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col h-[500px]">
            <div class="bg-stone-900 text-white p-4 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm">Rider Chat</h3>
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
                        class="flex-1 bg-stone-100 border-none rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 text-sm">
                    <button id="send-chat-btn"
                        class="bg-orange-600 text-white w-12 h-12 rounded-xl flex items-center justify-center hover:bg-orange-700 transition-colors shadow-lg shadow-orange-500/30">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="cancel-modal"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl">
            <h3 class="text-2xl font-bold text-gray-900 font-serif mb-2">Cancel Order?</h3>
            <p class="text-stone-500 mb-6">Please tell us why you want to cancel.</p>

            <form action="{{ route('customer.orders.cancel', $order) }}" method="POST">
                @csrf
                <div class="space-y-3 mb-6">
                    @foreach(['Order taking too long', 'Placed by mistake', 'Change of plans', 'Other'] as $reason)
                        <label
                            class="flex items-center p-3 rounded-xl border border-stone-200 cursor-pointer hover:border-orange-500 hover:bg-orange-50 transition-all">
                            <input type="radio" name="cancellation_reason" value="{{ $reason }}"
                                class="text-orange-600 focus:ring-orange-500">
                            <span class="ml-3 text-sm font-medium text-gray-700">{{ $reason }}</span>
                        </label>
                    @endforeach
                    <textarea id="other-reason" name="cancellation_reason_other"
                        class="w-full mt-2 p-3 border border-stone-200 rounded-xl text-sm hidden focus:ring-orange-500 focus:border-orange-500"
                        placeholder="Please specify..."></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" id="close-cancel-modal"
                        class="flex-1 py-3 text-stone-600 font-bold hover:bg-stone-100 rounded-xl transition-colors">Keep
                        Order</button>
                    <button type="submit"
                        class="flex-1 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors shadow-lg shadow-red-500/30">Confirm</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // --- Progress Bar Logic ---
        function updateProgressBar() {
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');
            const status = '{{ $order->status }}';
            const hasRider = {{ $order->rider ? 'true' : 'false' }};

            let width = '0%';
            let label = 'Processing';

            switch (status) {
                case 'pending': width = '15%'; label = 'Order Received'; break;
                case 'preparing': width = '40%'; label = 'Preparing Food'; break;
                case 'ready': width = '65%'; label = hasRider ? 'Waiting for Rider' : 'Ready for Pickup'; break;
                case 'on_the_way': width = '85%'; label = 'Out for Delivery'; break;
                case 'delivered': width = '100%'; label = 'Delivered'; break;
                case 'cancelled': width = '100%'; label = 'Cancelled'; progressBar.classList.add('from-red-500', 'to-red-600'); break;
            }

            if (status !== 'cancelled') {
                progressText.textContent = label;
                progressBar.style.width = width;
            } else {
                progressText.textContent = 'Order Cancelled';
                progressText.classList.add('text-red-600');
                progressBar.style.width = '100%';
            }
        }

        // --- Cancellation Modal ---
        const cancelModal = document.getElementById('cancel-modal');
        const cancelBtn = document.getElementById('cancel-order-btn');
        const closeCancelBtn = document.getElementById('close-cancel-modal');
        const radioButtons = document.querySelectorAll('input[name="cancellation_reason"]');
        const otherTextArea = document.getElementById('other-reason');

        if (cancelBtn) {
            cancelBtn.addEventListener('click', () => cancelModal.classList.remove('hidden'));
            closeCancelBtn.addEventListener('click', () => cancelModal.classList.add('hidden'));

            radioButtons.forEach(radio => {
                radio.addEventListener('change', (e) => {
                    if (e.target.value === 'Other') {
                        otherTextArea.classList.remove('hidden');
                    } else {
                        otherTextArea.classList.add('hidden');
                    }
                });
            });
        }

        // --- Initialize ---
        document.addEventListener('DOMContentLoaded', () => {
            updateProgressBar();
            // Polling for status updates could be added here
        });

        // --- Chat Logic (Preserved) ---
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

        function loadMessages() {
            fetch(`/customer/orders/${orderId}/messages`, {
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
                            <p>${msg.message}</p>
                            <span class="text-[10px] opacity-70 block text-right mt-1">${msg.created_at}</span>
                        </div>
                    `;
                        chatMessages.appendChild(div);
                    });
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                });
        }

        function sendMessage() {
            const msg = chatInput.value.trim();
            if (!msg) return;

            const formData = new FormData();
            formData.append('message', msg);
            formData.append('_token', '{{ csrf_token() }}');

            fetch(`/customer/orders/${orderId}/messages`, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(() => {
                    chatInput.value = '';
                    loadMessages();
                });
        }

        sendChatBtn.addEventListener('click', sendMessage);
        chatInput.addEventListener('keypress', (e) => { if (e.key === 'Enter') sendMessage(); });

    </script>
</body>

</html>