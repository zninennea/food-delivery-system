<div id="chat-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 h-[600px] flex flex-col">
        <!-- Modal Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b bg-blue-600 text-white rounded-t-lg">
            <div>
                <h3 class="text-lg font-medium" id="chat-title">
                    <i class="fas fa-comments mr-2"></i>Chat
                </h3>
                <p class="text-sm opacity-75" id="chat-subtitle">Order #<span id="chat-order-number"></span></p>
            </div>
            <button id="close-chat-modal" class="text-white hover:text-gray-200">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Chat Messages -->
        <div class="flex-1 p-4 overflow-hidden">
            <div id="chat-messages" class="h-full overflow-y-auto space-y-3">
                <div class="text-center text-gray-500 py-4">Chat ready. Click to start conversation.</div>
            </div>
        </div>

        <!-- Message Input Area -->
        <div class="p-4 border-t bg-gray-50">
            <div class="flex space-x-2">
                <!-- Message Input -->
                <input type="text" id="chat-input" placeholder="Type your message..."
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">

                <!-- Send Button -->
                <button id="send-chat-btn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple chat functions
    class ChatPopup {
        constructor() {
            this.modal = document.getElementById('chat-modal');
            this.messagesContainer = document.getElementById('chat-messages');
            this.chatInput = document.getElementById('chat-input');
            this.sendBtn = document.getElementById('send-chat-btn');
            this.chatOrderNumber = document.getElementById('chat-order-number');
            this.chatTitle = document.getElementById('chat-title');
            this.chatSubtitle = document.getElementById('chat-subtitle');

            this.orderId = null;
            this.userRole = 'customer';
            this.pollingInterval = null;

            this.init();
        }

        init() {
            // Send message events
            this.sendBtn.addEventListener('click', () => this.sendMessage());
            this.chatInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') this.sendMessage();
            });

            // Close modal
            document.getElementById('close-chat-modal').addEventListener('click', () => this.close());
            this.modal.addEventListener('click', (e) => {
                if (e.target === this.modal) this.close();
            });

            // Detect user role
            if (window.location.pathname.includes('/rider/')) {
                this.userRole = 'rider';
            } else if (window.location.pathname.includes('/owner/')) {
                this.userRole = 'owner';
            }
        }

        open(orderId, orderNumber = '') {
            this.orderId = orderId;
            this.chatOrderNumber.textContent = orderNumber || orderId;

            // Set title based on role
            if (this.userRole === 'rider') {
                this.chatTitle.innerHTML = '<i class="fas fa-comments mr-2"></i>Chat with Customer';
            } else if (this.userRole === 'owner') {
                this.chatTitle.innerHTML = '<i class="fas fa-comments mr-2"></i>Chat with Rider';
            } else {
                this.chatTitle.innerHTML = '<i class="fas fa-comments mr-2"></i>Chat with Rider';
            }

            this.modal.classList.remove('hidden');
            this.loadMessages();
            this.startPolling();
        }

        close() {
            this.modal.classList.add('hidden');
            this.stopPolling();
            this.chatInput.value = '';
            this.orderId = null;
        }

        async loadMessages() {
            try {
                if (!this.orderId) return;

                let url;
                if (this.userRole === 'rider') {
                    url = '/rider/orders/' + this.orderId + '/messages';
                } else if (this.userRole === 'owner') {
                    url = '/owner/orders/' + this.orderId + '/chat/messages';
                } else {
                    url = '/customer/orders/' + this.orderId + '/messages';
                }

                const response = await fetch(url);
                if (!response.ok) throw new Error('Failed to load messages');

                const messages = await response.json();
                this.renderMessages(messages);

            } catch (error) {
                console.error('Error loading messages:', error);
                this.showError('Error loading messages');
            }
        }

        renderMessages(messages) {
            if (!messages || messages.length === 0) {
                this.messagesContainer.innerHTML = '<div class="text-center text-gray-500 py-4">No messages yet. Start the conversation!</div>';
                return;
            }

            this.messagesContainer.innerHTML = '';

            messages.forEach(message => {
                const isOwnMessage = message.is_own_message || message.sender_id === {{ Auth::id() }};
                const messageDiv = document.createElement('div');
                messageDiv.className = 'flex ' + (isOwnMessage ? 'justify-end' : 'justify-start') + ' mb-3';

                const messageBubble = document.createElement('div');
                messageBubble.className = 'max-w-xs px-4 py-2 rounded-lg ' +
                    (isOwnMessage ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800');

                // Sender info for others' messages
                if (!isOwnMessage) {
                    const senderInfo = document.createElement('div');
                    senderInfo.className = 'text-xs opacity-75 mb-1';
                    senderInfo.textContent = message.sender_name || 'User';
                    messageBubble.appendChild(senderInfo);
                }

                // Message content
                const messageText = document.createElement('div');
                messageText.textContent = message.message;
                messageBubble.appendChild(messageText);

                // Message time
                const timeSpan = document.createElement('div');
                timeSpan.className = 'text-xs mt-1 ' + (isOwnMessage ? 'text-blue-100' : 'text-gray-500');
                timeSpan.textContent = message.created_at;
                messageBubble.appendChild(timeSpan);

                messageDiv.appendChild(messageBubble);
                this.messagesContainer.appendChild(messageDiv);
            });

            // Scroll to bottom
            this.messagesContainer.scrollTop = this.messagesContainer.scrollHeight;
        }

        async sendMessage() {
            const messageText = this.chatInput.value.trim();
            if (!messageText || !this.orderId) return;

            // Disable send button
            this.sendBtn.disabled = true;
            const originalHtml = this.sendBtn.innerHTML;
            this.sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            try {
                let url;
                if (this.userRole === 'rider') {
                    url = '/rider/orders/' + this.orderId + '/messages';
                } else if (this.userRole === 'owner') {
                    url = '/owner/orders/' + this.orderId + '/chat/messages';
                } else {
                    url = '/customer/orders/' + this.orderId + '/messages';
                }

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: messageText })
                });

                if (!response.ok) throw new Error('Failed to send message');

                const result = await response.json();
                if (result.success) {
                    this.chatInput.value = '';
                    this.loadMessages();
                }

            } catch (error) {
                console.error('Error sending message:', error);
                alert('Error sending message: ' + error.message);
            } finally {
                this.sendBtn.disabled = false;
                this.sendBtn.innerHTML = originalHtml;
            }
        }

        showError(message) {
            this.messagesContainer.innerHTML = '<div class="text-center text-red-500 py-4">' + message + '</div>';
        }

        startPolling() {
            this.stopPolling();
            this.pollingInterval = setInterval(() => this.loadMessages(), 3000);
        }

        stopPolling() {
            if (this.pollingInterval) {
                clearInterval(this.pollingInterval);
            }
        }
    }

    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', function () {
        window.chatPopup = new ChatPopup();

        // Global functions
        window.openChat = function (orderId, orderNumber) {
            if (window.chatPopup) {
                window.chatPopup.open(orderId, orderNumber);
            }
        };
    });
</script>