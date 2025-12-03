<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - NaNi Owner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- CSS for modal styling -->
    <style>
        #gcashReceiptModal {
            backdrop-filter: blur(5px);
        }

        #receipt-image {
            max-height: 70vh;
            object-fit: contain;
        }

        .modal-enter {
            animation: modalEnter 0.3s ease-out;
        }

        @keyframes modalEnter {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>

<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <!-- NaNi Logo -->
                    <div class="flex items-center">
                        <img src="{{ asset('images/nani-logo.png') }}" alt="NaNi Logo" class="h-10 w-10 mr-3">
                        <div>
                            <a href="/" class="text-xl font-bold text-gray-800">NaNi</a>
                            <p class="text-xs text-gray-500 -mt-1">Owner Dashboard</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('owner.dashboard') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <a href="{{ route('owner.menu.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-utensils mr-1"></i>Menu
                    </a>
                    <a href="{{ route('owner.orders.index') }}"
                        class="text-orange-600 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-shopping-cart mr-1"></i>Orders
                    </a>
                    <a href="{{ route('owner.analytics.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-chart-bar mr-1"></i>Analytics
                    </a>
                    <a href="{{ route('owner.reviews.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-star mr-1"></i>Reviews
                    </a>
                    <a href="{{ route('owner.riders.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-motorcycle mr-1"></i>Riders
                    </a>
                    <a href="{{ route('owner.profile.show') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-user mr-1"></i>Profile
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

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-center">
                    <p class="text-sm font-medium text-gray-600">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ array_sum($statusCounts) }}</p>
                </div>
            </div>
            <div class="bg-yellow-50 rounded-lg shadow p-4 border-l-4 border-yellow-400">
                <div class="text-center">
                    <p class="text-sm font-medium text-yellow-600">Pending</p>
                    <p class="text-2xl font-bold text-yellow-900">{{ $statusCounts['pending'] }}</p>
                </div>
            </div>
            <div class="bg-blue-50 rounded-lg shadow p-4 border-l-4 border-blue-400">
                <div class="text-center">
                    <p class="text-sm font-medium text-blue-600">Preparing</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $statusCounts['preparing'] }}</p>
                </div>
            </div>
            <div class="bg-green-50 rounded-lg shadow p-4 border-l-4 border-green-400">
                <div class="text-center">
                    <p class="text-sm font-medium text-green-600">Ready</p>
                    <p class="text-2xl font-bold text-green-900">{{ $statusCounts['ready'] }}</p>
                </div>
            </div>
            <div class="bg-purple-50 rounded-lg shadow p-4 border-l-4 border-purple-400">
                <div class="text-center">
                    <p class="text-sm font-medium text-purple-600">On the Way</p>
                    <p class="text-2xl font-bold text-purple-900">{{ $statusCounts['on_the_way'] }}</p>
                </div>
            </div>
            <div class="bg-gray-50 rounded-lg shadow p-4 border-l-4 border-gray-400">
                <div class="text-center">
                    <p class="text-sm font-medium text-gray-600">Delivered</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statusCounts['delivered'] }}</p>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">All Orders</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Order #
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Customer
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Payment Method
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Items
                            </th>
                            <!-- In the table header -->
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Payment Details
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($orders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $order->customer->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->customer_phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 capitalize">
                                        {{ str_replace('_', ' ', $order->payment_method) }}
                                    </div>
                                    @if($order->payment_method === 'cash_on_delivery' && $order->cash_provided)
                                        <div class="text-xs text-green-600 mt-1">
                                            Cash: ₱{{ number_format($order->cash_provided, 2) }}
                                        </div>
                                    @endif
                                    @if($order->payment_method === 'gcash')
                                        <div class="text-xs mt-1">
                                            @php
                                                $gcashStatus = $order->gcash_payment_status ?? 'pending';
                                            @endphp
                                            @if($gcashStatus === 'verified')
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full">Verified</span>
                                            @elseif($gcashStatus === 'rejected')
                                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full">Rejected</span>
                                            @else
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full">Pending
                                                    Verification</span>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        @foreach($order->items as $item)
                                            {{ $item->quantity }}x {{ $item->menuItem->name }}<br>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        ₱{{ number_format($order->total_amount, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                                                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                                                                                    @elseif($order->status == 'preparing') bg-blue-100 text-blue-800
                                                                                                    @elseif($order->status == 'ready') bg-green-100 text-green-800
                                                                                                    @elseif($order->status == 'on_the_way') bg-purple-100 text-purple-800
                                                                                                    @elseif($order->status == 'delivered') bg-gray-100 text-gray-800
                                                                                                    @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        @if(in_array($order->status, ['delivered', 'cancelled']))
                                            <i class="fas fa-lock ml-1" title="Finalized - No further changes allowed"></i>
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $order->created_at->format('M j, Y g:i A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('owner.orders.show', $order) }}"
                                        class="text-blue-600 hover:text-blue-900 mr-3">View</a>

                                    <!-- Only show Assign Rider button for non-delivered, non-cancelled orders -->
                                    @if(!in_array($order->status, ['delivered', 'cancelled']))
                                        <a href="{{ route('owner.orders.assign-rider-form', $order) }}"
                                            class="text-orange-600 hover:text-orange-900 mr-3">
                                            <i class="fas fa-motorcycle mr-1"></i>Assign Rider
                                        </a>
                                    @endif

                                    <!-- Only show Order Status button for non-delivered, non-cancelled orders -->
                                    @if(!in_array($order->status, ['delivered', 'cancelled']))
                                        <button class="text-indigo-600 hover:text-indigo-900 update-status mr-3"
                                            data-order-id="{{ $order->id }}">
                                            <i class="fas fa-sync"></i> Order Status
                                        </button>
                                    @endif

                                    <!-- GCash Receipt Button -->
                                    @if($order->payment_method === 'gcash' && $order->gcash_receipt_path)
                                        <button type="button"
                                            class="text-green-600 hover:text-green-900 mr-3 view-gcash-receipt"
                                            data-order-id="{{ $order->id }}" data-order-number="{{ $order->order_number }}"
                                            data-receipt-path="{{ $order->gcash_receipt_path }}">
                                            <i class="fas fa-receipt mr-1"></i>View GCash
                                        </button>
                                    @endif

                                    <!-- Only show GCash Status Update for non-delivered, non-cancelled GCash orders -->
                                    @if($order->payment_method === 'gcash' && !in_array($order->status, ['delivered', 'cancelled']))
                                        <button class="text-purple-600 hover:text-purple-900 update-gcash-status mr-3"
                                            data-order-id="{{ $order->id }}">
                                            <i class="fas fa-sync"></i>
                                            GCash Status
                                        </button>
                                    @endif

                                    @if(in_array($order->status, ['pending', 'cancelled']))
                                        <form action="{{ route('owner.orders.destroy', $order) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('Are you sure you want to delete order #{{ $order->order_number }}? This action cannot be undone.')">
                                                <i class="fas fa-trash mr-1"></i>Delete
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Status Update Modal -->
    <div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Update Order Status</h3>
                <form id="statusForm" method="POST">
                    @csrf
                    @method('PUT')
                    <select name="status"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="pending">Pending</option>
                        <option value="preparing">Preparing</option>
                        <option value="ready">Ready</option>
                        <option value="on_the_way">On the Way</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="gcash_pending_verification">GCash Payment not yet Confirmed</option>
                    </select>
                    <div class="flex justify-end space-x-3 mt-4">
                        <button type="button" onclick="closeModal('statusModal')"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- GCash Status Update Modal -->
    <div id="gcashStatusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Update GCash Payment Status</h3>
                <form id="gcashStatusForm" method="POST">
                    @csrf
                    <select name="gcash_payment_status"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="pending">Pending Verification</option>
                        <option value="verified">Verified - Payment Received</option>
                        <option value="rejected">Rejected - Payment Issue</option>
                    </select>
                    <div class="flex justify-end space-x-3 mt-4">
                        <button type="button" onclick="closeModal('gcashStatusModal')"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- GCash Receipt Modal -->
    <div id="gcashReceiptModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900" id="modal-title">GCash Receipt - Order #<span
                            id="modal-order-number"></span></h3>
                    <button type="button" id="closeGcashModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="mb-4">
                    <!-- Loading Spinner -->
                    <div id="receipt-loading" class="text-center py-8 hidden">
                        <i class="fas fa-spinner fa-spin text-3xl text-blue-500 mb-2"></i>
                        <p class="text-gray-600">Loading receipt...</p>
                    </div>

                    <!-- Receipt Image -->
                    <div id="receipt-container" class="hidden">
                        <img id="receipt-image" src="" alt="GCash Receipt"
                            class="max-w-full h-auto mx-auto rounded-lg shadow-md">
                    </div>

                    <!-- Error Message -->
                    <div id="receipt-error" class="text-center py-8 hidden">
                        <i class="fas fa-exclamation-triangle text-3xl text-red-500 mb-2"></i>
                        <p class="text-red-600" id="error-message">Failed to load receipt</p>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end space-x-3 mt-4">
                    <button type="button" id="closeGcashModalBtn"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Close
                    </button>
                    <a id="download-receipt" href="#" download
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 hidden">
                        <i class="fas fa-download mr-2"></i>Download
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Order Status Update
            const updateButtons = document.querySelectorAll('.update-status');
            const statusModal = document.getElementById('statusModal');
            const statusForm = document.getElementById('statusForm');

            updateButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const orderId = this.getAttribute('data-order-id');
                    statusForm.action = `/owner/orders/${orderId}/status`;
                    statusModal.classList.remove('hidden');
                });
            });

            // GCash Status Update
            const gcashUpdateButtons = document.querySelectorAll('.update-gcash-status');
            const gcashStatusModal = document.getElementById('gcashStatusModal');
            const gcashStatusForm = document.getElementById('gcashStatusForm');

            gcashUpdateButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const orderId = this.getAttribute('data-order-id');
                    gcashStatusForm.action = `/owner/orders/${orderId}/gcash-status`;
                    gcashStatusModal.classList.remove('hidden');
                });
            });

            // GCash Receipt Modal
            const gcashModal = document.getElementById('gcashReceiptModal');
            const modalTitle = document.getElementById('modal-order-number');
            const receiptImage = document.getElementById('receipt-image');
            const receiptContainer = document.getElementById('receipt-container');
            const receiptLoading = document.getElementById('receipt-loading');
            const receiptError = document.getElementById('receipt-error');
            const errorMessage = document.getElementById('error-message');
            const downloadLink = document.getElementById('download-receipt');

            // View GCash Receipt buttons
            document.querySelectorAll('.view-gcash-receipt').forEach(button => {
                button.addEventListener('click', function () {
                    const orderId = this.getAttribute('data-order-id');
                    const orderNumber = this.getAttribute('data-order-number');
                    const receiptPath = this.getAttribute('data-receipt-path');

                    showGcashReceipt(orderId, orderNumber, receiptPath);
                });
            });

            // Close GCash modal buttons
            document.getElementById('closeGcashModal').addEventListener('click', closeGcashModal);
            document.getElementById('closeGcashModalBtn').addEventListener('click', closeGcashModal);

            // Close GCash modal when clicking outside
            gcashModal.addEventListener('click', function (e) {
                if (e.target === gcashModal) {
                    closeGcashModal();
                }
            });

            function showGcashReceipt(orderId, orderNumber, receiptPath) {
                // Reset modal state
                receiptContainer.classList.add('hidden');
                receiptError.classList.add('hidden');
                receiptLoading.classList.remove('hidden');
                downloadLink.classList.add('hidden');

                // Set modal title
                modalTitle.textContent = orderNumber;

                // Show modal with animation
                gcashModal.classList.remove('hidden');
                gcashModal.classList.add('modal-enter');

                // Build receipt URL
                const receiptUrl = `/owner/orders/${orderId}/gcash-receipt?t=${Date.now()}`;
                const downloadUrl = `/owner/orders/${orderId}/gcash-receipt?download=1`;

                // Set download link
                downloadLink.href = downloadUrl;
                downloadLink.setAttribute('download', `gcash-receipt-${orderNumber}.jpg`);

                // Load image
                receiptImage.onload = function () {
                    receiptLoading.classList.add('hidden');
                    receiptContainer.classList.remove('hidden');
                    downloadLink.classList.remove('hidden');
                };

                receiptImage.onerror = function () {
                    receiptLoading.classList.add('hidden');
                    receiptError.classList.remove('hidden');
                    errorMessage.textContent = 'Failed to load GCash receipt image';
                };

                receiptImage.src = receiptUrl;
            }

            function closeGcashModal() {
                gcashModal.classList.remove('modal-enter');
                gcashModal.classList.add('hidden');
                // Clear the image source when closing to prevent caching issues
                receiptImage.src = '';
            }

            // Close modal with Escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    if (!gcashModal.classList.contains('hidden')) {
                        closeGcashModal();
                    }
                    if (!statusModal.classList.contains('hidden')) {
                        closeModal('statusModal');
                    }
                    if (!gcashStatusModal.classList.contains('hidden')) {
                        closeModal('gcashStatusModal');
                    }
                }
            });
        });

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
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

            // Use FormData with CSRF token
            const formData = new FormData();
            formData.append('message', message);
            formData.append('_token', '{{ csrf_token() }}'); // Add CSRF token

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
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

        // Close modal when clicking outside
        window.onclick = function (event) {
            if (event.target.id === 'statusModal') {
                closeModal('statusModal');
            }
            if (event.target.id === 'gcashStatusModal') {
                closeModal('gcashStatusModal');
            }
        }
        // Prevent status updates for delivered/cancelled orders
        document.addEventListener('DOMContentLoaded', function () {
            // Order Status Update - only add event listeners to visible buttons
            const updateButtons = document.querySelectorAll('.update-status');
            const statusModal = document.getElementById('statusModal');
            const statusForm = document.getElementById('statusForm');

            updateButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const orderId = this.getAttribute('data-order-id');
                    statusForm.action = `/owner/orders/${orderId}/status`;
                    statusModal.classList.remove('hidden');
                });
            });

            // GCash Status Update - only add event listeners to visible buttons
            const gcashUpdateButtons = document.querySelectorAll('.update-gcash-status');
            const gcashStatusModal = document.getElementById('gcashStatusModal');
            const gcashStatusForm = document.getElementById('gcashStatusForm');

            gcashUpdateButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const orderId = this.getAttribute('data-order-id');
                    gcashStatusForm.action = `/owner/orders/${orderId}/gcash-status`;
                    gcashStatusModal.classList.remove('hidden');
                });
            });
        });
    </script>
</body>

</html>