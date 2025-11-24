<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - NaNi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <img src="{{ asset('images/nani-logo.png') }}" alt="NaNi Logo" class="h-10 w-10 mr-3">
                    <div>
                        <a href="/" class="text-xl font-bold text-gray-800">NaNi</a>
                        <p class="text-xs text-gray-500 -mt-1">Japanese Restaurant</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('customer.dashboard') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <a href="{{ route('customer.menu') }}"
                        class="text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-utensils mr-1"></i>Menu
                    </a>
                    <a href="{{ route('customer.cart.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-shopping-cart mr-1"></i>Cart ({{ $cartCount }})
                    </a>
                    <a href="{{ route('customer.profile.show') }}"
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
    @if(isset($modifyOrder) && $modifyOrder)
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4" role="alert">
            <div class="flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                <div>
                    <p class="font-bold">Modification Mode</p>
                    <p>You are adding items to Order #{{ $modifyOrder->order_number }}. These will be added as additional
                        items.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Restaurant Header -->
    <div class="bg-orange-600 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-2">Our Menu</h1>
            <p class="text-xl">Discover authentic Japanese flavors</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-8 px-4">
        <!-- Category Navigation -->
        <div class="flex overflow-x-auto space-x-4 mb-8 pb-2">
            <button
                class="category-filter px-4 py-2 bg-orange-500 text-white rounded-full text-sm font-medium whitespace-nowrap"
                data-category="all">
                        All Items
            </button>
            @foreach($menuItemsByCategory->keys() as $category)
                <button
                    class="category-filter px-4 py-2 bg-gray-200 text-gray-700 rounded-full text-sm font-medium hover:bg-gray-300 whitespace-nowrap"
                    data-category="{{ $category }}">
                    {{ ucfirst($category) }}
                </button>
            @endforeach
        </div>

        <!-- Menu Items by Category -->
        @foreach($menuItemsByCategory as $category => $items)
            <div class="mb-12 category-section" data-category="{{ $category }}">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b-2 border-orange-500 pb-2">{{ ucfirst($category) }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($items as $item)
                        <div class="bg-white rounded-lg shadow hover:shadow-md transition duration-200 menu-item">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-48
                                object-cover rounded-t-lg">
                            @else
                                <div class="w-full h-48 bg-gray-200 rounded-t-lg flex items-center justify-center">
                                    <i class="fas fa-utensils text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $item->name }}</h3>
                                <p class=" text-gray-600 text-sm mt-1">{{ $item->description }}</p>
                                    <div class="flex justify-between items-center mt-3">
                                        <span class="text-lg font-bold text-orange-600">₱{{ number_format($item->price, 2) }}</span>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('customer.menu.item', $item) }}"
                                                class="bg-gray-500 text-white px-3 py-1 rounded-md text-sm hover:bg-gray-600 transition duration-200">
                                                Details
                                            </a>
                                                        <button
                                                class="add-to-cart-btn bg-orange-500 text-white px-3 py-1 rounded-md text-sm hover:bg-orange-600 transition duration-200"
                                                data-item-id="{{ $item->id }}" data-item-name="{{ $item->name }}"            
                                                data-item-price="{{ $item->price }}">
                                                Add to Cart
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

    <!-- Quick Cart Summary (Fixed at bottom) -->
    @if($cartCount > 0)
        <div class="fixed bottom-4 right-4 bg-orange-500 text-white p-4 rounded-lg shadow-lg">
            <div class="flex items-center space-x-2">
                <i class="fas fa-shopping-cart"></i>
                <span>{{ $cartCount }} items</span>
                <a href="{{ route('customer.cart.index') }}"
                    class="bg-white text-orange-500 px-3 py-1 rounded text-sm font-medium hover:bg-gray-100">
                    View Cart
                </a>
            </div>
        </div>
    @endif

    <!-- Add to Cart Modal -->
    <div id="addToCartModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gray-900 mb-4" id="modalItemName">Add to Cart</h3>
            <form id="addToCartForm">
                @csrf
                <input type="hidden" id="modalItemId" name="menu_item_id">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                    <div class="flex items-center space-x-4">
                        <button type="button" id="decreaseQty"
                            class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">-</button>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="10"
                            class="w-16 text-center border border-gray-300 rounded-md py-1">
                        <button type="button" id="increaseQty"
                            class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">+</button>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="special_instructions" class="block text-sm font-medium text-gray-700 mb-2">Special
                        Instructions (Optional)</label>
                    <textarea id="special_instructions" name="special_instructions" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                        placeholder="Any special requests..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" id="closeModal"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600">
                        Add to Cart
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Category filtering
        document.querySelectorAll('.category-filter').forEach(button => {
            button.addEventListener('click', function () {
                const category = this.getAttribute('data-category');

                // Update active button
                document.querySelectorAll('.category-filter').forEach(btn => {
                    btn.classList.remove('bg-orange-500', 'text-white');
                    btn.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
                });
                this.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
                this.classList.add('bg-orange-500', 'text-white');

                // Show/hide categories
                if (category === 'all') {
                    document.querySelectorAll('.category-section').forEach(section => {
                        section.style.display = 'block';
                    });
                } else {
                    document.querySelectorAll('.category-section').forEach(section => {
                        if (section.getAttribute('data-category') === category) {
                            section.style.display = 'block';
                        } else {
                            section.style.display = 'none';
                        }
                    });
                }
            });
        });

        // Add to Cart Modal
        const modal = document.getElementById('addToCartModal');
        const modalItemName = document.getElementById('modalItemName');
        const modalItemId = document.getElementById('modalItemId');
        const quantityInput = document.getElementById('quantity');

        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', function () {
                const itemId = this.getAttribute('data-item-id');
                const itemName = this.getAttribute('data-item-name');

                modalItemName.textContent = `Add ${itemName} to Cart`;
                modalItemId.value = itemId;
                quantityInput.value = 1;

                modal.classList.remove('hidden');
            });
        });

        // Quantity controls
        document.getElementById('increaseQty').addEventListener('click', function () {
            const current = parseInt(quantityInput.value);
            if (current < 10) quantityInput.value = current + 1;
        });

        document.getElementById('decreaseQty').addEventListener('click', function () {
            const current = parseInt(quantityInput.value);
            if (current > 1) quantityInput.value = current - 1;
        });

        // Close modal
        document.getElementById('closeModal').addEventListener('click', function () {
            modal.classList.add('hidden');
        });

        // Add to Cart form submission
        document.getElementById('addToCartForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch(`/customer/cart/${modalItemId.value}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        modal.classList.add('hidden');
                        alert('Item added to cart!');
                        // You could update the cart count here
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error adding item to cart. Please try again.');
                });
        });

        // Close modal when clicking outside
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    </script>
</body>

</html>