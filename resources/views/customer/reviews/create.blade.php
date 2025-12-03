<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write Review - NaNi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .star-rating {
            direction: rtl;
            display: inline-block;
        }
        .star-rating input[type=radio] {
            display: none;
        }
        .star-rating label {
            color: #d3d3d3;
            font-size: 30px;
            padding: 0 3px;
            cursor: pointer;
            transition: color 0.3s;
        }
        .star-rating input[type=radio]:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #fbbf24;
        }
        .star-rating input[type=radio]:checked ~ label {
            color: #f59e0b;
        }
    </style>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <img src="{{ asset('images/nani-logo.png') }}" alt="NaNi Logo" class="h-10 w-10 mr-3">
                    <div>
                        <a href="/" class="text-xl font-bold text-gray-800">NaNi</a>
                        <p class="text-xs text-gray-500 -mt-1">Write Review</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('customer.dashboard') }}" class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <a href="{{ route('customer.orders.index') }}" class="text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-list mr-1"></i>My Orders
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-2xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Write a Review</h2>
                <p class="text-gray-600">Order #{{ $order->order_number }}</p>
            </div>

            <form action="{{ route('customer.reviews.store', $order) }}" method="POST" class="p-6">
                @csrf
                
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('info'))
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
                        {{ session('info') }}
                    </div>
                @endif

                <div class="space-y-6">
                    <!-- Order Summary -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="font-medium text-gray-900 mb-2">Order Details</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Order Number:</span>
                                <span class="font-medium">{{ $order->order_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Order Date:</span>
                                <span class="font-medium">{{ $order->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="font-medium text-green-600">{{ ucfirst($order->status) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Restaurant Rating -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Restaurant Rating <span class="text-red-500">*</span>
                        </label>
                        <div class="star-rating">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" id="restaurant_rating{{ $i }}" name="restaurant_rating" value="{{ $i }}" {{ old('restaurant_rating') == $i ? 'checked' : '' }}>
                                <label for="restaurant_rating{{ $i }}" title="{{ $i }} stars">
                                    <i class="fas fa-star"></i>
                                </label>
                            @endfor
                        </div>
                        @error('restaurant_rating')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rider Rating (if rider exists) -->
                    @if($order->rider)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Rider Rating (Optional)
                            <span class="text-sm text-gray-500">- {{ $order->rider->name }}</span>
                        </label>
                        <div class="star-rating">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" id="rider_rating{{ $i }}" name="rider_rating" value="{{ $i }}" {{ old('rider_rating') == $i ? 'checked' : '' }}>
                                <label for="rider_rating{{ $i }}" title="{{ $i }} stars">
                                    <i class="fas fa-star"></i>
                                </label>
                            @endfor
                        </div>
                        @error('rider_rating')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    @endif

                    <!-- Comment -->
                    <div>
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                            Your Comment (Optional)
                        </label>
                        <textarea 
                            name="comment" 
                            id="comment" 
                            rows="4"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Share your experience with the food and delivery..."
                        >{{ old('comment') }}</textarea>
                        @error('comment')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order Items -->
                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="font-medium text-gray-900 mb-3">Order Items</h3>
                        <div class="space-y-2">
                            @foreach($order->items as $item)
                            <div class="flex justify-between items-center py-2 border-b last:border-b-0">
                                <div>
                                    <p class="font-medium">{{ $item->menuItem->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $item->quantity }} × ₱{{ number_format($item->unit_price, 2) }}</p>
                                </div>
                                <p class="font-medium">₱{{ number_format($item->quantity * $item->unit_price, 2) }}</p>
                            </div>
                            @endforeach
                            <div class="flex justify-between font-bold mt-2 pt-2 border-t">
                                <span>Total Amount:</span>
                                <span>₱{{ number_format($order->total_amount + $order->delivery_fee, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('customer.orders.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
                        Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize star ratings
            const ratings = document.querySelectorAll('.star-rating');
            
            ratings.forEach(rating => {
                const stars = rating.querySelectorAll('input[type="radio"]');
                const labels = rating.querySelectorAll('label');
                
                stars.forEach((star, index) => {
                    star.addEventListener('change', function() {
                        // Update star colors
                        labels.forEach((label, labelIndex) => {
                            const icon = label.querySelector('i');
                            if (labelIndex >= index) {
                                icon.classList.add('text-yellow-400');
                                icon.classList.remove('text-gray-300');
                            }
                        });
                    });
                });
            });
            
            // Set default restaurant rating to 5 if none selected
            const restaurantRating = document.querySelector('input[name="restaurant_rating"]:checked');
            if (!restaurantRating) {
                document.getElementById('restaurant_rating5').checked = true;
            }
        });
    </script>
</body>
</html>