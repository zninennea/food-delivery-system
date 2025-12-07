<!DOCTYPE html>
<html lang="en"class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - NaNi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5 { font-family: 'Playfair Display', serif; }

        .fade-in { animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; transform: translateY(20px); }
        @keyframes fadeIn { to { opacity: 1; transform: translateY(0); } }

        .hover-card { transition: all 0.3s ease; }
        .hover-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    </style>
</head>

<body class="bg-stone-50 text-gray-800 antialiased">
    <div data-user-role="{{ Auth::user()->role }}" style="display: none;"></div>

    <nav class="fixed w-full top-0 z-50 transition-all duration-300 bg-white/100 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('customer.dashboard') }}" class="flex-shrink-0 flex items-center gap-2 group">
                    <img src="https://i.imgur.com/vPOu1H2.png" alt="NaNi Text" class="h-20 w-auto group-hover:rotate-12 transition-transform duration-300">
                </a>

                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('customer.dashboard') }}" class="text-orange-600 bg-orange-50 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-home mr-1"></i> Home
                    </a>
                    <a href="{{ route('customer.menu') }}" class="text-gray-600 hover:text-orange-600 hover:bg-gray-50 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-utensils mr-1"></i> Menu
                    </a>
                    <a href="{{ route('customer.cart.index') }}" class="text-gray-600 hover:text-orange-600 hover:bg-gray-50 px-3 py-2 rounded-lg text-sm font-medium transition-colors relative">
                        <i class="fas fa-shopping-cart mr-1"></i> Cart
                        @if($cartCount > 0)
                            <span class="absolute top-0 right-0 -mt-1 -mr-1 px-1.5 py-0.5 bg-red-500 text-white text-xs font-bold rounded-full animate-pulse">{{ $cartCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('customer.orders.index') }}" class="text-gray-600 hover:text-orange-600 hover:bg-gray-50 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-list mr-1"></i> My Orders
                    </a>
                    
                    <div class="ml-4 flex items-center space-x-3 border-l pl-4 border-gray-200">
                        <a href="{{ route('customer.profile.show') }}" class="text-gray-600 hover:text-orange-600 transition-colors">
                            <i class="fas fa-user-circle text-xl"></i>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Logout">
                                <i class="fas fa-sign-out-alt text-lg"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="relative pt-20 pb-32 flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1579027989536-b7b1f875659b?q=80&w=2070&auto=format&fit=crop')] bg-cover bg-center"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-stone-50"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 text-center mt-10 fade-in">
            <span class="text-orange-400 font-bold tracking-[0.2em] uppercase text-xs sm:text-sm mb-2 block">Premium Japanese Dining</span>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 drop-shadow-lg">
                Welcome back, {{ Auth::user()->name }}
            </h1>
            <p class="text-xl text-gray-200 mb-8 font-light italic">"Authentic flavors delivered to your doorstep"</p>
            
            <a href="{{ route('customer.menu') }}" class="group relative inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-full font-bold overflow-hidden shadow-lg hover:shadow-orange-500/30 transition-all hover:-translate-y-1">
                <span>Order Now</span>
                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>

    <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 pb-12">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 fade-in">
            <a href="{{ route('customer.menu') }}" class="bg-white rounded-2xl p-6 shadow-xl shadow-black/5 hover-card border border-stone-100 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-orange-50 rounded-xl group-hover:bg-orange-500 transition-colors duration-300">
                        <i class="fas fa-utensils text-2xl text-orange-500 group-hover:text-white transition-colors"></i>
                    </div>
                    <i class="fas fa-chevron-right text-gray-300 group-hover:text-orange-500 transition-colors"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Browse Menu</h3>
                <p class="text-sm text-gray-500 mt-1">Explore our sushi, ramen, and more.</p>
            </a>

            <a href="{{ route('customer.orders.index') }}" class="bg-white rounded-2xl p-6 shadow-xl shadow-black/5 hover-card border border-stone-100 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-50 rounded-xl group-hover:bg-blue-500 transition-colors duration-300">
                        <i class="fas fa-clock text-2xl text-blue-500 group-hover:text-white transition-colors"></i>
                    </div>
                    <i class="fas fa-chevron-right text-gray-300 group-hover:text-blue-500 transition-colors"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Track Orders</h3>
                <p class="text-sm text-gray-500 mt-1">Check the status of your deliveries.</p>
            </a>

            <a href="{{ route('customer.profile.show') }}" class="bg-white rounded-2xl p-6 shadow-xl shadow-black/5 hover-card border border-stone-100 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-green-50 rounded-xl group-hover:bg-green-500 transition-colors duration-300">
                        <i class="fas fa-user text-2xl text-green-500 group-hover:text-white transition-colors"></i>
                    </div>
                    <i class="fas fa-chevron-right text-gray-300 group-hover:text-green-500 transition-colors"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">My Profile</h3>
                <p class="text-sm text-gray-500 mt-1">Update your details and preferences.</p>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-white rounded-2xl shadow-sm border border-stone-100 overflow-hidden">
                    <div class="p-6 sm:p-8">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">{{ $restaurant->name }}</h2>
                                <div class="flex items-center gap-2 text-gray-500 mt-2 text-sm">
                                    <i class="fas fa-map-marker-alt text-orange-500"></i>
                                    <span>{{ $restaurant->address }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-500 mt-1 text-sm">
                                    <i class="fas fa-phone-alt text-orange-500"></i>
                                    <span>{{ $restaurant->phone }}</span>
                                </div>
                            </div>
                            
                            <div class="text-left sm:text-right bg-stone-50 p-4 rounded-xl">
                                <div class="flex items-center text-yellow-400 mb-1">
                                    @php
                                        $averageRating = $averageRating ?? 4.5;
                                        $totalReviews = $totalReviews ?? 128;
                                    @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= floor($averageRating) ? '' : ($i == ceil($averageRating) ? '-half-alt' : '-o') }}"></i>
                                    @endfor
                                    <span class="text-gray-900 font-bold ml-2">{{ number_format($averageRating, 1) }}</span>
                                </div>
                                <p class="text-xs text-gray-500">{{ $totalReviews }} Reviews</p>
                                <p class="text-xs text-green-600 font-medium mt-1"><i class="fas fa-door-open mr-1"></i> Open until 10:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">Featured Specialties</h3>
                        <a href="{{ route('customer.menu') }}" class="text-orange-600 hover:text-orange-700 text-sm font-medium">View All <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        @foreach($featuredItems as $item)
                            <div class="bg-white rounded-2xl shadow-sm border border-stone-100 overflow-hidden hover-card group">
                                <div class="relative h-48 overflow-hidden">
                                    @if($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full bg-stone-100 flex items-center justify-center">
                                            <i class="fas fa-utensils text-stone-300 text-4xl"></i>
                                        </div>
                                    @endif
                                    <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full shadow-sm">
                                        <span class="text-orange-600 font-bold text-sm">₱{{ number_format($item->price, 2) }}</span>
                                    </div>
                                </div>
                                <div class="p-5">
                                    <h4 class="text-lg font-bold text-gray-900 mb-1">{{ $item->name }}</h4>
                                    <p class="text-gray-500 text-sm line-clamp-2 mb-4">{{ $item->description }}</p>
                                    <a href="{{ route('customer.menu-item', $item) }}" class="block w-full py-2 bg-stone-100 text-stone-600 text-center rounded-lg hover:bg-orange-600 hover:text-white transition-colors text-sm font-medium">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($recentOrders->count() > 0)
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Recent Orders</h3>
                        <div class="space-y-4">
                            @foreach($recentOrders as $order)
                                <div class="bg-white rounded-xl p-5 border-l-4 {{ $order->status == 'delivered' ? 'border-green-500' : ($order->status == 'pending' ? 'border-yellow-500' : 'border-blue-500') }} shadow-sm">
                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                        <div>
                                            <div class="flex items-center gap-3 mb-1">
                                                <h4 class="font-bold text-gray-900">Order #{{ $order->order_number }}</h4>
                                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide
                                                    {{ $order->status == 'delivered' ? 'bg-green-100 text-green-700' : ($order->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700') }}">
                                                    {{ $order->status }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-500">{{ $order->created_at->format('M j, Y • g:i A') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-gray-900">₱{{ number_format($order->total_amount, 2) }}</p>
                                            <div class="flex gap-3 mt-1 text-sm">
                                                <a href="{{ route('customer.track-order', $order) }}" class="text-orange-600 hover:underline">Track</a>
                                                @if($order->status == 'delivered')
                                                    <a href="{{ route('customer.reviews.create', $order) }}" class="text-gray-500 hover:text-gray-900">Review</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6 sticky top-24">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Latest Reviews</h3>
                        <a href="{{ route('customer.reviews.index') }}" class="text-xs font-bold text-orange-600 uppercase hover:underline">View All</a>
                    </div>

                    @if($recentReviews->count() > 0)
                        <div class="space-y-6">
                            @foreach($recentReviews as $review)
                                <div class="border-b border-gray-100 last:border-0 pb-6 last:pb-0">
                                    <div class="flex items-center gap-3 mb-3">
                                        @if($review->customer->profile_picture)
                                            <img src="{{ asset('storage/' . $review->customer->profile_picture) }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
                                        @else 
                                        <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-500 font-bold text-sm">
                                            {{ substr($review->customer->name, 0, 1) }}
                                        </div>
                                            </ul>
                                        @endif
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">{{ $review->customer->name }}</p>
                                            <div class="flex text-yellow-400 text-xs">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star{{ $i <= $review->restaurant_rating ? '' : '-o' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        <span class="ml-auto text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>

                                    @if($review->comment)
                                        <p class="text-sm text-gray-600 italic bg-stone-50 p-3 rounded-lg mb-2">"{{ $review->comment }}"</p>
                                    @endif

                                    @if($review->order && $review->order->items->count() > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($review->order->items->take(2) as $item)
                                                <span class="px-2 py-0.5 bg-stone-100 text-stone-500 text-[10px] rounded-full">{{ $item->menuItem->name }}</span>
                                            @endforeach
                                            @if($review->order->items->count() > 2)
                                                <span class="px-2 py-0.5 bg-stone-100 text-stone-500 text-[10px] rounded-full">+{{ $review->order->items->count() - 2 }} more</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-400">
                            <i class="far fa-comment-dots text-4xl mb-2 opacity-50"></i>
                            <p class="text-sm">No reviews yet.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <footer class="bg-stone-900 text-white py-12 border-t border-stone-800">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="flex justify-center items-center gap-2 mb-6 opacity-80">
                <a href="/" class="flex-shrink-0 flex items-center gap-2 group">
                    <img src="https://i.imgur.com/vPOu1H2.png" alt="NaNi Icon" class="h-20 w-auto group-hover:rotate-12 transition-transform duration-300">
                </a>
            </div>
            <p class="text-stone-400 text-sm max-w-md mx-auto mb-6">Bringing the authentic flavors of Japan to the heart of Davao City. Dedicated to freshness, quality, and tradition.</p>
            
            <div class="flex justify-center gap-6 mb-8">
                <a href="#" class="text-stone-500 hover:text-white transition-colors"><i class="fab fa-facebook-f text-lg"></i></a>
                <a href="#" class="text-stone-500 hover:text-white transition-colors"><i class="fab fa-instagram text-lg"></i></a>
                <a href="#" class="text-stone-500 hover:text-white transition-colors"><i class="fab fa-twitter text-lg"></i></a>
            </div>
            
            <p class="text-stone-600 text-xs">&copy; {{ date('Y') }} {{ $restaurant->name ?? 'NaNi Restaurant' }}. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
        });
    </script>
</body>
</html>