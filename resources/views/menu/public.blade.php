<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - NaNi Japanese Restaurant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .category-anchor {
            scroll-margin-top: 80px;
        }

        .sticky-categories {
            position: sticky;
            top: 64px;
            z-index: 40;
            background-color: white;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/">
                        <img src="{{ asset('images/nani-logo.png') }}" alt="NaNi Logo" class="h-10 w-10 mr-3">
                    </a>
                    <div>
                        <a href="/" class="text-xl font-bold text-gray-800">NaNi</a>
                        <p class="text-xs text-gray-500 -mt-1">Menu</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/" class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <a href="/login"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-sign-in-alt mr-1"></i>Login
                    </a>
                    <a href="/register"
                        class="bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600 text-sm font-medium">
                        <i class="fas fa-user-plus mr-1"></i>Register
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-orange-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-4">NaNi Japanese Restaurant Menu</h1>
            <p class="text-xl mb-6">Authentic Japanese Cuisine - Dine in or Takeout</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="#featured" class="bg-white text-orange-600 px-6 py-2 rounded-lg font-medium hover:bg-gray-100">
                    Featured Items
                </a>
                <a href="#categories"
                    class="bg-transparent border-2 border-white text-white px-6 py-2 rounded-lg font-medium hover:bg-white hover:text-orange-600">
                    All Categories
                </a>
            </div>
        </div>
    </div>

    <!-- Restaurant Info -->
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $restaurant->name }}</h2>
                    <p class="text-gray-600 mt-1">{{ $restaurant->address }}</p>
                    <p class="text-gray-600">{{ $restaurant->phone }}</p>
                </div>
                <div class="text-right">
                    <div class="flex items-center text-yellow-400 mb-2">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <span class="text-gray-600 ml-2">4.5 (128 reviews)</span>
                    </div>
                    <p class="text-gray-600">Open until 10:00 PM</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Items -->
    <div class="max-w-7xl mx-auto px-4 py-6" id="featured">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">Featured Items</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($featuredItems as $item)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                            class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-utensils text-gray-400 text-4xl"></i>
                        </div>
                    @endif
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $item->name }}</h3>
                        <p class="text-gray-600 text-sm mt-1">{{ Str::limit($item->description, 80) }}</p>
                        <div class="flex justify-between items-center mt-3">
                            <span class="text-lg font-bold text-orange-600">₱{{ number_format($item->price, 2) }}</span>
                            <a href="{{ route('menu.item', $item) }}"
                                class="bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600 text-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Categories Navigation -->
    <div class="sticky-categories bg-gray-100 border-t border-b border-gray-200 py-3" id="categories">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex overflow-x-auto space-x-4 pb-2">
                @foreach($menuItemsByCategory as $category => $items)
                    <a href="#category-{{ Str::slug($category) }}"
                        class="whitespace-nowrap bg-white px-4 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-orange-50 hover:text-orange-600 border border-gray-300">
                        {{ $category }} ({{ count($items) }})
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- All Menu Items by Category -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        @foreach($menuItemsByCategory as $category => $items)
            <div class="mb-12 category-anchor" id="category-{{ Str::slug($category) }}">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $category }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($items as $item)
                        <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-md transition-shadow">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                                    class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-utensils text-gray-400 text-4xl"></i>
                                </div>
                            @endif
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $item->name }}</h3>
                                <p class="text-gray-600 text-sm mt-1">{{ Str::limit($item->description, 100) }}</p>
                                <div class="flex justify-between items-center mt-4">
                                    <span class="text-xl font-bold text-orange-600">₱{{ number_format($item->price, 2) }}</span>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('menu.item', $item) }}"
                                            class="bg-gray-200 text-gray-700 px-3 py-2 rounded-md hover:bg-gray-300 text-sm">
                                            <i class="fas fa-eye mr-1"></i> View
                                        </a>
                                        <button onclick="showLoginAlert()"
                                            class="bg-orange-500 text-white px-3 py-2 rounded-md hover:bg-orange-600 text-sm">
                                            <i class="fas fa-cart-plus mr-1"></i> Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- CTA Section -->
    <div class="bg-orange-50 border-t border-orange-100 py-12">
        <div class="max-w-4xl mx-auto text-center px-4">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Ready to Order?</h2>
            <p class="text-lg text-gray-600 mb-6">Create an account to start ordering delicious Japanese cuisine!</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register"
                    class="bg-orange-500 text-white px-8 py-3 rounded-lg font-medium hover:bg-orange-600 shadow-lg">
                    <i class="fas fa-user-plus mr-2"></i>Create Account
                </a>
                <a href="/login"
                    class="border-2 border-orange-500 text-orange-500 px-8 py-3 rounded-lg font-medium hover:bg-orange-500 hover:text-white">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login to Order
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('images/nani-logo.png') }}" alt="NaNi Logo" class="h-16 w-16">
                </div>
                <h4 class="text-xl font-semibold mb-2">NaNi Japanese Restaurant</h4>
                <p class="text-gray-400 mb-1">JP Laurel Ave, Davao City</p>
                <p class="text-gray-400 mb-6">09194445566</p>
                <div class="flex justify-center space-x-6 mb-6">
                    <a href="{{ $restaurant->facebook_url ?? '#' }}" class="text-gray-400 hover:text-white">
                        <i class="fab fa-facebook text-2xl"></i>
                    </a>
                </div>
                <p class="text-gray-500 text-sm">&copy; 2024 NaNi Restaurant. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function showLoginAlert() {
            if (confirm('You need to login or register to add items to your cart. Would you like to login now?')) {
                window.location.href = '/login';
            }
        }

        // Smooth scrolling for category anchors
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>

</html>