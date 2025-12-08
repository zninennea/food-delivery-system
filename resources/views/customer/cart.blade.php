<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - NaNi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                    <img src="https://i.imgur.com/vPOu1H2.png" alt="NaNi Icon"
                        class="h-20 w-auto group-hover:rotate-12 transition-transform duration-300">
                </a>

                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('customer.dashboard') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-home mr-1"></i> Home
                    </a>
                    <a href="{{ route('customer.menu') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-utensils mr-1"></i> Menu
                    </a>
                    <a href="{{ route('customer.cart.index') }}"
                        class="text-orange-600 bg-orange-50 px-3 py-2 rounded-lg text-sm font-medium transition-colors relative">
                        <i class="fas fa-shopping-cart mr-1"></i> Cart
                        @if($cartItems->sum('quantity') > 0)
                            <span id="nav-cart-count"
                                class="absolute top-0 right-0 -mt-1 -mr-1 px-1.5 py-0.5 bg-red-500 text-white text-xs font-bold rounded-full">{{ $cartItems->sum('quantity') }}</span>
                        @endif
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

        <div class="flex items-center justify-between mb-8 fade-in">
            <h1 class="text-4xl font-bold text-gray-900">Your Cart</h1>
            <span class="text-stone-500 text-sm">{{ $cartItems->sum('quantity') }} items</span>
        </div>

        @if($cartItems->count() > 0)
            <div class="flex flex-col lg:flex-row gap-8 fade-in">

                <div class="lg:w-2/3 space-y-4">
                    @foreach($cartItems as $item)
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-stone-100 p-4 sm:p-6 flex flex-col sm:flex-row items-start gap-6 transition-all hover:shadow-md">

                            <div class="w-full sm:w-24 h-24 flex-shrink-0 bg-stone-100 rounded-xl overflow-hidden">
                                @if($item->menuItem->image)
                                    <img src="{{ asset('storage/' . $item->menuItem->image) }}" alt="{{ $item->menuItem->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-stone-300">
                                        <i class="fas fa-utensils text-2xl"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-grow">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-xl font-bold text-gray-900 font-serif mb-1">{{ $item->menuItem->name }}</h3>
                                    <p class="text-lg font-bold text-orange-600">
                                        ₱{{ number_format($item->quantity * $item->menuItem->price, 2) }}</p>
                                </div>
                                <p class="text-stone-500 text-sm mb-2">₱{{ number_format($item->menuItem->price, 2) }} each</p>

                                @if($item->special_instructions)
                                    <div
                                        class="bg-orange-50 text-orange-800 text-xs px-3 py-2 rounded-lg inline-block mb-3 border border-orange-100">
                                        <i class="fas fa-comment-alt mr-1"></i> "{{ $item->special_instructions }}"
                                    </div>
                                @endif

                                <div class="flex justify-between items-center mt-2">
                                    <div class="flex items-center bg-stone-100 rounded-lg p-1">
                                        <button
                                            class="decrease-quantity w-8 h-8 flex items-center justify-center bg-white rounded-md shadow-sm text-stone-600 hover:text-orange-600 transition-colors"
                                            data-cart-id="{{ $item->id }}">-</button>
                                        <span
                                            class="quantity-display w-10 text-center font-bold text-gray-900 text-sm">{{ $item->quantity }}</span>
                                        <button
                                            class="increase-quantity w-8 h-8 flex items-center justify-center bg-white rounded-md shadow-sm text-stone-600 hover:text-orange-600 transition-colors"
                                            data-cart-id="{{ $item->id }}">+</button>
                                    </div>

                                    <button
                                        class="remove-item text-red-500 hover:text-red-700 text-sm font-medium transition-colors flex items-center gap-1"
                                        data-cart-id="{{ $item->id }}">
                                        <i class="fas fa-trash-alt"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="lg:w-1/3">
                    <div class="bg-white rounded-2xl shadow-xl border border-stone-100 p-6 sm:p-8 sticky top-28">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 font-serif">Order Summary</h2>

                        <div class="space-y-4 mb-6 pb-6 border-b border-gray-100">
                            <div class="flex justify-between text-stone-600">
                                <span>Subtotal</span>
                                <span>₱{{ number_format($total, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-stone-600">
                                <span>Delivery Fee</span>
                                <span>₱{{ number_format($deliveryFee, 2) }}</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-end mb-8">
                            <span class="text-lg font-bold text-gray-900">Total</span>
                            <span
                                class="text-3xl font-bold text-orange-600">₱{{ number_format($total + $deliveryFee, 2) }}</span>
                        </div>

                        <a href="{{ route('customer.cart.checkout') }}"
                            class="block w-full bg-gradient-to-r from-orange-600 to-red-600 text-white text-center py-4 rounded-xl font-bold shadow-lg hover:shadow-orange-500/30 transform hover:-translate-y-1 transition-all duration-200">
                            Proceed to Checkout
                        </a>

                        <div class="mt-4 flex justify-center">
                            <button type="button" id="clear-cart-btn"
                                class="text-stone-400 hover:text-red-500 text-sm transition-colors">
                                Clear Shopping Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-12 text-center fade-in max-w-2xl mx-auto">
                <div class="w-24 h-24 bg-orange-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shopping-basket text-4xl text-orange-300"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2 font-serif">Your cart is empty</h3>
                <p class="text-stone-500 mb-8">Looks like you haven't added any delicious items yet.</p>
                <a href="{{ route('customer.menu') }}"
                    class="inline-flex items-center gap-2 bg-stone-900 text-white px-8 py-3 rounded-full font-bold hover:bg-orange-600 transition-all duration-300">
                    <i class="fas fa-utensils"></i> Browse Menu
                </a>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize SweetAlert2 Toast
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#1f2937',
                color: '#f9fafb',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            // Helper to handle API calls
            const handleCartAction = async (url, method, body = null) => {
                try {
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: body ? JSON.stringify(body) : null
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        Toast.fire({
                            icon: 'success',
                            title: data.message || 'Cart updated successfully'
                        });

                        // Wait for toast to show before reload
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        throw new Error(data.message || 'Failed to update cart');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error',
                        text: error.message || 'Something went wrong. Please try again.',
                        icon: 'error',
                        confirmButtonColor: '#ef4444',
                        confirmButtonText: 'OK',
                        customClass: {
                            popup: 'rounded-2xl',
                            confirmButton: 'rounded-xl px-6 py-3'
                        }
                    });
                }
            };

            // Quantity Increase
            document.querySelectorAll('.increase-quantity').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.cartId;
                    const display = this.parentNode.querySelector('.quantity-display');
                    let qty = parseInt(display.textContent);

                    if (qty < 10) {
                        handleCartAction(`/customer/cart/${id}`, 'PUT', { quantity: qty + 1 });
                    } else {
                        Toast.fire({
                            icon: 'warning',
                            title: 'Maximum quantity is 10'
                        });
                    }
                });
            });

            // Quantity Decrease
            document.querySelectorAll('.decrease-quantity').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.cartId;
                    const display = this.parentNode.querySelector('.quantity-display');
                    let qty = parseInt(display.textContent);

                    if (qty > 1) {
                        handleCartAction(`/customer/cart/${id}`, 'PUT', { quantity: qty - 1 });
                    } else {
                        // If quantity is 1, show remove confirmation instead
                        const removeBtn = this.closest('.flex').querySelector('.remove-item');
                        removeBtn.click();
                    }
                });
            });

            // Remove Item with SweetAlert2
            document.querySelectorAll('.remove-item').forEach(btn => {
                btn.addEventListener('click', function () {
                    const itemName = this.closest('.bg-white').querySelector('h3').textContent;
                    const cartId = this.dataset.cartId;

                    Swal.fire({
                        title: 'Remove Item?',
                        html: `<div class="text-center">
                            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-trash-alt text-red-600 text-xl"></i>
                            </div>
                            <p class="text-gray-700">Are you sure you want to remove <strong>"${itemName}"</strong> from your cart?</p>
                        </div>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: '<i class="fas fa-trash-alt mr-2"></i>Remove Item',
                        cancelButtonText: '<i class="fas fa-times mr-2"></i>Keep Item',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-2xl',
                            confirmButton: 'rounded-xl px-6 py-3 font-medium',
                            cancelButton: 'rounded-xl px-6 py-3 font-medium'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            handleCartAction(`/customer/cart/${cartId}`, 'DELETE');
                        }
                    });
                });
            });

            // Clear Cart with SweetAlert2 - FIXED VERSION
            document.getElementById('clear-cart-btn')?.addEventListener('click', function () {
                Swal.fire({
                    title: 'Clear Cart?',
                    html: `<div class="text-center">
                        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-shopping-basket text-red-600 text-2xl"></i>
                        </div>
                        <p class="text-lg font-medium text-gray-900">Clear All Items</p>
                        <p class="text-gray-600 mt-2">This will remove all items from your shopping cart.</p>
                        <div class="mt-4 p-4 bg-yellow-50 rounded-xl border border-yellow-100">
                            <p class="text-sm text-yellow-800">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                This action cannot be undone.
                            </p>
                        </div>
                    </div>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="fas fa-trash mr-2"></i>Clear All Items',
                    cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-6 py-3 font-medium',
                        cancelButton: 'rounded-xl px-6 py-3 font-medium'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Clearing Cart...',
                            text: 'Please wait while we clear your cart.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        fetch('{{ route('customer.cart.clear') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
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
                                    Swal.close();
                                    Toast.fire({
                                        icon: 'success',
                                        title: data.message || 'Cart cleared successfully'
                                    });
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1000);
                                } else {
                                    throw new Error(data.message || 'Failed to clear cart');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    title: 'Error',
                                    text: error.message || 'Failed to clear cart. Please try again.',
                                    icon: 'error',
                                    confirmButtonColor: '#ef4444',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        popup: 'rounded-2xl',
                                        confirmButton: 'rounded-xl px-6 py-3'
                                    }
                                });
                            });
                    }
                });
            });

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
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
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