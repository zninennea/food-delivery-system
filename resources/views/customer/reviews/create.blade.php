<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write Review - NaNi</title>
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

        /* Star Rating Logic */
        .rate-area {
            float: left;
            border-style: none;
        }

        .rate-area:not(:checked)>input {
            position: absolute;
            top: -9999px;
            clip: rect(0, 0, 0, 0);
        }

        .rate-area:not(:checked)>label {
            float: right;
            width: 1em;
            overflow: hidden;
            white-space: nowrap;
            cursor: pointer;
            font-size: 30px;
            color: #d1d5db;
        }

        .rate-area:not(:checked)>label:before {
            content: '★ ';
        }

        .rate-area>input:checked~label {
            color: #f59e0b;
        }

        .rate-area:not(:checked)>label:hover,
        .rate-area:not(:checked)>label:hover~label {
            color: #fbbf24;
        }

        .rate-area>input:checked+label:hover,
        .rate-area>input:checked+label:hover~label,
        .rate-area>input:checked~label:hover,
        .rate-area>input:checked~label:hover~label,
        .rate-area>label:hover~input:checked~label {
            color: #f59e0b;
        }
    </style>
</head>

<body class="bg-stone-50 text-gray-800 antialiased">

    <nav
        class="fixed w-full top-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('customer.dashboard') }}" class="flex-shrink-0 flex items-center gap-2 group">
                    <img src="{{ asset('images/NaNi_Logo.png') }}" alt="NaNi Logo"
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

    <div class="pt-32 pb-16 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-3xl shadow-xl border border-stone-100 overflow-hidden fade-in">
            <div class="bg-stone-900 text-white px-8 py-6">
                <h2 class="text-2xl font-bold font-serif mb-1">Share your Experience</h2>
                <p class="text-stone-400 text-sm">Review for Order #{{ $order->order_number }}</p>
            </div>

            <form action="{{ route('customer.reviews.store', $order) }}" method="POST" class="p-8">
                @csrf

                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-r-lg mb-6 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="space-y-8">

                    <div
                        class="bg-stone-50 rounded-xl p-5 border border-stone-100 flex justify-between items-center text-sm">
                        <div>
                            <p class="text-stone-500 text-xs uppercase tracking-wider font-bold mb-1">Order Date</p>
                            <p class="font-medium text-gray-900">{{ $order->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-stone-500 text-xs uppercase tracking-wider font-bold mb-1">Status</p>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">
                            Rate the Food & Restaurant <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-4 bg-white border border-gray-200 rounded-xl p-4">
                            <div class="rate-area">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="restaurant_rating{{ $i }}" name="restaurant_rating"
                                        value="{{ $i }}" {{ old('restaurant_rating') == $i ? 'checked' : '' }} />
                                    <label for="restaurant_rating{{ $i }}" title="{{ $i }} stars"></label>
                                @endfor
                            </div>
                            <span class="text-xs text-gray-400 italic mt-2">Click to rate</span>
                        </div>
                        @error('restaurant_rating')
                            <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    @if($order->rider)
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">
                                Rate your Rider <span class="font-normal text-gray-400 text-xs ml-1">(Optional)</span>
                            </label>
                            <div class="flex items-center gap-4 bg-white border border-gray-200 rounded-xl p-4">
                                <div class="flex items-center gap-3 mr-4 border-r border-gray-100 pr-4">
                                    <div
                                        class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 text-xs font-bold">
                                        {{ substr($order->rider->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">{{ $order->rider->name }}</span>
                                </div>
                                <div class="rate-area">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="rider_rating{{ $i }}" name="rider_rating" value="{{ $i }}" {{ old('rider_rating') == $i ? 'checked' : '' }} />
                                        <label for="rider_rating{{ $i }}" title="{{ $i }} stars"></label>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    @endif

                    <div>
                        <label for="comment" class="block text-sm font-bold text-gray-900 mb-2">
                            Detailed Feedback <span class="font-normal text-gray-400 text-xs ml-1">(Optional)</span>
                        </label>
                        <textarea name="comment" id="comment" rows="4"
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm resize-none transition-shadow"
                            placeholder="Tell us about the taste, portion size, or delivery experience...">{{ old('comment') }}</textarea>
                    </div>

                    <div class="border-t border-gray-100 pt-6">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Items Ordered</p>
                        <div class="space-y-3">
                            @foreach($order->items as $item)
                                <div class="flex justify-between items-center text-sm">
                                    <div class="flex items-center gap-2">
                                        <span class="text-orange-600 font-bold">{{ $item->quantity }}x</span>
                                        <span class="text-gray-700">{{ $item->menuItem->name }}</span>
                                    </div>
                                    <span
                                        class="text-gray-500">₱{{ number_format($item->quantity * $item->unit_price, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                <div class="mt-8 flex items-center gap-4 border-t border-gray-100 pt-6">
                    <button type="submit"
                        class="flex-1 bg-gradient-to-r from-orange-600 to-red-600 text-white py-3 rounded-xl font-bold shadow-lg hover:shadow-orange-500/30 transform hover:-translate-y-0.5 transition-all">
                        Submit Review
                    </button>
                    <a href="{{ route('customer.orders.index') }}"
                        class="px-6 py-3 border border-gray-200 text-gray-600 font-medium rounded-xl hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>