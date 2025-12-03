<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu Item - NaNi Owner</title>
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
                        <p class="text-xs text-gray-500 -mt-1">Admin Dashboard</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('owner.menu.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-arrow-left mr-1"></i>Back to Menu
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-orange-600">Edit Menu Item: {{ $menuItem->name }}</h2>
            </div>

            <form action="{{ route('owner.menu.update', $menuItem) }}" method="POST" enctype="multipart/form-data"
                class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Current Image -->
                    @if($menuItem->image)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Current Image</label>
                            <img src="{{ asset('storage/' . $menuItem->image) }}" alt="{{ $menuItem->name }}"
                                class="mt-2 h-32 w-32 object-cover rounded-lg">
                        </div>
                    @endif

                    <!-- Item Image -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">New Item Image</label>
                        <input type="file" name="image" id="image"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('image')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Item Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Item Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $menuItem->name) }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="number" name="price" id="price" value="{{ old('price', $menuItem->price) }}"
                            step="0.01" min="0"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('price')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category" id="category"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
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
                        @error('category')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('description', $menuItem->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('owner.menu.index') }}"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Update Menu Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>