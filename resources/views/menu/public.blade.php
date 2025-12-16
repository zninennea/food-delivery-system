<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - NaNi Japanese Restaurant</title>
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

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Smooth scrolling offset for sticky header */
        .category-anchor {
            scroll-margin-top: 140px;
        }
    </style>
</head>

<body class="bg-stone-50 text-gray-800 antialiased">

    <nav
        class="fixed w-full top-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="/" class="flex-shrink-0 flex items-center gap-2 group">
                    <img src="{{ asset('images/NaNi_Logo.png') }}" alt="NaNi Logo"
                        class="h-20 w-auto group-hover:rotate-12 transition-transform duration-300">
                </a>

                <div class="hidden md:flex items-center space-x-1">
                    <a href="/"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-home mr-1"></i> Home
                    </a>
                    <a href="/login"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-sign-in-alt mr-1"></i> Login
                    </a>
                    <a href="/register"
                        class="bg-orange-600 text-white px-5 py-2.5 rounded-full hover:bg-orange-600 text-sm font-medium transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 ml-2">
                        Create Account
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="relative pt-32 pb-20 bg-stone-900 overflow-hidden">
        <div class="absolute inset-0">
            <div
                class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1579027989536-b7b1f875659b?q=80&w=2070&auto=format&fit=crop')] bg-cover bg-center opacity-40">
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-stone-900 via-stone-900/50 to-transparent"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6 drop-shadow-lg">Our Menu</h1>
            <p class="text-xl text-stone-200 max-w-2xl mx-auto font-light italic mb-8">Authentic Japanese Cuisine - Dine
                in or Takeout</p>

            <div
                class="inline-flex flex-col sm:flex-row items-center gap-4 bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4 sm:px-8">
                <div class="flex items-center gap-2 text-white">
                    <i class="fas fa-map-marker-alt text-orange-500"></i>
                    <span class="text-sm font-medium">{{ $restaurant->address }}</span>
                </div>
                <div class="hidden sm:block h-4 w-px bg-white/20"></div>
                <div class="flex items-center text-yellow-400 gap-1 text-sm">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                    <span class="text-stone-300 ml-1">(128 reviews)</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-16" id="featured">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Featured Items</h2>
            <div class="h-px bg-gray-200 flex-1 ml-6"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($featuredItems as $item)
                <div
                    class="group bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="relative h-56 overflow-hidden">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full bg-stone-100 flex items-center justify-center text-stone-300">
                                <i class="fas fa-utensils text-4xl"></i>
                            </div>
                        @endif
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full shadow-sm">
                            <span class="text-orange-600 font-bold text-sm">₱{{ number_format($item->price, 2) }}</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2 font-serif">{{ $item->name }}</h3>
                        <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $item->description }}</p>
                        <a href="{{ route('menu.item', $item) }}"
                            class="inline-flex items-center text-sm font-bold text-orange-600 hover:text-orange-700 uppercase tracking-wide">
                            View Details <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="sticky top-20 z-40 bg-stone-50/95 backdrop-blur-sm py-4 border-b border-stone-200 mb-8 shadow-sm">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex overflow-x-auto space-x-3 hide-scrollbar pb-1">
                @foreach($menuItemsByCategory->keys() as $category)
                    <a href="#category-{{ Str::slug($category) }}"
                        class="px-6 py-2.5 bg-white text-gray-600 border border-gray-200 rounded-full text-sm font-bold hover:bg-orange-50 hover:text-orange-600 hover:border-orange-200 transition-all whitespace-nowrap shadow-sm">
                        {{ ucfirst($category) }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 pb-16">
        @foreach($menuItemsByCategory as $category => $items)
            <div class="mb-16 category-anchor" id="category-{{ Str::slug($category) }}">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 border-l-4 border-orange-500 pl-4 font-serif">
                    {{ ucfirst($category) }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($items as $item)
                        <div
                            class="bg-white rounded-2xl p-4 shadow-sm border border-stone-100 hover:border-orange-100 hover:shadow-md transition-all flex gap-4">
                            <div class="w-24 h-24 flex-shrink-0 rounded-xl overflow-hidden bg-stone-100">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-stone-300">
                                        <i class="fas fa-utensils"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 flex flex-col justify-between">
                                <div>
                                    <h3 class="font-bold text-gray-900 text-lg leading-tight mb-1">{{ $item->name }}</h3>
                                    <p class="text-gray-500 text-xs line-clamp-2">{{ $item->description }}</p>
                                </div>
                                <div class="flex justify-between items-end mt-2">
                                    <span class="font-bold text-orange-600">₱{{ number_format($item->price, 0) }}</span>
                                    <div class="flex gap-2">
                                        <a href="{{ route('menu.item', $item) }}" class="text-gray-400 hover:text-gray-600">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button onclick="showLoginAlert()" class="text-orange-500 hover:text-orange-600">
                                            <i class="fas fa-plus-circle text-xl"></i>
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

    <div class="bg-stone-900 relative overflow-hidden py-20">
        <div
            class="absolute top-0 right-0 w-96 h-96 bg-orange-500/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2">
        </div>
        <div
            class="absolute bottom-0 left-0 w-64 h-64 bg-red-500/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2">
        </div>

        <div class="max-w-4xl mx-auto text-center px-4 relative z-10">
            <h2 class="text-4xl font-bold text-white mb-6 font-serif">Ready to Order?</h2>
            <p class="text-xl text-stone-300 mb-10 font-light">Join our community of food lovers. Create an account to
                start ordering delicious Japanese cuisine!</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register"
                    class="bg-gradient-to-r from-orange-600 to-red-600 text-white px-8 py-4 rounded-xl font-bold shadow-lg hover:shadow-orange-500/30 hover:-translate-y-1 transition-all">
                    Create Account
                </a>
                <a href="/login"
                    class="bg-transparent border border-stone-600 text-white px-8 py-4 rounded-xl font-bold hover:bg-white hover:text-stone-900 transition-all">
                    Login to Order
                </a>
            </div>
        </div>
    </div>

    <footer class="bg-stone-950 text-white py-12 border-t border-stone-900">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="flex justify-center items-center gap-2 mb-6 opacity-80">
                <a href="/" class="flex-shrink-0 flex items-center gap-2 group">
                    <img src="{{ asset('images/NaNi_Logo.png') }}" alt="NaNi Logo"
                        class="h-20 w-auto group-hover:rotate-12 transition-transform duration-300">
                </a>
            </div>
            <p class="text-stone-500 text-sm max-w-md mx-auto mb-8">Authentic flavors, delivered to your doorstep.</p>
            <div class="flex justify-center space-x-6 mb-8">
                <a href="{{ $restaurant->facebook_url ?? '#' }}"
                    class="text-stone-600 hover:text-orange-500 transition-colors">
                    <i class="fab fa-facebook text-2xl"></i>
                </a>
                <a href="#" class="text-stone-600 hover:text-pink-500 transition-colors">
                    <i class="fab fa-instagram text-2xl"></i>
                </a>
            </div>
            <p class="text-stone-700 text-xs">&copy; {{ date('Y') }} NaNi Restaurant. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showLoginAlert() {
            Swal.fire({
                title: 'Join NaNi',
                text: "Please login or register to add items to your cart.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#ea580c',
                cancelButtonColor: '#78716c',
                confirmButtonText: 'Login Now',
                cancelButtonText: 'Not yet',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-lg px-6',
                    cancelButton: 'rounded-lg px-6'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/login';
                }
            })
        }
    </script>
</body>

</html>