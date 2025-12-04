<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Reviews - NaNi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <!-- Navigation (same as other pages) -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <img src="{{ asset('images/nani-logo.png') }}" alt="NaNi Logo" class="h-10 w-10 mr-3">
                    <div>
                        <a href="/" class="text-xl font-bold text-gray-800">NaNi</a>
                        <p class="text-xs text-gray-500 -mt-1">Customer Reviews</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('customer.dashboard') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <a href="{{ route('customer.orders.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-list mr-1"></i>My Orders
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
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Customer Reviews</h1>
            </div>

            <!-- Reviews Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
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

                <div class="bg-green-50 rounded-lg p-4">
                    <p class="font-medium text-gray-900 mb-2">Rating Distribution</p>
                    @foreach($ratingDistribution as $distribution)
                        <div class="flex items-center mb-1">
                            <span class="text-sm text-gray-600 w-8">{{ $distribution->restaurant_rating }}★</span>
                            <div class="flex-1 bg-gray-200 rounded-full h-2 ml-2">
                                <div class="bg-yellow-400 h-2 rounded-full"
                                    style="width: {{ ($distribution->count / $totalReviews) * 100 }}%"></div>
                            </div>
                            <span class="text-sm text-gray-600 ml-2 w-8">{{ $distribution->count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Reviews List -->
            <div class="space-y-6">
                @foreach($reviews as $review)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition duration-150">
                        <!-- Review Header with customer info -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center">
                                <!-- Customer Avatar -->
                                @if($review->customer->profile_picture)
                                    <img src="{{ asset('storage/' . $review->customer->profile_picture) }}"
                                        alt="{{ $review->customer->name }}" class="h-10 w-10 rounded-full object-cover mr-3">
                                @else
                                    <div class="h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-900">{{ $review->customer->name }}</p>
                                    <div class="flex items-center">
                                        <div class="flex items-center text-yellow-400 mr-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="fas fa-star{{ $i <= $review->restaurant_rating ? '' : '-o' }} text-sm"></i>
                                            @endfor
                                        </div>
                                        <span
                                            class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                Order #{{ $review->order->order_number }}
                            </span>
                        </div>

                        <!-- Review Comment -->
                        @if($review->comment)
                            <p class="text-gray-700 mb-3">"{{ $review->comment }}"</p>
                        @else
                            <p class="text-gray-500 italic mb-3">No comment provided</p>
                        @endif

                        <!-- Ordered Items -->
                        @if($review->order && $review->order->items->count() > 0)
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <p class="text-sm font-medium text-gray-700 mb-2">Ordered:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($review->order->items as $item)
                                        <div class="flex items-center text-sm bg-gray-100 rounded-full px-3 py-1">
                                            <span class="text-gray-700">{{ $item->quantity }}x {{ $item->menuItem->name }}</span>
                                            <span class="text-gray-500 ml-1">• ₱{{ number_format($item->unit_price, 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="flex justify-between items-center mt-3 text-sm">
                                    <span class="text-gray-600">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        {{ $review->order->created_at->format('M d, Y') }}
                                    </span>
                                    <span class="font-medium text-gray-900">
                                        Total: ₱{{ number_format($review->order->total_amount, 2) }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        <!-- Rider Rating (if exists) -->
                        @if($review->rider_rating)
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <div class="flex items-center">
                                    <i class="fas fa-motorcycle text-gray-400 mr-2"></i>
                                    <span class="text-sm text-gray-600 mr-2">Rider:</span>
                                    <div class="flex items-center text-yellow-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= $review->rider_rating ? '' : '-o' }} text-xs"></i>
                                        @endfor
                                    </div>
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
</body>

</html>