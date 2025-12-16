<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with Rider - NaNi Owner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <img src="{{ asset('images/NaNi_Logo.png') }}" alt="NaNi Logo" class="h-10 w-10 mr-3">
                    <div>
                        <a href="/" class="text-xl font-bold text-gray-800">NaNi</a>
                        <p class="text-xs text-gray-500 -mt-1">Chat with Rider</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('owner.dashboard') }}"
                        class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-1"></i>Dashboard
                    </a>
                    <a href="{{ route('owner.orders.show', $order) }}"
                        class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-arrow-left mr-1"></i>Back to Order
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg">
            <!-- Chat Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Chat with Rider</h2>
                        <p class="text-gray-600">Order #{{ $order->order_number }} - {{ $order->rider->name }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                            @if($order->status == 'on_the_way') bg-purple-100 text-purple-800
                            @elseif($order->status == 'ready') bg-green-100 text-green-800
                            @else bg-blue-100 text-blue-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Chat Messages -->
            <div class="h-96 overflow-y-auto p-6 bg-gray-50" id="chat-messages">
                <div class="space-y-4" id="messages-container">
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-comments text-4xl mb-2"></i>
                        <p>Loading messages...</p>
                    </div>
                </div>
            </div>

            <!-- Message Input -->
            <div class="px-6 py-4 border-t border-gray-200">
                <form id="message-form" class="flex space-x-4">
                    @csrf
                    <input type="text" name="message" id="message-input" placeholder="Type your message..."
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-paper-plane mr-2"></i>Send
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const orderId = {{ $order->id }};
        const currentUserId = {{ Auth::id() }};
        let messagePolling;

        // Load messages
        function loadMessages() {
            fetch(`/owner/orders/${orderId}/chat/messages`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(messages => {
                    const container = document.getElementById('messages-container');

                    container.innerHTML = '';

                    if (messages.length === 0) {
                        container.innerHTML = `
                            <div class="text-center text-gray-500 py-8">
                                <i class="fas fa-comments text-4xl mb-2"></i>
                                <p>No messages yet. Start the conversation!</p>
                            </div>
                        `;
                        return;
                    }

                    messages.forEach(message => {
                        const messageDiv = document.createElement('div');
                        messageDiv.className = `flex ${message.sender_id === currentUserId ? 'justify-end' : 'justify-start'}`;

                        messageDiv.innerHTML = `
                            <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg ${message.sender_id === currentUserId ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'}">
                                <div class="text-sm">${message.message}</div>
                                <div class="text-xs mt-1 opacity-70 ${message.sender_id === currentUserId ? 'text-blue-100' : 'text-gray-600'}">
                                    ${new Date(message.created_at).toLocaleTimeString()}
                                    ${message.is_read && message.sender_id === currentUserId ? ' <i class="fas fa-check-double"></i>' : ''}
                                </div>
                            </div>
                        `;
                        container.appendChild(messageDiv);
                    });

                    // Scroll to bottom
                    const chatMessages = document.getElementById('chat-messages');
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                })
                .catch(error => {
                    console.error('Error loading messages:', error);
                    document.getElementById('messages-container').innerHTML = `
                        <div class="text-center text-red-500 py-8">
                            <i class="fas fa-exclamation-triangle text-4xl mb-2"></i>
                            <p>Error loading messages. Please refresh the page.</p>
                        </div>
                    `;
                });
        }

        // Send message
        document.getElementById('message-form').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const messageInput = document.getElementById('message-input');

            if (!messageInput.value.trim()) return;

            fetch(`/owner/orders/${orderId}/chat/send`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        messageInput.value = '';
                        loadMessages();
                    }
                })
                .catch(error => {
                    console.error('Error sending message:', error);
                    alert('Error sending message. Please try again.');
                });
        });

        // Poll for new messages every 3 seconds
        function startPolling() {
            messagePolling = setInterval(loadMessages, 3000);
        }

        // Initial load
        loadMessages();
        startPolling();

        // Cleanup on page unload
        window.addEventListener('beforeunload', function () {
            clearInterval(messagePolling);
        });

        // Auto-focus message input
        document.getElementById('message-input').focus();
    </script>
</body>

</html>