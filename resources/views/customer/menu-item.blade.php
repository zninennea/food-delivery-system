<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $menuItem->name }} - NaNi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5 { font-family: 'Playfair Display', serif; }
        .fade-in { animation: fadeIn 0.6s ease-out forwards; opacity: 0; transform: translateY(10px); }
        @keyframes fadeIn { to { opacity: 1; transform: translateY(0); } }
    </style>
</head>

<body class="bg-stone-50 text-gray-800 antialiased">
    
    <nav class="fixed w-full top-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('customer.dashboard') }}" class="flex-shrink-0 flex items-center gap-2 group">
                    <img src="https://i.imgur.com/vPOu1H2.png" alt="NaNi Icon" class="h-10 w-auto group-hover:rotate-12 transition-transform duration-300">
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
                        @if($cartCount > 0)
                            <span class="absolute top-0 right-0 -mt-1 -mr-1 px-1.5 py-0.5 bg-red-500 text-white text-xs font-bold rounded-full">{{ $cartCount }}</span>
                        @endif
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
                        <span class="bg-white/90 backdrop-blur-md text-orange-600 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm uppercase tracking-wider">
                            {{ ucfirst($menuItem->category) }}
                        </span>
                    </div>
                </div>

                <div class="md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 leading-tight">{{ $menuItem->name }}</h1>
                    
                    <div class="flex items-center gap-4 mb-6">
                        <span class="text-3xl font-bold text-orange-600">₱{{ number_format($menuItem->price, 2) }}</span>
                        <div class="h-8 w-px bg-gray-200"></div>
                        <div class="flex text-yellow-400 text-sm">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                            <span class="text-gray-400 ml-2 text-xs pt-0.5">(Popular)</span>
                        </div>
                    </div>

                    <p class="text-gray-600 text-lg leading-relaxed mb-8 font-light">{{ $menuItem->description }}</p>

                    <form action="{{ route('customer.cart.add', $menuItem) }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                                <div class="relative">
                                    <select name="quantity" id="quantity"
                                        class="block w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm rounded-xl bg-gray-50">
                                        @for($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
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
                            <button type="submit"
                                class="flex-1 bg-gradient-to-r from-orange-600 to-red-600 text-white py-4 px-8 rounded-xl hover:shadow-lg hover:shadow-orange-500/30 transform hover:-translate-y-1 transition-all duration-200 font-bold text-lg flex justify-center items-center gap-2">
                                <i class="fas fa-shopping-bag"></i> Add to Order
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
                        <a href="{{ route('customer.menu-item', $item) }}" class="group bg-white rounded-2xl shadow-sm border border-stone-100 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="relative h-48 overflow-hidden">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
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
                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-orange-600 transition-colors">{{ $item->name }}</h3>
                                <p class="text-gray-500 text-sm mt-1 line-clamp-2">{{ $item->description }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

</body>
</html>