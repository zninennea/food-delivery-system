<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Reviews - NaNi Owner</title>
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
                            <p class="text-xs text-gray-500 -mt-1">Admin Dashboard</p>
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
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-shopping-cart mr-1"></i>Orders
                    </a>
                    <a href="{{ route('owner.analytics.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-chart-bar mr-1"></i>Analytics
                    </a>
                    <a href="{{ route('owner.reviews.index') }}"
                        class="text-orange-600 hover:text-orange-700 px-3 py-2 rounded-md text-sm font-medium">
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

    <div class="max-w-7xl mx-auto py-8 px-4">
        <div class="bg-white rounded-lg shadow p-6">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-orange-600">Customer Reviews</h1>
                <p class="text-gray-600">Track NaNi's customer feedback and ratings</p>
            </div>

            <!-- Reviews Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-orange-50 rounded-lg p-4 text-center">
                    <div class="text-4xl font-bold text-orange-600 mb-2">{{ number_format($averageRating, 1) }}</div>
                    <div class="flex items-center justify-center text-yellow-400 mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($averageRating))
                                <i class="fas fa-star"></i>
                            @elseif($i == floor($averageRating) + 1 && $averageRating - floor($averageRating) >= 0.5)
                                <i class="fas fa-star-half-alt"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <p class="text-gray-600">Average Rating</p>
                </div>

                <div class="bg-blue-50 rounded-lg p-4 text-center">
                    <div class="text-4xl font-bold text-blue-600 mb-2">{{ $totalReviews }}</div>
                    <p class="text-gray-600">Total Reviews</p>
                </div>

                <div class="bg-green-50 rounded-lg p-4 text-center">
                    <div class="text-4xl font-bold text-green-600 mb-2">
                        {{ $popularMenuItems->count() > 0 ? number_format($popularMenuItems->first()->reviews_avg_restaurant_rating ?? 0, 1) : '0.0' }}
                    </div>
                    <p class="text-gray-600">Top Rated Item</p>
                </div>

                <div class="bg-purple-50 rounded-lg p-4 text-center">
                    <div class="text-4xl font-bold text-purple-600 mb-2">
                        {{ $ratingDistribution->where('restaurant_rating', '>=', 4)->sum('count') }}
                    </div>
                    <p class="text-gray-600">Positive Reviews (4+ stars)</p>
                </div>
            </div>

            <!-- Rating Distribution -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Rating Distribution</h3>
                <div class="space-y-3">
                    @foreach($ratingDistribution as $distribution)
                        <div class="flex items-center">
                            <span class="text-sm font-medium text-gray-600 w-20">{{ $distribution->restaurant_rating }}
                                ★</span>
                            <div class="flex-1 bg-gray-200 rounded-full h-3 ml-4">
                                <div class="bg-yellow-400 h-3 rounded-full"
                                    style="width: {{ ($distribution->count / $totalReviews) * 100 }}%"></div>
                            </div>
                            <span class="text-sm text-gray-600 ml-4 w-16">{{ $distribution->count }} reviews</span>
                            <span
                                class="text-sm text-gray-500 ml-2">({{ number_format(($distribution->count / $totalReviews) * 100, 1) }}%)</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Popular Menu Items -->
            @if($popularMenuItems && $popularMenuItems->count() > 0)
                <div class="bg-gray-50 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Top Rated Menu Items</h3>
                    <div class="space-y-4">
                        @foreach($popularMenuItems as $index => $item)
                            <div class="flex items-center justify-between bg-white rounded-lg p-3">
                                <div class="flex items-center">
                                    <span
                                        class="w-8 h-8 bg-blue-100 text-blue-800 rounded-full flex items-center justify-center text-sm font-medium mr-4">
                                        {{ $index + 1 }}
                                    </span>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $item->name }}</h4>
                                        <div class="flex items-center">
                                            <div class="flex items-center text-yellow-400 mr-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i
                                                        class="fas fa-star{{ $i <= ($item->reviews_avg_restaurant_rating ?? 0) ? '' : '-o' }} text-xs"></i>
                                                @endfor
                                            </div>
                                            <span
                                                class="text-sm text-gray-600">{{ number_format($item->reviews_avg_restaurant_rating ?? 0, 1) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500">₱{{ number_format($item->price, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Reviews List -->
            <div class="space-y-6">
                @foreach($reviews as $review)
                    <div class="border border-gray-200 rounded-lg p-6 hover:bg-gray-50 transition duration-150">
                        <!-- Review Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center">
                                @if($review->customer->profile_picture)
                                    <img src="{{ asset('storage/' . $review->customer->profile_picture) }}"
                                        alt="{{ $review->customer->name }}" class="h-12 w-12 rounded-full object-cover mr-4">
                                @else
                                    <div class="h-12 w-12 bg-gray-200 rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-900">{{ $review->customer->name }}</p>
                                    <div class="flex items-center">
                                        <div class="flex items-center text-yellow-400 mr-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $review->restaurant_rating ? '' : '-o' }}"></i>
                                            @endfor
                                        </div>
                                        <span
                                            class="text-sm text-gray-500">{{ $review->created_at->format('M d, Y - h:i A') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                    Order #{{ $review->order->order_number }}
                                </span>
                                @if($review->rider_rating)
                                    <div class="mt-2 flex items-center justify-end">
                                        <span class="text-xs text-gray-500 mr-2">Rider:</span>
                                        <div class="flex items-center text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $review->rider_rating ? '' : '-o' }} text-xs"></i>
                                            @endfor
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Review Comment -->
                        @if($review->comment)
                            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                <p class="text-gray-700 italic">"{{ $review->comment }}"</p>
                            </div>
                        @else
                            <div class="text-gray-400 italic mb-4">No comment provided</div>
                        @endif

                        <!-- Ordered Items -->
                        @if($review->order && $review->order->items->count() > 0)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Ordered Items:</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    @foreach($review->order->items as $item)
                                        <div class="flex items-center justify-between text-sm bg-gray-50 rounded px-3 py-2">
                                            <span class="text-gray-700">{{ $item->quantity }}x {{ $item->menuItem->name }}</span>
                                            <span class="text-gray-500">₱{{ number_format($item->total, 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="flex justify-between items-center mt-3 text-sm">
                                    <span class="text-gray-600">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        Ordered: {{ $review->order->created_at->format('M d, Y') }}
                                    </span>
                                    <span class="font-medium text-gray-900">
                                        Total: ₱{{ number_format($review->order->total_amount, 2) }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($reviews->hasPages())
                <div class="mt-8">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        // Auto-expand long comments
        document.addEventListener('DOMContentLoaded', function () {
            const reviewComments = document.querySelectorAll('.review-comment');
            reviewComments.forEach(comment => {
                if (comment.scrollHeight > 80) {
                    const expandBtn = document.createElement('button');
                    expandBtn.className = 'text-blue-600 hover:text-blue-800 text-sm mt-2';
                    expandBtn.innerHTML = 'Read more <i class="fas fa-chevron-down ml-1"></i>';
                    expandBtn.addEventListener('click', function () {
                        comment.classList.toggle('expanded');
                        if (comment.classList.contains('expanded')) {
                            comment.style.maxHeight = 'none';
                            this.innerHTML = 'Read less <i class="fas fa-chevron-up ml-1"></i>';
                        } else {
                            comment.style.maxHeight = '80px';
                            this.innerHTML = 'Read more <i class="fas fa-chevron-down ml-1"></i>';
                        }
                    });
                    comment.parentNode.insertBefore(expandBtn, comment.nextSibling);
                    comment.style.maxHeight = '80px';
                    comment.style.overflow = 'hidden';
                    comment.classList.add('transition-all', 'duration-300');
                }
            });
        });
    </script>
</body>

</html>