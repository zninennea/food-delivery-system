<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $menuItem->name }} - NaNi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                    <a href="/menu" class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-utensils mr-1"></i>Menu
                    </a>
                    <a href="/login" class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-sign-in-alt mr-1"></i>Login
                    </a>
                    <a href="/register" class="bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600 text-sm font-medium">
                        <i class="fas fa-user-plus mr-1"></i>Register
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Item Detail -->
    <div class="max-w-6xl mx-auto py-8 px-4">
        <a href="/menu" class="inline-flex items-center text-orange-600 hover:text-orange-700 mb-6">
            <i class="fas fa-arrow-left mr-2"></i> Back to Menu
        </a>
        
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="md:flex">
                <!-- Item Image -->
                <div class="md:w-1/2">
                    @if($menuItem->image)
                        <img src="{{ asset('storage/' . $menuItem->image) }}" alt="{{ $menuItem->name }}"
                            class="w-full h-96 object-cover">
                    @else
                        <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-utensils text-gray-400 text-6xl"></i>
                        </div>
                    @endif
                </div>
                
                <!-- Item Details -->
                <div class="md:w-1/2 p-8">
                    <div class="mb-6">
                        <span class="inline-block px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-sm font-medium mb-4">
                            {{ $menuItem->category }}
                        </span>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $menuItem->name }}</h1>
                        <p class="text-2xl font-bold text-orange-600 mb-6">₱{{ number_format($menuItem->price, 2) }}</p>
                    </div>
                    
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-2">Description</h2>
                        <p class="text-gray-600 leading-relaxed">{{ $menuItem->description }}</p>
                    </div>
                    
                    <div class="mb-8">
                        <button onclick="showLoginAlert()"
                            class="w-full bg-orange-500 text-white py-3 px-6 rounded-lg hover:bg-orange-600 font-medium text-lg flex items-center justify-center">
                            <i class="fas fa-cart-plus mr-2"></i> Add to Cart (Login Required)
                        </button>
                        <p class="text-sm text-gray-500 mt-2 text-center">
                            You need to be logged in to add items to your cart
                        </p>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-clock mr-2"></i>
                            <span>Preparation time: 15-20 minutes</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Similar Items -->
        @if($similarItems->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">You might also like</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @foreach($similarItems as $similarItem)
                    <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-md transition-shadow">
                        @if($similarItem->image)
                            <img src="{{ asset('storage/' . $similarItem->image) }}" alt="{{ $similarItem->name }}"
                                class="w-full h-40 object-cover">
                        @else
                            <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-utensils text-gray-400 text-3xl"></i>
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900">{{ $similarItem->name }}</h3>
                            <p class="text-orange-600 font-bold mt-1">₱{{ number_format($similarItem->price, 2) }}</p>
                            <div class="mt-3">
                                <a href="{{ route('menu.item', $similarItem) }}"
                                    class="block text-center bg-gray-100 text-gray-700 py-2 rounded-md hover:bg-gray-200 text-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('images/nani-logo.png') }}" alt="NaNi Logo" class="h-16 w-16">
                </div>
                <h4 class="text-xl font-semibold mb-2">NaNi Japanese Restaurant</h4>
                <p class="text-gray-400 mb-1">JP Laurel Ave, Davao City</p>
                <p class="text-gray-400 mb-6">09194445566</p>
                <p class="text-gray-500 text-sm">&copy; 2024 NaNi Restaurant. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function showLoginAlert() {
            if(confirm('You need to login or register to add items to your cart. Would you like to login now?')) {
                window.location.href = '/login';
            }
        }
    </script>
</body>
</html>