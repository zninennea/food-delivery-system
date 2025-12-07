<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu Item - NaNi Owner</title>
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
    </style>
</head>

<body class="bg-stone-50 text-gray-800 antialiased">

    <nav
        class="fixed w-full top-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('owner.dashboard') }}" class="flex-shrink-0 flex items-center gap-2 group">
                    <img src="https://i.imgur.com/vPOu1H2.png" alt="NaNi Icon"
                        class="h-20 w-auto group-hover:rotate-12 transition-transform duration-300">
                </a>

                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('owner.menu.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Menu
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="pt-32 pb-16 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-3xl shadow-xl border border-stone-100 overflow-hidden fade-in">
            <div class="p-8 border-b border-stone-100 bg-stone-50/50">
                <h2 class="text-3xl font-bold text-gray-900 font-serif">Edit Item</h2>
                <p class="text-stone-500 mt-1">Updating details for <span
                        class="font-bold text-orange-600">{{ $menuItem->name }}</span></p>
            </div>

            <form action="{{ route('owner.menu.update', $menuItem) }}" method="POST" enctype="multipart/form-data"
                class="p-8 space-y-8">
                @csrf
                @method('PUT')

                <div class="flex flex-col sm:flex-row gap-8 items-start">
                    <div class="w-full sm:w-1/3">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Current Image</label>
                        @if($menuItem->image)
                            <img src="{{ asset('storage/' . $menuItem->image) }}" alt="{{ $menuItem->name }}"
                                class="w-full h-48 object-cover rounded-xl shadow-md border border-stone-200">
                        @else
                            <div
                                class="w-full h-48 bg-stone-100 rounded-xl flex items-center justify-center text-stone-300 border border-stone-200">
                                <i class="fas fa-utensils text-4xl"></i>
                            </div>
                        @endif
                    </div>

                    <div class="w-full sm:w-2/3">
                        <label for="image" class="block text-sm font-bold text-gray-700 mb-2">Upload New Image</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-stone-300 border-dashed rounded-xl hover:border-orange-400 transition-colors cursor-pointer bg-stone-50"
                            onclick="document.getElementById('image').click()">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-cloud-upload-alt text-3xl text-stone-400"></i>
                                <div class="text-sm text-stone-600">
                                    <span class="font-medium text-orange-600 hover:text-orange-500">Click to
                                        upload</span>
                                </div>
                                <p class="text-xs text-stone-500">Leave blank to keep current</p>
                            </div>
                            <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                        </div>
                        @error('image') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>

                <hr class="border-stone-100">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Item Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $menuItem->name) }}"
                            class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm transition-all">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-bold text-gray-700 mb-2">Price (₱)</label>
                        <input type="number" name="price" id="price" value="{{ old('price', $menuItem->price) }}"
                            step="0.01" min="0"
                            class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm transition-all">
                        @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="category" class="block text-sm font-bold text-gray-700 mb-2">Category</label>
                    <div class="relative">
                        <select name="category" id="category"
                            class="block w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm appearance-none transition-all">
                            <option value="appetizer" {{ $menuItem->category == 'appetizer' ? 'selected' : '' }}>Appetizer
                            </option>
                            <option value="main" {{ $menuItem->category == 'main' ? 'selected' : '' }}>Main Course
                            </option>
                            <option value="sushi" {{ $menuItem->category == 'sushi' ? 'selected' : '' }}>Sushi</option>
                            <option value="ramen" {{ $menuItem->category == 'ramen' ? 'selected' : '' }}>Ramen</option>
                            <option value="donburi" {{ $menuItem->category == 'donburi' ? 'selected' : '' }}>Donburi
                            </option>
                            <option value="dessert" {{ $menuItem->category == 'dessert' ? 'selected' : '' }}>Dessert
                            </option>
                            <option value="beverage" {{ $menuItem->category == 'beverage' ? 'selected' : '' }}>Beverage
                            </option>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-stone-500">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                    @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm resize-none transition-all">{{ old('description', $menuItem->description) }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-stone-100">
                    <a href="{{ route('owner.menu.index') }}"
                        class="px-6 py-3 border border-stone-200 text-stone-600 font-bold rounded-xl hover:bg-stone-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-8 py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg hover:shadow-blue-500/30 hover:-translate-y-0.5 transition-all">
                        Update Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>