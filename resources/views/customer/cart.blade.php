<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - NaNi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <img src="{{ asset('images/nani-logo.png') }}" alt="NaNi Logo" class="h-10 w-10 mr-3">
                    <div>
                        <a href="/" class="text-xl font-bold text-gray-800">NaNi</a>
                        <p class="text-xs text-gray-500 -mt-1">Shopping Cart</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('customer.dashboard') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <a href="{{ route('customer.cart.index') }}"
                        class="text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-shopping-cart mr-1"></i>Cart ({{ $cartItems->sum('quantity') }})
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
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Shopping Cart</h2>
            </div>

            @if($cartItems->count() > 0)
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($cartItems as $item)
                            <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                                <div class="flex items-center">
                                    @if($item->menuItem->image)
                                        <img src="{{ asset('storage/' . $item->menuItem->image) }}"
                                            alt="{{ $item->menuItem->name }}" class="h-16 w-16 object-cover rounded-md">
                                    @else
                                        <div class="h-16 w-16 bg-gray-200 rounded-md flex items-center justify-center">
                                            <i class="fas fa-utensils text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $item->menuItem->name }}</h3>
                                        <p class="text-gray-600">₱{{ number_format($item->menuItem->price, 2) }}</p>
                                        @if($item->special_instructions)
                                            <p class="text-sm text-gray-500">Note: {{ $item->special_instructions }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center space-x-2">
                                        <button class="decrease-quantity px-2 py-1 border border-gray-300 rounded"
                                            data-cart-id="{{ $item->id }}">-</button>
                                        <span class="quantity-display w-8 text-center">{{ $item->quantity }}</span>
                                        <button class="increase-quantity px-2 py-1 border border-gray-300 rounded"
                                            data-cart-id="{{ $item->id }}">+</button>
                                    </div>
                                    <div class="text-lg font-semibold text-gray-900">
                                        ₱{{ number_format($item->quantity * $item->menuItem->price, 2) }}
                                    </div>
                                    <button class="remove-item text-red-600 hover:text-red-800" data-cart-id="{{ $item->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <div class="flex justify-between items-center text-xl font-bold">
                            <span>Total:</span>
                            <span>₱{{ number_format($total, 2) }}</span>
                        </div>

                        <div class="mt-6 flex justify-between">
                            <form action="{{ route('customer.cart.clear') }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                                    Clear Cart
                                </button>
                            </form>
                            {{-- Replace the existing checkout button --}}
                            <a href="{{ route('customer.cart.checkout') }}"
                                class="bg-orange-500 text-white px-6 py-2 rounded-md hover:bg-orange-600">
                                Proceed to Checkout
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="p-6 text-center">
                    <i class="fas fa-shopping-cart text-gray-400 text-5xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Your cart is empty</h3>
                    <p class="text-gray-600 mb-4">Add some delicious items to get started!</p>
                    <a href="{{ route('customer.dashboard') }}"
                        class="bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600">
                        Browse Menu
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Checkout Modal -->
    <div id="checkout-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Checkout</h3>
            <form id="checkout-form" action="{{ route('customer.orders.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="delivery_address" class="block text-sm font-medium text-gray-700">Delivery
                            Address</label>
                        <input type="text" name="delivery_address" id="delivery_address" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>
                    <div>
                        <label for="customer_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="text" name="customer_phone" id="customer_phone" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>
                    <div>
                        <label for="special_instructions" class="block text-sm font-medium text-gray-700">Special
                            Instructions</label>
                        <textarea name="special_instructions" id="special_instructions" rows="3"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" id="cancel-checkout"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600">
                        Place Order
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Checkout modal
            const checkoutBtn = document.getElementById('checkout-btn');
            const checkoutModal = document.getElementById('checkout-modal');
            const cancelCheckout = document.getElementById('cancel-checkout');

            if (checkoutBtn) {
                checkoutBtn.addEventListener('click', () => {
                    checkoutModal.classList.remove('hidden');
                });
            }

            cancelCheckout.addEventListener('click', () => {
                checkoutModal.classList.add('hidden');
            });

            // Cart quantity updates
            document.querySelectorAll('.increase-quantity').forEach(button => {
                button.addEventListener('click', function () {
                    const cartId = this.getAttribute('data-cart-id');
                    updateQuantity(cartId, 'increase');
                });
            });

            document.querySelectorAll('.decrease-quantity').forEach(button => {
                button.addEventListener('click', function () {
                    const cartId = this.getAttribute('data-cart-id');
                    updateQuantity(cartId, 'decrease');
                });
            });

            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', function () {
                    const cartId = this.getAttribute('data-cart-id');
                    removeItem(cartId);
                });
            });

            function updateQuantity(cartId, action) {
                const quantityDisplay = document.querySelector(`[data-cart-id="${cartId}"]`).closest('.flex').querySelector('.quantity-display');
                let quantity = parseInt(quantityDisplay.textContent);

                if (action === 'increase' && quantity < 10) {
                    quantity++;
                } else if (action === 'decrease' && quantity > 1) {
                    quantity--;
                }

                fetch(`/customer/cart/${cartId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        quantity: quantity
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            quantityDisplay.textContent = quantity;
                            location.reload(); // Simple reload for demo
                        }
                    });
            }

            function removeItem(cartId) {
                fetch(`/customer/cart/${cartId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
            }
        });
    </script>
</body>

</html>