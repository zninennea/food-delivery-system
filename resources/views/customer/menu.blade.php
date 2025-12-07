<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - NaNi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5 { font-family: 'Playfair Display', serif; }
        
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        .category-active { background-color: #ea580c; color: white; box-shadow: 0 4px 6px -1px rgba(234, 88, 12, 0.3); }
        .category-inactive { background-color: white; color: #4b5563; border: 1px solid #e5e7eb; }
        .category-inactive:hover { background-color: #f3f4f6; color: #1f2937; }
    </style>
</head>

<body class="bg-stone-50 text-gray-800 antialiased">
    
    <nav class="fixed w-full top-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('customer.dashboard') }}" class="flex-shrink-0 flex items-center gap-2 group">
                    <img src="https://i.imgur.com/vPOu1H2.png" alt="NaNi Icon" class="h-20 w-auto group-hover:rotate-12 transition-transform duration-300">
                </a>

                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('customer.dashboard') }}" class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-home mr-1"></i> Home
                    </a>
                    <a href="{{ route('customer.menu') }}" class="text-orange-600 bg-orange-50 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-utensils mr-1"></i> Menu
                    </a>
                    <a href="{{ route('customer.cart.index') }}" class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors relative">
                        <i class="fas fa-shopping-cart mr-1"></i> Cart
                        <span id="nav-cart-count" class="{{ $cartCount > 0 ? '' : 'hidden' }} absolute top-0 right-0 -mt-1 -mr-1 px-1.5 py-0.5 bg-red-500 text-white text-xs font-bold rounded-full animate-pulse">{{ $cartCount }}</span>
                    </a>
                     <div class="ml-4 flex items-center space-x-3 border-l pl-4 border-gray-200">
                        <a href="{{ route('customer.profile.show') }}" class="text-gray-600 hover:text-orange-600 transition-colors">
                            <i class="fas fa-user-circle text-xl"></i>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Logout">
                                <i class="fas fa-sign-out-alt text-lg"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="relative pt-32 pb-16 bg-stone-900 overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1579027989536-b7b1f875659b?q=80&w=2070&auto=format&fit=crop')] bg-cover bg-center opacity-40"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-stone-900 via-stone-900/50 to-transparent"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 drop-shadow-lg">Our Menu</h1>
            <p class="text-xl text-stone-300 max-w-2xl mx-auto font-light italic">Explore the authentic tastes of Japan, crafted with passion and precision.</p>
        </div>
    </div>

    @if(isset($modifyOrder) && $modifyOrder)
        <div class="max-w-7xl mx-auto px-4 mt-6">
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start shadow-sm">
                <i class="fas fa-info-circle text-blue-500 mt-1 mr-3 text-lg"></i>
                <div>
                    <h4 class="font-bold text-blue-900">Modification Mode</h4>
                    <p class="text-sm text-blue-700">You are adding items to Order #{{ $modifyOrder->order_number }}. These will be tracked as additional items.</p>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 py-8 relative min-h-screen">
        
        <div class="sticky top-20 z-40 bg-stone-50/95 backdrop-blur-sm py-4 -mx-4 px-4 border-b border-stone-200 mb-8">
            <div class="flex overflow-x-auto space-x-3 hide-scrollbar pb-1">
                <button class="category-filter category-active px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-300 whitespace-nowrap" data-category="all">
                    All Items
                </button>
                @foreach($menuItemsByCategory->keys() as $category)
                    <button class="category-filter category-inactive px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-300 whitespace-nowrap" data-category="{{ $category }}">
                        {{ ucfirst($category) }}
                    </button>
                @endforeach
            </div>
        </div>

        @foreach($menuItemsByCategory as $category => $items)
            <div class="mb-16 category-section scroll-mt-40" id="cat-{{ $category }}" data-category="{{ $category }}">
                <div class="flex items-center gap-4 mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">{{ ucfirst($category) }}</h2>
                    <div class="h-px bg-gray-200 flex-grow"></div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach($items as $item)
                        <div class="group bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden hover:shadow-xl hover:border-orange-100 transition-all duration-300 flex flex-col h-full">
                            <div class="relative h-56 overflow-hidden">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full bg-stone-100 flex items-center justify-center text-stone-300">
                                        <i class="fas fa-utensils text-4xl"></i>
                                    </div>
                                @endif
                                <button class="add-to-cart-btn absolute bottom-4 right-4 w-10 h-10 bg-white text-orange-600 rounded-full shadow-lg flex items-center justify-center hover:bg-orange-600 hover:text-white transition-all transform hover:scale-110 z-10"
                                    data-item-id="{{ $item->id }}" 
                                    data-item-name="{{ $item->name }}"
                                    data-item-price="{{ $item->price }}">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>

                            <div class="p-6 flex-1 flex flex-col">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-xl font-bold text-gray-900 leading-tight group-hover:text-orange-600 transition-colors">{{ $item->name }}</h3>
                                    <span class="text-lg font-bold text-orange-600 whitespace-nowrap ml-2">₱{{ number_format($item->price, 0) }}</span>
                                </div>
                                <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $item->description }}</p>
                                
                                <div class="mt-auto">
                                    <a href="{{ route('customer.menu-item', $item) }}" class="text-sm font-medium text-gray-400 hover:text-gray-800 transition-colors border-b border-dashed border-gray-300 hover:border-gray-800 pb-0.5">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <div id="quick-cart" class="{{ $cartCount > 0 ? 'translate-y-0' : 'translate-y-24' }} fixed bottom-6 left-1/2 transform -translate-x-1/2 z-50 transition-transform duration-300">
        <a href="{{ route('customer.cart.index') }}" class="flex items-center gap-4 bg-gray-900 text-white pl-5 pr-2 py-2 rounded-full shadow-2xl hover:scale-105 transition-transform border border-gray-700">
            <div class="flex items-center gap-2">
                <div class="bg-orange-500 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold" id="quick-cart-count">
                    {{ $cartCount }}
                </div>
                <span class="text-sm font-medium">Items in cart</span>
            </div>
            <span class="bg-white text-gray-900 px-4 py-2 rounded-full text-sm font-bold hover:bg-gray-100 transition-colors">
                View Cart <i class="fas fa-arrow-right ml-1"></i>
            </span>
        </a>
    </div>

    <div id="addToCartModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center z-[60] p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-3xl p-0 w-full max-w-md shadow-2xl transform scale-95 transition-transform duration-300" id="modalContent">
            
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-3xl">
                <h3 class="text-xl font-bold text-gray-900 font-serif">Add to Cart</h3>
                <button type="button" id="closeModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="addToCartForm" class="p-6">
                @csrf
                <input type="hidden" id="modalItemId" name="menu_item_id">
                
                <div class="mb-6">
                    <h4 class="text-lg font-bold text-orange-600 mb-1" id="modalItemName">Item Name</h4>
                    <p class="text-sm text-gray-500">Customize your order below</p>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Quantity</label>
                    <div class="flex items-center justify-between bg-gray-50 rounded-2xl p-2 border border-gray-100">
                        <button type="button" id="decreaseQty" class="w-10 h-10 bg-white rounded-xl shadow-sm text-gray-600 hover:text-orange-600 transition-colors flex items-center justify-center font-bold text-lg">-</button>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="10" class="w-16 text-center bg-transparent border-none focus:ring-0 text-xl font-bold text-gray-900">
                        <button type="button" id="increaseQty" class="w-10 h-10 bg-white rounded-xl shadow-sm text-gray-600 hover:text-orange-600 transition-colors flex items-center justify-center font-bold text-lg">+</button>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Special Instructions</label>
                    <textarea id="special_instructions" name="special_instructions" rows="3"
                        class="w-full px-4 py-3 bg-gray-50 border-gray-100 rounded-2xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm resize-none"
                        placeholder="E.g. No onions, sauce on side..."></textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-gradient-to-r from-orange-600 to-red-600 text-white py-4 rounded-xl font-bold text-lg hover:shadow-lg hover:shadow-orange-500/30 transform hover:-translate-y-1 transition-all duration-200 flex justify-center items-center gap-2">
                        <span>Add to Order</span>
                        <span id="modalPriceDisplay" class="bg-white/20 px-2 py-0.5 rounded text-sm font-normal ml-1"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // --- Category Filtering ---
            const filters = document.querySelectorAll('.category-filter');
            const sections = document.querySelectorAll('.category-section');

            filters.forEach(button => {
                button.addEventListener('click', () => {
                    const category = button.dataset.category;

                    // Update UI Buttons
                    filters.forEach(btn => {
                        btn.classList.remove('category-active');
                        btn.classList.add('category-inactive');
                    });
                    button.classList.remove('category-inactive');
                    button.classList.add('category-active');

                    // Filter Sections
                    sections.forEach(section => {
                        if (category === 'all' || section.dataset.category === category) {
                            section.style.display = 'block';
                            // Add slight animation
                            section.style.opacity = '0';
                            setTimeout(() => section.style.opacity = '1', 50);
                        } else {
                            section.style.display = 'none';
                        }
                    });
                });
            });

            // --- Modal Logic ---
            const modal = document.getElementById('addToCartModal');
            const modalContent = document.getElementById('modalContent');
            const modalItemName = document.getElementById('modalItemName');
            const modalItemId = document.getElementById('modalItemId');
            const quantityInput = document.getElementById('quantity');
            const modalPriceDisplay = document.getElementById('modalPriceDisplay');
            let currentItemPrice = 0;

            function openModal() {
                modal.classList.remove('hidden');
                // Small delay to allow display:block to apply before opacity transition
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    modalContent.classList.remove('scale-95');
                    modalContent.classList.add('scale-100');
                }, 10);
            }

            function closeModal() {
                modal.classList.add('opacity-0');
                modalContent.classList.remove('scale-100');
                modalContent.classList.add('scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }

            // Open Modal Buttons
            document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    modalItemName.textContent = this.dataset.itemName;
                    modalItemId.value = this.dataset.itemId;
                    currentItemPrice = parseFloat(this.dataset.itemPrice);
                    quantityInput.value = 1;
                    updatePriceDisplay();
                    openModal();
                });
            });

            // Close Modal Actions
            document.getElementById('closeModal').addEventListener('click', closeModal);
            modal.addEventListener('click', (e) => { if(e.target === modal) closeModal(); });

            // Quantity Logic
            document.getElementById('increaseQty').addEventListener('click', () => {
                if(quantityInput.value < 10) {
                    quantityInput.value = parseInt(quantityInput.value) + 1;
                    updatePriceDisplay();
                }
            });
            document.getElementById('decreaseQty').addEventListener('click', () => {
                if(quantityInput.value > 1) {
                    quantityInput.value = parseInt(quantityInput.value) - 1;
                    updatePriceDisplay();
                }
            });

            function updatePriceDisplay() {
                const total = currentItemPrice * parseInt(quantityInput.value);
                modalPriceDisplay.textContent = '₱' + total.toFixed(2);
            }

            // --- AJAX Add to Cart ---
            document.getElementById('addToCartForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalContent = submitBtn.innerHTML;

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                fetch(`/customer/cart/add/${modalItemId.value}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        closeModal();
                        updateCartUI(data.cart_count);
                        
                        // SweetAlert Success
                        Swal.fire({
                            icon: 'success',
                            title: 'Added!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });

                        // Clear form
                        document.getElementById('special_instructions').value = '';
                    }
                })
                .catch(err => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                    });
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalContent;
                });
            });

            function updateCartUI(count) {
                // Update Nav Badge
                const navBadge = document.getElementById('nav-cart-count');
                if(count > 0) {
                    navBadge.textContent = count;
                    navBadge.classList.remove('hidden');
                } else {
                    navBadge.classList.add('hidden');
                }

                // Update Floating Bar
                const quickCart = document.getElementById('quick-cart');
                const quickCartCount = document.getElementById('quick-cart-count');
                if(count > 0) {
                    quickCartCount.textContent = count;
                    quickCart.classList.remove('translate-y-24');
                    quickCart.classList.add('translate-y-0');
                }
            }
        });
    </script>
</body>
</html>