<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $menuItem->name }} - NaNi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <img src="{{ asset('images/nani-logo.png') }}" alt="NaNi Logo" class="h-10 w-10 mr-3">
                    <div>
                        <a href="/" class="text-xl font-bold text-gray-800">NaNi</a>
                        <p class="text-xs text-gray-500 -mt-1">{{ $menuItem->name }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('customer.dashboard') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <a href="{{ route('customer.menu') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-utensils mr-1"></i>Menu
                    </a>
                    <a href="{{ route('customer.cart.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-shopping-cart mr-1"></i>Cart ({{ $cartCount }})
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

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="md:flex">
                <!-- Menu Item Image -->
                <div class="md:w-1/2">
                    @if($menuItem->image)
                        <img src="{{ asset('storage/' . $menuItem->image) }}" alt="{{ $menuItem->name }}"
                            class="w-full h-64 md:h-full object-cover">
                    @else
                        <div class="w-full h-64 md:h-full bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-utensils text-gray-400 text-6xl"></i>
                        </div>
                    @endif
                </div>

                <!-- Menu Item Details -->
                <div class="md:w-1/2 p-6">
                    <div class="mb-4">
                        <span
                            class="inline-block bg-orange-100 text-orange-800 text-sm font-semibold px-3 py-1 rounded-full">
                            {{ ucfirst($menuItem->category) }}
                        </span>
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $menuItem->name }}</h1>
                    <p class="text-2xl font-bold text-orange-600 mb-4">₱{{ number_format($menuItem->price, 2) }}</p>

                    <p class="text-gray-600 mb-6">{{ $menuItem->description }}</p>

                    <!-- Add to Cart Form -->
                    <form action="{{ route('customer.cart.add', $menuItem) }}" method="POST" class="mb-6">
                        @csrf
                        <div class="flex items-center space-x-4 mb-4">
                            <label for="quantity" class="text-sm font-medium text-gray-700">Quantity:</label>
                            <select name="quantity" id="quantity"
                                class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="special_instructions" class="block text-sm font-medium text-gray-700 mb-2">
                                Special Instructions (Optional)
                            </label>
                            <textarea name="special_instructions" id="special_instructions" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                                placeholder="Any special requests or modifications..."></textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-orange-600 text-white py-3 px-4 rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 font-medium">
                            <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                        </button>
                    </form>

                    <!-- Back to Menu -->
                    <a href="{{ route('customer.menu') }}" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Menu
                    </a>
                </div>
            </div>
        </div>

        <!-- Similar Items -->
        @if($similarItems->count() > 0)
            <div class="mt-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">You might also like</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($similarItems as $item)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                                    class="w-full h-40 object-cover">
                            @else
                                <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-utensils text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $item->name }}</h3>
                                <p class="text-gray-600 text-sm mt-1">{{ Str::limit($item->description, 60) }}</p>
                                <div class="flex justify-between items-center mt-3">
                                    <span class="text-lg font-bold text-orange-600">₱{{ number_format($item->price, 2) }}</span>
                                    <a href="{{ route('customer.menu-item', $item) }}"
                                        class="bg-orange-500 text-white px-3 py-1 rounded-md hover:bg-orange-600 text-sm">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div id="success-message" class="fixed top-4 right-4 bg-orange-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
        <script>
            setTimeout(() => {
                const message = document.getElementById('success-message');
                if (message) message.remove();
            }, 3000);
        </script>
    @endif

    @if($errors->any())
        <div id="error-message" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span>Please check the form for errors</span>
            </div>
        </div>
        <script>
            setTimeout(() => {
                const message = document.getElementById('error-message');
                if (message) message.remove();
            }, 5000);
        </script>
    @endif

    <script>
        // Add to cart with AJAX
        d// Add to cart with AJAX
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const submitButton = this.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;

                    // Show loading state
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Adding...';

                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show success message and redirect to cart
                                showNotification('Item added to cart! Redirecting...', 'success');

                                // Update cart count in navigation
                                updateCartCount(data.cart_count);

                                // Just update cart count and show success message
                                updateCartCount(data.cart_count);
                                showNotification('Item added to cart!', 'success');
                            } else {
                                showNotification('Error adding item to cart', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showNotification('Error adding item to cart', 'error');
                        })
                        .finally(() => {
                            // Restore button
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalText;
                        });
                });
            }

            function showNotification(message, type) {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${type === 'success' ? 'bg-orange-500 text-white' : 'bg-red-500 text-white'
                    }`;
                notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.remove();
                }, 3000);
            }

            function updateCartCount(count) {
                const cartLinks = document.querySelectorAll('a[href*="cart"]');
                cartLinks.forEach(link => {
                    const text = link.textContent;
                    const newText = text.replace(/\(\d+\)/, `(${count})`);
                    link.textContent = newText;
                });
            }
        });
    </script>
</body>

</html>