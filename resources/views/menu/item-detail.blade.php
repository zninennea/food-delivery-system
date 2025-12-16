<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $menuItem->name }} - NaNi</title>
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
            animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
                    <a href="/menu"
                        class="text-orange-600 bg-orange-50 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-utensils mr-1"></i> Menu
                    </a>
                    <a href="/login"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-sign-in-alt mr-1"></i> Login
                    </a>
                    <a href="/register"
                        class="bg-stone-900 text-white px-5 py-2.5 rounded-full hover:bg-orange-600 text-sm font-medium transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 ml-2">
                        Create Account
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="pt-28 pb-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <nav class="flex mb-8 text-sm text-gray-500 fade-in">
            <a href="/menu" class="hover:text-orange-600 transition-colors flex items-center gap-2">
                <i class="fas fa-arrow-left text-xs"></i> Back to Menu
            </a>
            <span class="mx-3 text-gray-300">|</span>
            <span class="text-gray-900 font-medium">{{ $menuItem->name }}</span>
        </nav>

        <div class="bg-white rounded-3xl shadow-xl border border-stone-100 overflow-hidden fade-in">
            <div class="md:flex">
                <div class="md:w-1/2 relative h-96 md:h-auto bg-stone-100 group">
                    @if($menuItem->image)
                        <img src="{{ asset('storage/' . $menuItem->image) }}" alt="{{ $menuItem->name }}"
                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center text-stone-300">
                            <i class="fas fa-utensils text-6xl"></i>
                        </div>
                    @endif

                    <div class="absolute top-6 left-6">
                        <span
                            class="bg-white/95 backdrop-blur-md text-orange-600 text-xs font-bold px-4 py-2 rounded-full shadow-sm uppercase tracking-widest border border-orange-100">
                            {{ $menuItem->category }}
                        </span>
                    </div>
                </div>

                <div class="md:w-1/2 p-8 md:p-12 flex flex-col justify-center bg-white relative">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-orange-50 rounded-bl-full opacity-50"></div>

                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 leading-tight relative z-10">
                        {{ $menuItem->name }}
                    </h1>

                    <div class="flex items-center gap-4 mb-8">
                        <span
                            class="text-4xl font-bold text-orange-600">₱{{ number_format($menuItem->price, 2) }}</span>
                    </div>

                    <div class="space-y-6 mb-10">
                        <div>
                            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Description</h3>
                            <p class="text-gray-600 text-lg leading-relaxed font-light">{{ $menuItem->description }}</p>
                        </div>

                        <div
                            class="flex items-center gap-3 text-gray-500 bg-stone-50 p-4 rounded-xl border border-stone-100">
                            <div class="bg-white p-2 rounded-full shadow-sm">
                                <i class="fas fa-clock text-orange-500"></i>
                            </div>
                            <span class="text-sm font-medium">Preparation time: <span class="text-gray-900">15-20
                                    minutes</span></span>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-8">
                        <button onclick="showLoginAlert()"
                            class="w-full bg-gradient-to-r from-orange-600 to-red-600 text-white py-4 px-8 rounded-xl hover:shadow-lg hover:shadow-orange-500/30 transform hover:-translate-y-1 transition-all duration-200 font-bold text-lg flex justify-center items-center gap-3">
                            <i class="fas fa-lock"></i>
                            <span>Login to Order</span>
                        </button>
                        <p class="text-center text-xs text-gray-400 mt-3">
                            You must be signed in to add items to your cart.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @if($similarItems->count() > 0)
            <div class="mt-20 fade-in" style="animation-delay: 0.2s;">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">You might also like</h2>
                    <div class="h-px bg-gray-200 flex-1 ml-6"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($similarItems as $similarItem)
                        <a href="{{ route('menu.item', $similarItem) }}"
                            class="group bg-white rounded-2xl shadow-sm border border-stone-100 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="relative h-48 overflow-hidden">
                                @if($similarItem->image)
                                    <img src="{{ asset('storage/' . $similarItem->image) }}" alt="{{ $similarItem->name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-stone-100 flex items-center justify-center text-stone-300">
                                        <i class="fas fa-utensils text-2xl"></i>
                                    </div>
                                @endif
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4">
                                    <p class="text-white font-bold">₱{{ number_format($similarItem->price, 2) }}</p>
                                </div>
                            </div>
                            <div class="p-5">
                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-orange-600 transition-colors mb-2">
                                    {{ $similarItem->name }}
                                </h3>
                                <button
                                    class="text-sm font-medium text-gray-400 group-hover:text-gray-800 transition-colors flex items-center gap-1">
                                    View Details <i class="fas fa-arrow-right text-xs"></i>
                                </button>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <footer class="bg-stone-900 text-white py-12 border-t border-stone-800">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="flex justify-center items-center gap-2 mb-6 opacity-80">
                <a href="/" class="flex-shrink-0 flex items-center gap-2 group">
                    <img src="{{ asset('images/NaNi_Logo.png') }}" alt="NaNi Logo"
                        class="h-20 w-auto group-hover:rotate-12 transition-transform duration-300">
                </a>
            </div>
            <p class="text-stone-400 text-sm max-w-md mx-auto mb-6">Bringing the authentic flavors of Japan to the heart
                of Davao City.</p>
            <p class="text-stone-600 text-xs">&copy; {{ date('Y') }} NaNi Restaurant. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showLoginAlert() {
            Swal.fire({
                title: 'Join NaNi',
                text: "Please login or register to start your order.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#ea580c',
                cancelButtonColor: '#78716c',
                confirmButtonText: 'Login Now',
                cancelButtonText: 'Not yet'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/login';
                }
            })
        }
    </script>
</body>

</html>