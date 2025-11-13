<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - NaNi Owner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                                Items
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total
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
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status == 'preparing') bg-blue-100 text-blue-800
                                            @elseif($order->status == 'ready') bg-green-100 text-green-800
                                            @elseif($order->status == 'on_the_way') bg-purple-100 text-purple-800
                                            @elseif($order->status == 'delivered') bg-gray-100 text-gray-800
                                            @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $order->created_at->format('M j, Y g:i A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('owner.orders.show', $order) }}"
                                        class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                    <button class="text-green-600 hover:text-green-900 update-status"
                                        data-order-id="{{ $order->id }}">Update Status</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
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
                    </select>
                    <div class="flex justify-end space-x-3 mt-4">
                        <button type="button" onclick="closeModal()"
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const updateButtons = document.querySelectorAll('.update-status');
            const modal = document.getElementById('statusModal');
            const form = document.getElementById('statusForm');

            updateButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const orderId = this.getAttribute('data-order-id');
                    form.action = `/owner/orders/${orderId}/status`;
                    modal.classList.remove('hidden');
                });
            });
        });

        function closeModal() {
            document.getElementById('statusModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        window.onclick = function (event) {
            const modal = document.getElementById('statusModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>

</html>