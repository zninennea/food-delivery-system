<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management - NaNi Owner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* SweetAlert2 custom styles */
        .swal2-popup {
            font-family: 'Inter', sans-serif !important;
            border-radius: 1.5rem !important;
        }

        .swal2-title {
            font-family: 'Playfair Display', serif !important;
        }

        .swal2-confirm {
            transition: all 0.2s ease !important;
            border-radius: 0.75rem !important;
        }

        .swal2-confirm:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        }
    </style>
</head>

<body class="bg-stone-50 text-gray-800 antialiased">

    <nav
        class="fixed w-full top-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('owner.dashboard') }}" class="flex-shrink-0 flex items-center gap-2 group">
                    <img src="https://i.imgur.com/vPOu1H2.png" alt="NaNi Icon"
                        class="h-20 w-auto group-hover:rotate-12 transition-transform duration-300">

                </a>

                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('owner.dashboard') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-home mr-1"></i> Dashboard
                    </a>
                    <a href="{{ route('owner.orders.index') }}"
                        class="text-orange-600 bg-orange-50 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-shopping-cart mr-1"></i> Orders
                    </a>
                    <a href="{{ route('owner.menu.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-utensils mr-1"></i> Menu
                    </a>
                    <a href="{{ route('owner.analytics.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-chart-line mr-1"></i> Analytics
                    </a>
                    <a href="{{ route('owner.reviews.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-star mr-1"></i> Reviews
                    </a>
                    <a href="{{ route('owner.riders.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-motorcycle mr-1"></i> Riders
                    </a>

                    <div class="ml-4 pl-4 border-l border-gray-200 flex items-center gap-3">
                        <!-- Profile Button (Active) -->
                        <a href="{{ route('owner.profile.show') }}"
                            class="text-grey-600 hover:text-orange-600 transition-colors">
                            <i class="fas fa-user-circle text-xl"></i>
                        </a>

                        <span class="text-sm font-bold text-gray-700">Admin</span>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors"
                                title="Logout">
                                <i class="fas fa-sign-out-alt text-lg"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="pt-32 pb-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-8 fade-in">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Order Management</h1>
                <p class="text-stone-500">Track and manage customer orders.</p>
            </div>

            <div class="flex gap-2">
                @if(request('status') && request('status') !== 'all')
                    <a href="{{ route('owner.orders.index') }}"
                        class="bg-orange-50 border border-orange-200 text-orange-600 px-4 py-2 rounded-xl text-sm font-bold hover:bg-orange-100 transition-colors shadow-sm">
                        <i class="fas fa-times mr-1"></i> Clear Filter
                    </a>
                @endif
                <button onclick="window.location.reload()"
                    class="bg-white border border-stone-200 text-stone-600 px-4 py-2 rounded-xl text-sm font-bold hover:bg-stone-50 transition-colors shadow-sm">
                    <i class="fas fa-sync-alt mr-1"></i> Refresh
                </button>
            </div>
        </div>

        <div class="mb-8 fade-in" style="animation-delay: 0.1s;">
            <div class="flex overflow-x-auto space-x-2 pb-2 hide-scrollbar">
                @php
                    $statuses = ['all', 'pending', 'preparing', 'ready', 'on_the_way', 'delivered', 'cancelled'];
                    $currentStatus = request('status', 'all');
                @endphp

                @foreach($statuses as $status)
                            <a href="{{ route('owner.orders.index', ['status' => $status]) }}" class="px-5 py-2.5 rounded-full text-sm font-bold whitespace-nowrap transition-all duration-200 
                                                                                                                                                                  {{ $currentStatus === $status
                    ? 'bg-stone-900 text-white shadow-md'
                    : 'bg-white text-stone-600 border border-stone-200 hover:bg-stone-50 hover:border-stone-300' }}">
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                @if($status !== 'all' && isset($statusCounts[$status]) && $statusCounts[$status] > 0)
                                    <span class="ml-2 bg-orange-100 text-orange-600 text-xs px-1.5 py-0.5 rounded-full">
                                        {{ $statusCounts[$status] }}
                                    </span>
                                @endif
                            </a>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden fade-in"
            style="animation-delay: 0.2s;">

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-stone-100">
                    <thead class="bg-stone-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                Order</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                Customer</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                Payment</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                Rider</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-stone-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    @if(request('status') && request('status') !== 'all')
                        <tbody>
                            <tr>
                                <td colspan="6"
                                    class="px-6 py-3 bg-orange-50 text-orange-700 text-sm border-b border-orange-100">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-filter"></i>
                                            Showing {{ ucfirst(request('status')) }} orders only
                                            <span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full">
                                                {{ $orders->total() }} {{ Str::plural('order', $orders->total()) }}
                                            </span>
                                        </div>
                                        <a href="{{ route('owner.orders.index') }}"
                                            class="text-orange-600 hover:text-orange-800 text-sm font-medium">
                                            <i class="fas fa-times mr-1"></i> Clear filter
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    @endif

                    <tbody class="bg-white divide-y divide-stone-100">
                        @forelse($orders as $order)
                            <tr class="hover:bg-stone-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-stone-100 flex items-center justify-center text-stone-500 font-bold text-xs">
                                            #{{ substr($order->order_number, -4) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('owner.orders.show', $order) }}"
                                                class="text-sm font-bold text-gray-900 hover:text-orange-600 transition-colors">
                                                {{ $order->order_number }}
                                            </a>
                                            <div class="text-xs text-stone-500 mt-0.5">
                                                {{ $order->items_count }} items •
                                                ₱{{ number_format($order->total_amount, 2) }}
                                            </div>
                                            <div class="text-[10px] text-stone-400 mt-0.5">
                                                {{ $order->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $order->customer->name }}</div>
                                    <div class="text-xs text-stone-500">{{ $order->customer->phone }}</div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full uppercase tracking-wide
                                                                                            @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                                                                            @elseif($order->status == 'preparing') bg-blue-100 text-blue-800
                                                                                            @elseif($order->status == 'ready') bg-purple-100 text-purple-800
                                                                                            @elseif($order->status == 'on_the_way') bg-indigo-100 text-indigo-800
                                                                                            @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                                                                            @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                                                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                        @if(in_array($order->status, ['delivered', 'cancelled']))
                                            <i class="fas fa-lock text-xs text-stone-400"
                                                title="Order finalized - No further changes allowed"></i>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-900 flex items-center gap-2">
                                            @if($order->payment_method == 'gcash')
                                                <img src="{{ asset('images/gcash-logo.png') }}" class="h-4 w-auto" alt="GCash"
                                                    onerror="this.style.display='none';this.nextElementSibling.style.display='inline'">
                                                <span class="hidden">GCash</span>
                                            @else
                                                <i class="fas fa-money-bill-wave text-green-600"></i> COD
                                            @endif
                                        </span>

                                        @if($order->payment_method == 'gcash')
                                            <div class="mt-1">
                                                <div class="flex items-center gap-1">
                                                    <span
                                                        class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                                                                                                                                    @if($order->gcash_payment_status == 'verified') bg-green-100 text-green-700
                                                                                                                                                    @elseif($order->gcash_payment_status == 'rejected') bg-red-100 text-red-700
                                                                                                                                                    @else bg-yellow-100 text-yellow-700 @endif">
                                                        {{ $order->gcash_payment_status ?? 'pending' }}
                                                    </span>
                                                    @if(in_array($order->status, ['delivered', 'cancelled']))
                                                        <i class="fas fa-lock text-xs text-stone-400" title="Finalized order"></i>
                                                    @endif
                                                </div>
                                                @if($order->gcash_receipt_path && !in_array($order->status, ['delivered', 'cancelled']))
                                                    <button class="text-[10px] text-blue-600 hover:underline ml-1 view-receipt-btn"
                                                        data-image-url="{{ asset('storage/' . $order->gcash_receipt_path) }}"
                                                        data-order-id="{{ $order->id }}"
                                                        data-ref-number="{{ $order->gcash_reference_number ?? 'N/A' }}"
                                                        data-status="{{ $order->gcash_payment_status ?? 'pending' }}"
                                                        data-order-status="{{ $order->status }}">
                                                        View
                                                    </button>
                                                @elseif($order->gcash_receipt_path)
                                                    <span class="text-[10px] text-stone-400 ml-1">View only</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($order->rider)
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-6 h-6 bg-stone-200 rounded-full flex items-center justify-center text-[10px]">
                                                <i class="fas fa-motorcycle text-stone-500"></i>
                                            </div>
                                            <span class="text-sm text-gray-700">{{ $order->rider->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-stone-400 italic">Unassigned</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        @if(!in_array($order->status, ['delivered', 'cancelled']))
                                            <button
                                                class="p-2 bg-stone-100 text-stone-600 rounded-lg hover:bg-stone-200 hover:text-stone-900 transition-colors update-status-btn"
                                                data-order-id="{{ $order->id }}" data-current-status="{{ $order->status }}"
                                                title="Update Status">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>

                                            <a href="{{ route('owner.orders.assign-rider-form', $order) }}"
                                                class="p-2 bg-stone-100 text-stone-600 rounded-lg hover:bg-orange-100 hover:text-orange-600 transition-colors"
                                                title="Assign Rider">
                                                <i class="fas fa-motorcycle"></i>
                                            </a>
                                        @endif

                                        <a href="{{ route('owner.orders.show', $order) }}"
                                            class="p-2 bg-stone-900 text-white rounded-lg hover:bg-stone-800 transition-colors shadow-sm"
                                            title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if(!in_array($order->status, ['delivered', 'cancelled']))
                                            @if($order->payment_method == 'gcash' && $order->gcash_receipt_path)
                                                <button
                                                    class="p-2 bg-stone-100 text-stone-600 rounded-lg hover:bg-green-100 hover:text-green-600 transition-colors view-receipt-btn"
                                                    data-image-url="{{ asset('storage/' . $order->gcash_receipt_path) }}"
                                                    data-order-id="{{ $order->id }}"
                                                    data-ref-number="{{ $order->gcash_reference_number ?? 'N/A' }}"
                                                    data-status="{{ $order->gcash_payment_status ?? 'pending' }}"
                                                    data-order-status="{{ $order->status }}" title="Verify GCash Payment">
                                                    <i class="fas fa-money-check-alt"></i>
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-stone-400">
                                        <i class="fas fa-clipboard-list text-4xl mb-4"></i>
                                        <p class="text-lg font-medium text-gray-900">No orders found</p>
                                        <p class="text-sm">Try changing the filter status.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
                <div class="px-6 py-4 border-t border-stone-100 bg-stone-50">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <div id="gcashReceiptModal"
        class="fixed inset-0 bg-black/80 backdrop-blur-md hidden flex items-center justify-center z-50 p-4 opacity-0 transition-opacity duration-300">
        <div
            class="bg-white rounded-3xl w-full max-w-lg shadow-2xl transform scale-95 transition-transform duration-300 modal-content overflow-hidden flex flex-col max-h-[90vh]">
            <div class="p-4 bg-stone-900 text-white flex justify-between items-center">
                <h3 class="font-bold">Payment Verification</h3>
                <button type="button" class="close-modal text-stone-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-4 bg-stone-100 flex items-center justify-center">
                <img id="receipt-image" src="" alt="Receipt"
                    class="max-w-full rounded-lg shadow-md border border-stone-200">
            </div>

            <div class="p-6 bg-white border-t border-stone-100">
                <div class="flex justify-between items-center mb-4 text-sm">
                    <span class="text-stone-500">Ref No:</span>
                    <span id="receipt-ref-no"
                        class="font-mono font-bold text-gray-900 bg-stone-100 px-2 py-1 rounded"></span>
                </div>

                <div class="mb-4 text-sm">
                    <span class="text-stone-500">Current Status:</span>
                    <span id="current-payment-status" class="ml-2 font-bold"></span>
                </div>

                <form id="gcashStatusForm" method="POST" class="grid grid-cols-2 gap-4">
                    @csrf
                    <input type="hidden" name="gcash_payment_status" id="gcash_payment_status_input">
                    <input type="hidden" name="order_id" id="order_id_input">

                    <button type="button"
                        class="update-payment-btn w-full py-3 border border-red-200 text-red-600 font-bold rounded-xl hover:bg-red-50 transition-colors"
                        data-status="rejected">
                        Reject Payment
                    </button>
                    <button type="button"
                        class="update-payment-btn w-full py-3 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 shadow-lg shadow-green-500/30 transition-all"
                        data-status="verified">
                        Approve Payment
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Helper to open/close modals with animation
            function toggleModal(modalId, show = true) {
                const modal = document.getElementById(modalId);
                const content = modal.querySelector('.modal-content');

                if (show) {
                    modal.classList.remove('hidden');
                    // Small delay for CSS transition
                    setTimeout(() => {
                        modal.classList.remove('opacity-0');
                        if (content) {
                            content.classList.remove('scale-95');
                            content.classList.add('scale-100');
                        }
                    }, 10);
                } else {
                    modal.classList.add('opacity-0');
                    if (content) {
                        content.classList.remove('scale-100');
                        content.classList.add('scale-95');
                    }
                    setTimeout(() => {
                        modal.classList.add('hidden');
                    }, 300);
                }
            }

            // Close buttons
            document.querySelectorAll('.close-modal').forEach(btn => {
                btn.addEventListener('click', function () {
                    const modal = this.closest('.fixed');
                    toggleModal(modal.id, false);
                });
            });

            // 1. Status Update Logic
            document.querySelectorAll('.update-status-btn').forEach(btn => {
                btn.addEventListener('click', async function () {
                    const orderId = this.dataset.orderId;
                    const currentStatus = this.dataset.currentStatus;

                    // Check if order is delivered or cancelled
                    if (['delivered', 'cancelled'].includes(currentStatus)) {
                        Swal.fire({
                            title: 'Cannot Update Status',
                            html: `This order is already <strong class="capitalize">${currentStatus}</strong>.<br>
                                   <span class="text-stone-500 text-sm">Delivered or cancelled orders cannot be modified.</span>`,
                            icon: 'warning',
                            iconColor: '#f59e0b',
                            confirmButtonColor: '#1c1917',
                            confirmButtonText: 'OK',
                            customClass: {
                                popup: 'rounded-2xl shadow-2xl'
                            }
                        });
                        return;
                    }

                    // Define status options with icons and colors
                    const statusOptions = {
                        pending: { name: 'Pending', icon: '⏳', color: '#f59e0b' },
                        preparing: { name: 'Preparing', icon: '👨‍🍳', color: '#3b82f6' },
                        ready: { name: 'Ready', icon: '✅', color: '#8b5cf6' },
                        on_the_way: { name: 'On the Way', icon: '🏍️', color: '#6366f1' },
                        delivered: { name: 'Delivered', icon: '📦', color: '#10b981' },
                        cancelled: { name: 'Cancelled', icon: '❌', color: '#ef4444' }
                    };

                    // Create HTML for status options
                    let optionsHtml = '';
                    Object.entries(statusOptions).forEach(([key, value]) => {
                        const isCurrent = key === currentStatus;
                        optionsHtml += `
                            <button type="button" 
                                class="status-option w-full flex items-center justify-between p-4 rounded-xl border border-stone-200 hover:border-${key === 'cancelled' ? 'red' : 'orange'}-200 hover:bg-${key === 'cancelled' ? 'red' : 'orange'}-50 transition-all mb-2 ${isCurrent ? 'ring-2 ring-orange-300' : ''}"
                                data-status="${key}">
                                <div class="flex items-center gap-3">
                                    <span class="text-2xl">${value.icon}</span>
                                    <div class="text-left">
                                        <div class="font-bold text-gray-900">${value.name}</div>
                                        ${isCurrent ? '<div class="text-xs text-orange-600 font-medium">Current Status</div>' : ''}
                                    </div>
                                </div>
                                ${!isCurrent ? '<i class="fas fa-chevron-right text-stone-400"></i>' : ''}
                            </button>
                        `;
                    });

                    const { value: selectedStatus } = await Swal.fire({
                        title: 'Update Order Status',
                        html: `
                            <div class="text-left mb-4">
                                <p class="text-stone-600 mb-1">Select new status for Order #${orderId}</p>
                                <p class="text-xs text-stone-400">Current: <span class="font-bold capitalize">${currentStatus.replace('_', ' ')}</span></p>
                            </div>
                            <div id="status-options" class="max-h-64 overflow-y-auto">
                                ${optionsHtml}
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonColor: '#1c1917',
                        cancelButtonColor: '#78716c',
                        confirmButtonText: 'Update Status',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                        backdrop: 'rgba(0, 0, 0, 0.3)',
                        allowOutsideClick: true,
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            const selected = document.querySelector('.status-option[data-status].selected');
                            if (!selected) {
                                Swal.showValidationMessage('Please select a status');
                                return false;
                            }
                            return selected.dataset.status;
                        },
                        customClass: {
                            popup: 'rounded-2xl shadow-2xl',
                            confirmButton: 'rounded-xl px-6 py-2 font-bold shadow-sm',
                            cancelButton: 'rounded-xl px-6 py-2 font-bold bg-stone-200 hover:bg-stone-300 text-stone-700'
                        },
                        didOpen: () => {
                            // Add click handlers to status options
                            document.querySelectorAll('.status-option').forEach(option => {
                                option.addEventListener('click', function () {
                                    document.querySelectorAll('.status-option').forEach(opt => {
                                        opt.classList.remove('selected', 'bg-orange-50', 'border-orange-300');
                                    });
                                    this.classList.add('selected', 'bg-orange-50', 'border-orange-300');
                                });
                            });
                        }
                    });

                    if (selectedStatus) {
                        // Submit the form
                        const formData = new FormData();
                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('_method', 'PUT');
                        formData.append('status', selectedStatus);

                        try {
                            const response = await fetch(`/owner/orders/${orderId}/status`, {
                                method: 'POST',
                                body: formData
                            });

                            if (response.ok) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: `Order status updated to ${statusOptions[selectedStatus].name}`,
                                    icon: 'success',
                                    iconColor: '#10b981',
                                    confirmButtonColor: '#1c1917',
                                    confirmButtonText: 'OK',
                                    timer: 2000,
                                    timerProgressBar: true
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                throw new Error('Failed to update status');
                            }
                        } catch (error) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to update order status. Please try again.',
                                icon: 'error',
                                iconColor: '#dc2626',
                                confirmButtonColor: '#1c1917',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            });

            // 2. GCash Logic
            document.querySelectorAll('.view-receipt-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const orderStatus = this.dataset.orderStatus;

                    // Check if order is delivered or cancelled
                    if (['delivered', 'cancelled'].includes(orderStatus)) {
                        Swal.fire({
                            title: 'Cannot Verify Payment',
                            html: `This order is already <strong class="capitalize">${orderStatus}</strong>.<br>
                                   <span class="text-stone-500 text-sm">Delivered or cancelled orders cannot have payment status modified.</span>`,
                            icon: 'warning',
                            iconColor: '#f59e0b',
                            confirmButtonColor: '#1c1917',
                            confirmButtonText: 'OK',
                            customClass: {
                                popup: 'rounded-2xl shadow-2xl'
                            }
                        });
                        return;
                    }

                    const imgUrl = this.dataset.imageUrl;
                    const refNo = this.dataset.refNumber || 'N/A';
                    const orderId = this.dataset.orderId;
                    const currentStatus = this.dataset.status || 'pending';

                    document.getElementById('receipt-image').src = imgUrl;
                    document.getElementById('receipt-ref-no').textContent = refNo;
                    document.getElementById('current-payment-status').textContent = currentStatus.toUpperCase();
                    document.getElementById('order_id_input').value = orderId;

                    const form = document.getElementById('gcashStatusForm');
                    form.action = `/owner/orders/${orderId}/gcash-status`;

                    // Style current status
                    const statusElement = document.getElementById('current-payment-status');
                    statusElement.className = 'ml-2 font-bold px-2 py-1 rounded text-xs uppercase';
                    if (currentStatus === 'verified') {
                        statusElement.classList.add('bg-green-100', 'text-green-800');
                    } else if (currentStatus === 'rejected') {
                        statusElement.classList.add('bg-red-100', 'text-red-800');
                    } else {
                        statusElement.classList.add('bg-yellow-100', 'text-yellow-800');
                    }

                    toggleModal('gcashReceiptModal', true);
                });
            });

            // Handle Payment Buttons (Approve/Reject) inside modal
            document.querySelectorAll('.update-payment-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const status = this.dataset.status;
                    const orderId = document.getElementById('order_id_input').value;

                    // Determine action text and colors
                    const action = status === 'verified' ? 'APPROVE' : 'REJECT';
                    const actionText = status === 'verified' ? 'Approve' : 'Reject';
                    const iconColor = status === 'verified' ? '#16a34a' : '#dc2626'; // green-600 : red-600
                    const confirmColor = status === 'verified' ? '#16a34a' : '#dc2626';
                    const cancelColor = '#78716c'; // stone-500

                    Swal.fire({
                        title: `${actionText} Payment Confirmation`,
                        html: `Are you sure you want to <strong>${actionText.toLowerCase()}</strong> this GCash payment?<br><br>
                              <span class="text-sm text-stone-500">Order ID: <span class="font-mono font-bold">${orderId}</span></span>`,
                        icon: 'warning',
                        iconColor: iconColor,
                        showCancelButton: true,
                        confirmButtonColor: confirmColor,
                        cancelButtonColor: cancelColor,
                        confirmButtonText: `Yes, ${actionText}`,
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                        backdrop: 'rgba(0, 0, 0, 0.3)',
                        allowOutsideClick: false,
                        customClass: {
                            popup: 'rounded-2xl shadow-2xl',
                            confirmButton: 'rounded-xl px-6 py-2 font-bold shadow-sm',
                            cancelButton: 'rounded-xl px-6 py-2 font-bold bg-stone-200 hover:bg-stone-300 text-stone-700'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('gcash_payment_status_input').value = status;
                            document.getElementById('gcashStatusForm').submit();

                            // Show loading state
                            Swal.fire({
                                title: 'Processing...',
                                text: `Updating payment status to ${status}`,
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        }
                    });
                });
            });

            // Close on outside click
            window.onclick = function (event) {
                if (event.target.classList.contains('fixed')) {
                    toggleModal(event.target.id, false);
                }
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Auto-refresh logic
        setTimeout(function () {
            window.location.reload();
        }, 60000); // Refresh every minute

        // Logout Confirmation
        const logoutForm = document.getElementById('logout-form');
        if (logoutForm) {
            logoutForm.addEventListener('submit', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Logout Confirmation',
                    html: `<div class="text-center">
                            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-sign-out-alt text-red-600 text-2xl"></i>
                            </div>
                            <p class="text-gray-700">Are you sure you want to logout from your rider account?</p>
                            <p class="text-sm text-gray-500 mt-1">You will be redirected to the login page.</p>
                        </div>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="fas fa-sign-out-alt mr-2"></i>Yes, Logout',
                    cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-6 py-3 font-medium',
                        cancelButton: 'rounded-xl px-6 py-3 font-medium'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        logoutForm.submit();
                    }
                });
            });
        }
    </script>
</body>

</html>