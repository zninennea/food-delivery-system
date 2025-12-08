<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - NaNi</title>
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
                    <a href="{{ route('customer.menu') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-utensils mr-1"></i> Menu
                    </a>
                    <a href="{{ route('customer.orders.index') }}"
                        class="text-orange-600 bg-orange-50 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-list mr-1"></i> My Orders
                    </a>
                    <div class="ml-4 flex items-center space-x-3 border-l pl-4 border-gray-200">
                        <a href="{{ route('customer.profile.show') }}"
                            class="flex justify-center mb-0 transition-all duration-300 transform hover:-translate-y-1">
                            @if($profilePictureUrl)
                                <img src="{{ $profilePictureUrl }}" alt="Profile"
                                    class="w-12 h-12 rounded-full object-cover border-2 border-orange-600 shadow-sm">
                            @else
                                <div
                                    class="w-12 h-12 rounded-full bg-gradient-to-r from-orange-400 to-red-500 flex items-center justify-center text-white text-sm font-bold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors"
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

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 fade-in">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Order History</h1>
                <p class="text-stone-500">Track and review your past culinary experiences.</p>
            </div>
            <a href="{{ route('customer.menu') }}"
                class="inline-flex items-center px-6 py-3 bg-stone-900 text-white rounded-xl hover:bg-orange-600 transition-colors font-medium shadow-lg hover:shadow-orange-500/30 transform hover:-translate-y-0.5 transition-all">
                <i class="fas fa-plus mr-2"></i> New Order
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden fade-in"
            style="animation-delay: 0.1s;">
            @if($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-100">
                        <thead class="bg-stone-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                    Order Details</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                    Date</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                    Total</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-stone-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-stone-100">
                            @foreach($orders as $order)
                                <tr class="hover:bg-stone-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-10 w-10 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 font-bold text-xs">
                                                #{{ substr($order->order_number, -4) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">Order #{{ $order->order_number }}
                                                </div>
                                                <div class="text-xs text-stone-500 mt-0.5">
                                                    {{ $order->items->count() }} items •
                                                    {{ $order->items->first()->menuItem->name ?? 'Item' }}...
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $order->created_at->format('M j, Y') }}</div>
                                        <div class="text-xs text-stone-500">{{ $order->created_at->format('g:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">
                                            ₱{{ number_format($order->total_amount, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full uppercase tracking-wide
                                                                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                                                    @elseif($order->status == 'preparing') bg-blue-100 text-blue-800
                                                                    @elseif($order->status == 'ready') bg-purple-100 text-purple-800
                                                                    @elseif($order->status == 'on_the_way') bg-indigo-100 text-indigo-800
                                                                    @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                                                    @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                                                    @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if(in_array($order->status, ['pending', 'preparing', 'ready', 'on_the_way']))
                                            <a href="{{ route('customer.track-order', $order) }}"
                                                class="text-orange-600 hover:text-orange-900 font-bold bg-orange-50 px-4 py-2 rounded-lg hover:bg-orange-100 transition-colors">
                                                Track
                                            </a>
                                        @elseif($order->status == 'delivered')
                                            @php $hasReviewed = \App\Models\Review::where('order_id', $order->id)->exists(); @endphp
                                            @if(!$hasReviewed)
                                                <a href="{{ route('customer.reviews.create', $order) }}"
                                                    class="text-stone-600 hover:text-stone-900 hover:underline">
                                                    Rate Order
                                                </a>
                                            @else
                                                <span class="text-green-600 text-xs"><i class="fas fa-check"></i> Reviewed</span>
                                            @endif
                                        @else
                                            <span class="text-stone-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($orders->hasPages())
                    <div class="px-6 py-4 border-t border-stone-100 bg-stone-50">
                        {{ $orders->links() }}
                    </div>
                @endif

            @else
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-stone-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-receipt text-3xl text-stone-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2 font-serif">No orders yet</h3>
                    <p class="text-stone-500 mb-8 max-w-md mx-auto">It looks like you haven't placed any orders. Discover
                        our menu and treat yourself to something delicious.</p>
                    <a href="{{ route('customer.menu') }}"
                        class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-full font-bold shadow-lg hover:shadow-orange-500/30 transform hover:-translate-y-1 transition-all">
                        Browse Menu
                    </a>
                </div>
            @endif
        </div>
    </div>
</body>

</html>