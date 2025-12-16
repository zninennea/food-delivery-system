<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - NaNi</title>
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
    <div data-user-role="{{ Auth::user()->role }}" style="display: none;"></div>

    <nav
        class="fixed w-full top-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('owner.dashboard') }}" class="flex-shrink-0 flex items-center gap-2 group">
                    <img src="{{ asset('images/NaNi_Logo.png') }}" alt="NaNi Logo"
                        class="h-20 w-auto group-hover:rotate-12 transition-transform duration-300">
                </a>

                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('owner.dashboard') }}"
                        class="text-orange-600 bg-orange-50 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-home mr-1"></i> Dashboard
                    </a>
                    <a href="{{ route('owner.orders.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
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

        <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 mb-8 fade-in relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-orange-50 rounded-bl-full -mr-16 -mt-16 opacity-50"></div>
            <div class="relative z-10">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Welcome Back, Admin</h1>
                <p class="text-stone-500">{{ $restaurant->name }} • {{ $restaurant->address }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 fade-in" style="animation-delay: 0.1s;">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-stone-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-orange-50 text-orange-600 rounded-xl">
                        <i class="fas fa-receipt text-xl"></i>
                    </div>
                    <span class="text-xs font-bold text-orange-600 bg-orange-50 px-2 py-1 rounded-lg">Today</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['today_orders'] }}</h3>
                <p class="text-sm text-stone-500">Orders Received</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-stone-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                        <i class="fas fa-fire text-xl"></i>
                    </div>
                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">Live</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['active_orders'] }}</h3>
                <p class="text-sm text-stone-500">Active Orders</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-stone-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-yellow-50 text-yellow-600 rounded-xl">
                        <i class="fas fa-bell text-xl"></i>
                    </div>
                    @if($stats['pending_orders'] > 0)
                        <span class="flex h-3 w-3 relative">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                        </span>
                    @endif
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['pending_orders'] }}</h3>
                <p class="text-sm text-stone-500">Pending Actions</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-stone-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                        <i class="fas fa-star text-xl"></i>
                    </div>
                    <span class="text-xs font-bold text-stone-400">{{ $stats['total_reviews'] }} Reviews</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($stats['average_rating'], 1) }}</h3>
                <p class="text-sm text-stone-500">Average Rating</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 fade-in" style="animation-delay: 0.2s;">
            <a href="{{ route('owner.menu.create') }}"
                class="group bg-gradient-to-br from-stone-900 to-stone-800 text-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all hover:-translate-y-1">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-lg mb-1">Add Menu Item</h3>
                        <p class="text-stone-400 text-sm">Create new dishes</p>
                    </div>
                    <div class="bg-white/10 p-2 rounded-lg group-hover:bg-white/20 transition-colors">
                        <i class="fas fa-plus"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('owner.analytics.index') }}"
                class="group bg-white border border-stone-200 p-6 rounded-2xl shadow-sm hover:border-orange-200 hover:shadow-md transition-all">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-gray-900 text-lg mb-1">View Analytics</h3>
                        <p class="text-stone-500 text-sm">Sales & performance</p>
                    </div>
                    <div class="text-stone-300 group-hover:text-orange-500 transition-colors">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('owner.riders.index') }}"
                class="group bg-white border border-stone-200 p-6 rounded-2xl shadow-sm hover:border-blue-200 hover:shadow-md transition-all">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-gray-900 text-lg mb-1">Manage Riders</h3>
                        <p class="text-stone-500 text-sm">View fleet status</p>
                    </div>
                    <div class="text-stone-300 group-hover:text-blue-500 transition-colors">
                        <i class="fas fa-motorcycle text-xl"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 fade-in" style="animation-delay: 0.3s;">

            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden">
                    <div class="p-6 border-b border-stone-100 flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-900 font-serif">Active Orders</h3>
                        <a href="{{ route('owner.orders.index') }}"
                            class="text-sm font-bold text-orange-600 hover:text-orange-700">View All</a>
                    </div>

                    @if($activeOrders->count() > 0)
                        <div class="divide-y divide-stone-100">
                            @foreach($activeOrders as $order)
                                <div class="p-6 hover:bg-stone-50 transition-colors">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <div class="flex items-center gap-3 mb-1">
                                                <span class="font-bold text-gray-900">#{{ $order->order_number }}</span>
                                                <span
                                                    class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide
                                                                                                                    @if($order->status == 'preparing') bg-blue-100 text-blue-700
                                                                                                                    @elseif($order->status == 'ready') bg-green-100 text-green-700
                                                                                                                    @else bg-gray-100 text-gray-700 @endif">
                                                    {{ $order->status }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-stone-500">{{ $order->created_at->diffForHumans() }} •
                                                {{ $order->items->count() }} items
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-gray-900">₱{{ number_format($order->total_amount, 2) }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-stone-100">
                                        <div class="flex items-center gap-2 text-sm text-stone-600">
                                            <i class="fas fa-user-circle text-stone-400"></i>
                                            {{ $order->customer->name }}
                                        </div>
                                        <div class="flex gap-2">
                                            @if(!$order->rider)
                                                <a href="{{ route('owner.orders.assign-rider-form', $order) }}"
                                                    class="px-4 py-2 bg-orange-50 text-orange-700 rounded-lg text-sm font-bold hover:bg-orange-100 transition-colors">
                                                    Assign Rider
                                                </a>
                                            @endif
                                            <a href="{{ route('owner.orders.show', $order) }}"
                                                class="px-4 py-2 bg-stone-900 text-white rounded-lg text-sm font-bold hover:bg-stone-800 transition-colors">
                                                View
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-12 text-center text-stone-400">
                            <i class="fas fa-clipboard-check text-4xl mb-3"></i>
                            <p>No active orders right now.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden">
                    <div class="p-6 border-b border-stone-100">
                        <h3 class="text-xl font-bold text-gray-900 font-serif">Recent Feedback</h3>
                    </div>

                    @if($recentReviews->count() > 0)
                        <div class="divide-y divide-stone-100">
                            @foreach($recentReviews as $review)
                                <div class="p-6 hover:bg-stone-50 transition-colors">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex items-center gap-2">
                                            <div class="font-bold text-sm text-gray-900">{{ $review->customer->name }}</div>
                                        </div>
                                        <div class="flex text-yellow-400 text-xs">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="fas fa-star{{ $i <= $review->restaurant_rating ? '' : '-o text-stone-200' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-sm text-stone-600 italic mb-2">"{{ Str::limit($review->comment, 60) }}"</p>
                                    <span class="text-xs text-stone-400">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="p-4 border-t border-stone-100">
                            <a href="{{ route('owner.reviews.index') }}"
                                class="block w-full text-center py-2 text-sm font-bold text-stone-600 hover:text-stone-900 hover:bg-stone-50 rounded-lg transition-colors">
                                View All Reviews
                            </a>
                        </div>
                    @else
                        <div class="p-12 text-center text-stone-400">
                            <i class="far fa-comment-alt text-4xl mb-3"></i>
                            <p>No reviews yet.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

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