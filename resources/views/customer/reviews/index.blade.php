<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Reviews - NaNi</title>
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
                    <a href="{{ route('customer.orders.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-list mr-1"></i> My Orders
                    </a>
                    <div class="ml-4 flex items-center space-x-3 border-l pl-4 border-gray-200">
                        <a href="{{ route('customer.profile.show') }}"
                            class="text-gray-600 hover:text-orange-600 transition-colors">
                            <i class="fas fa-user-circle text-xl"></i>
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

        <div class="text-center mb-12 fade-in">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Community Reviews</h1>
            <p class="text-stone-500">See what others are saying about our dishes</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 fade-in">
            <div
                class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6 flex flex-col items-center justify-center">
                <div class="text-5xl font-bold text-gray-900 mb-2 font-serif">{{ number_format($averageRating, 1) }}
                </div>
                <div class="flex items-center gap-1 text-yellow-400 mb-2 text-lg">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($averageRating))
                            <i class="fas fa-star"></i>
                        @elseif($i == floor($averageRating) + 1 && $averageRating - floor($averageRating) >= 0.5)
                            <i class="fas fa-star-half-alt"></i>
                        @else
                            <i class="far fa-star text-gray-300"></i>
                        @endif
                    @endfor
                </div>
                <p class="text-sm text-stone-500 font-medium uppercase tracking-wide">Average Rating</p>
            </div>

            <div
                class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6 flex flex-col items-center justify-center">
                <div
                    class="w-16 h-16 bg-orange-50 rounded-full flex items-center justify-center text-orange-600 text-2xl mb-4">
                    <i class="fas fa-comment-alt"></i>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ $totalReviews }}</div>
                <p class="text-sm text-stone-500 font-medium uppercase tracking-wide">Total Reviews</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6">
                <p class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-4">Rating Breakdown</p>
                <div class="space-y-3">
                    @foreach($ratingDistribution as $dist)
                        <div class="flex items-center gap-3 text-sm">
                            <span class="font-medium text-gray-600 w-6">{{ $dist->restaurant_rating }} <i
                                    class="fas fa-star text-xs text-yellow-400"></i></span>
                            <div class="flex-1 h-2 bg-stone-100 rounded-full overflow-hidden">
                                <div class="h-full bg-yellow-400 rounded-full"
                                    style="width: {{ ($dist->count / $totalReviews) * 100 }}%"></div>
                            </div>
                            <span class="text-stone-400 w-8 text-right">{{ $dist->count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 fade-in" style="animation-delay: 0.1s;">
            @foreach($reviews as $review)
                <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6 hover:shadow-md transition-shadow">

                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            @if($review->customer->profile_picture)
                                <img src="{{ asset('storage/' . $review->customer->profile_picture) }}"
                                    alt="{{ $review->customer->name }}"
                                    class="w-10 h-10 rounded-full object-cover border border-stone-100">
                            @else
                                <div
                                    class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 font-bold">
                                    {{ substr($review->customer->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <p class="font-bold text-gray-900 text-sm">{{ $review->customer->name }}</p>
                                <p class="text-xs text-stone-400">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex text-yellow-400 text-sm">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $review->restaurant_rating ? '' : '-o text-gray-200' }}"></i>
                            @endfor
                        </div>
                    </div>

                    @if($review->comment)
                        <p class="text-gray-600 text-sm leading-relaxed mb-4">"{{ $review->comment }}"</p>
                    @else
                        <p class="text-stone-400 text-sm italic mb-4">No written review.</p>
                    @endif

                    @if($review->order && $review->order->items->count() > 0)
                        <div class="bg-stone-50 rounded-xl p-3 border border-stone-100">
                            <p class="text-xs font-bold text-stone-400 uppercase tracking-wide mb-2">Ordered</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($review->order->items->take(3) as $item)
                                    <span
                                        class="px-2 py-1 bg-white border border-stone-200 rounded-md text-xs text-stone-600 font-medium">
                                        {{ $item->quantity }}x {{ $item->menuItem->name }}
                                    </span>
                                @endforeach
                                @if($review->order->items->count() > 3)
                                    <span class="px-2 py-1 text-xs text-stone-400">+{{ $review->order->items->count() - 3 }}
                                        more</span>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($review->rider_rating && $review->rider)
                        <div class="mt-4 pt-4 border-t border-stone-100 flex items-center gap-2 text-xs text-stone-500">
                            <i class="fas fa-motorcycle"></i>
                            <span>Rider rated:</span>
                            <span class="font-bold text-gray-900 flex items-center gap-1">
                                {{ $review->rider_rating }} <i class="fas fa-star text-yellow-400 text-[10px]"></i>
                            </span>
                        </div>
                    @endif

                </div>
            @endforeach
        </div>

        @if($reviews->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $reviews->links() }}
            </div>
        @endif

    </div>
</body>

</html>