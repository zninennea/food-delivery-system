<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $menuItem->name }} - NaNi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Add SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        /* SweetAlert2 Custom Theme */
        .swal2-popup {
            border-radius: 1.5rem !important;
            font-family: 'Inter', sans-serif;
        }

        .swal2-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
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
                    <a href="{{ route('customer.menu') }}"
                        class="text-orange-600 bg-orange-50 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-utensils mr-1"></i> Menu
                    </a>
                    <a href="{{ route('customer.cart.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors relative">
                        <i class="fas fa-shopping-cart mr-1"></i> Cart
                        <span id="nav-cart-count"
                            class="{{ $cartCount > 0 ? '' : 'hidden' }} absolute top-0 right-0 -mt-1 -mr-1 px-1.5 py-0.5 bg-red-500 text-white text-xs font-bold rounded-full">{{ $cartCount }}</span>
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

    <div class="pt-28 pb-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <nav class="flex mb-8 text-sm text-gray-500 fade-in">
            <a href="{{ route('customer.menu') }}" class="hover:text-orange-600 transition-colors">Menu</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900 font-medium">{{ $menuItem->name }}</span>
        </nav>

        <div class="bg-white rounded-3xl shadow-xl border border-stone-100 overflow-hidden fade-in">
            <div class="md:flex">
                <div class="md:w-1/2 relative h-64 md:h-auto bg-stone-100">
                    @if($menuItem->image)
                        <img src="{{ asset('storage/' . $menuItem->image) }}" alt="{{ $menuItem->name }}"
                            class="absolute inset-0 w-full h-full object-cover">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center text-stone-300">
                            <i class="fas fa-utensils text-6xl"></i>
                        </div>
                    @endif
                    <div class="absolute top-4 left-4">
                        <span
                            class="bg-white/90 backdrop-blur-md text-orange-600 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm uppercase tracking-wider">
                            {{ ucfirst($menuItem->category) }}
                        </span>
                    </div>
                </div>

                <div class="md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 leading-tight">{{ $menuItem->name }}
                    </h1>

                    <div class="flex items-center gap-4 mb-6">
                        <span
                            class="text-3xl font-bold text-orange-600">₱{{ number_format($menuItem->price, 2) }}</span>
                        <div class="h-8 w-px bg-gray-200"></div>
                        <div class="flex text-yellow-400 text-sm">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                            <span class="text-gray-400 ml-2 text-xs pt-0.5">(Popular)</span>
                        </div>
                    </div>

                    <p class="text-gray-600 text-lg leading-relaxed mb-8 font-light">{{ $menuItem->description }}</p>

                    <form id="addToCartForm" action="{{ route('customer.cart.add', $menuItem) }}" method="POST"
                        class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="quantity"
                                    class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                                <div
                                    class="flex items-center justify-between bg-gray-50 rounded-2xl p-2 border border-gray-100 max-w-xs">
                                    <button type="button" id="decreaseQty"
                                        class="w-10 h-10 bg-white rounded-xl shadow-sm text-gray-600 hover:text-orange-600 transition-colors flex items-center justify-center font-bold text-lg">-</button>
                                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="10"
                                        class="w-16 text-center bg-transparent border-none focus:ring-0 text-xl font-bold text-gray-900">
                                    <button type="button" id="increaseQty"
                                        class="w-10 h-10 bg-white rounded-xl shadow-sm text-gray-600 hover:text-orange-600 transition-colors flex items-center justify-center font-bold text-lg">+</button>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="special_instructions" class="block text-sm font-medium text-gray-700 mb-2">
                                Special Instructions <span class="text-gray-400 font-normal">(Optional)</span>
                            </label>
                            <textarea name="special_instructions" id="special_instructions" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-gray-50 text-sm resize-none"
                                placeholder="E.g., No onions, extra spicy..."></textarea>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 pt-4">
                            <button type="submit" id="addToCartButton"
                                class="flex-1 bg-gradient-to-r from-orange-600 to-red-600 text-white py-4 px-8 rounded-xl hover:shadow-lg hover:shadow-orange-500/30 transform hover:-translate-y-1 transition-all duration-200 font-bold text-lg flex justify-center items-center gap-2">
                                <i class="fas fa-shopping-bag"></i> Add to Order
                                <span id="priceDisplay" class="bg-white/20 px-3 py-1 rounded text-sm font-normal ml-2">
                                    ₱{{ number_format($menuItem->price, 2) }}
                                </span>
                            </button>
                            <a href="{{ route('customer.menu') }}"
                                class="px-6 py-4 border border-gray-200 rounded-xl text-gray-600 font-medium hover:bg-gray-50 hover:text-gray-900 transition-colors text-center">
                                Back to Menu
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if($similarItems->count() > 0)
            <div class="mt-16 fade-in" style="animation-delay: 0.2s;">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">You might also like</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($similarItems as $item)
                        <a href="{{ route('customer.menu-item', $item) }}"
                            class="group bg-white rounded-2xl shadow-sm border border-stone-100 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="relative h-48 overflow-hidden">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-stone-100 flex items-center justify-center text-stone-300">
                                        <i class="fas fa-utensils text-2xl"></i>
                                    </div>
                                @endif
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4">
                                    <p class="text-white font-bold">₱{{ number_format($item->price, 2) }}</p>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-orange-600 transition-colors">
                                    {{ $item->name }}
                                </h3>
                                <p class="text-gray-500 text-sm mt-1 line-clamp-2">{{ $item->description }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get menu item price
            const menuItemPrice = {{ $menuItem->price }};
            const quantityInput = document.getElementById('quantity');
            const priceDisplay = document.getElementById('priceDisplay');

            // Function to update price display
            function updatePriceDisplay() {
                const quantity = parseInt(quantityInput.value);
                const totalPrice = menuItemPrice * quantity;
                priceDisplay.textContent = '₱' + totalPrice.toFixed(2);
            }

            // Initialize price display
            updatePriceDisplay();

            // Quantity increase/decrease buttons
            document.getElementById('increaseQty').addEventListener('click', () => {
                if (parseInt(quantityInput.value) < 10) {
                    quantityInput.value = parseInt(quantityInput.value) + 1;
                    updatePriceDisplay();
                }
            });

            document.getElementById('decreaseQty').addEventListener('click', () => {
                if (parseInt(quantityInput.value) > 1) {
                    quantityInput.value = parseInt(quantityInput.value) - 1;
                    updatePriceDisplay();
                }
            });

            // Handle quantity input changes
            quantityInput.addEventListener('change', () => {
                let value = parseInt(quantityInput.value);
                if (value < 1) value = 1;
                if (value > 10) value = 10;
                quantityInput.value = value;
                updatePriceDisplay();
            });

            // Handle form submission with AJAX
            const addToCartForm = document.getElementById('addToCartForm');
            const addToCartButton = document.getElementById('addToCartButton');

            if (addToCartForm) {
                addToCartForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const submitBtn = addToCartButton;
                    const originalBtnText = submitBtn.innerHTML;

                    // Show loading state
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Adding...';

                    const formData = new FormData(this);

                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(data => {
                                    throw new Error(data.message || 'Network response was not ok');
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // SweetAlert Success - Same style as menu.blade.php
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Added!',
                                    text: data.message,
                                    timer: 1500,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end'
                                });

                                // Update cart count in navigation
                                const navCartCount = document.querySelector('#nav-cart-count');
                                if (navCartCount) {
                                    navCartCount.textContent = data.cart_count;
                                    if (data.cart_count > 0) {
                                        navCartCount.classList.remove('hidden');
                                    } else {
                                        navCartCount.classList.add('hidden');
                                    }
                                }

                                // Reset form (keep quantity but clear instructions)
                                document.getElementById('special_instructions').value = '';
                                quantityInput.value = 1;
                                updatePriceDisplay();
                            } else {
                                throw new Error(data.message || 'Failed to add item to cart');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: error.message || 'Failed to add item to cart. Please try again.',
                                icon: 'error',
                                confirmButtonColor: '#ef4444',
                                confirmButtonText: 'OK',
                                customClass: {
                                    popup: 'rounded-2xl',
                                    confirmButton: 'rounded-xl px-6 py-3'
                                }
                            });
                        })
                        .finally(() => {
                            // Reset button state
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalBtnText;
                        });
                });
            }

            // Logout confirmation
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
                            <p class="text-gray-700">Are you sure you want to logout from your account?</p>
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

            // Show any flash messages from server
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '{{ session('success') }}',
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    title: 'Error',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'OK',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-6 py-3'
                    }
                });
            @endif
        });
    </script>
</body>

</html>