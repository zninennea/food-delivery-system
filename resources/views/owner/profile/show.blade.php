<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - NaNi Owner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <!-- NaNi Logo -->
                    <div class="flex items-center">
                        <img src="{{ asset('images/nani-logo.png') }}" alt="NaNi Logo" class="h-10 w-10 mr-3">
                        <div>
                            <a href="/" class="text-xl font-bold text-gray-800">NaNi</a>
                            <p class="text-xs text-gray-500 -mt-1">Owner Dashboard</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('owner.dashboard') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <a href="{{ route('owner.menu.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-utensils mr-1"></i>Menu
                    </a>
                    <a href="{{ route('owner.orders.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-shopping-cart mr-1"></i>Orders
                    </a>
                    <a href="{{ route('owner.analytics.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-chart-bar mr-1"></i>Analytics
                    </a>
                    <a href="{{ route('owner.riders.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-motorcycle mr-1"></i>Riders
                    </a>
                    <a href="{{ route('owner.profile.show') }}"
                        class="text-orange-600 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
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

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <div class="flex items-center">
                <div class="py-1">
                    <svg class="w-6 h-6 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <strong class="font-bold">Success! </strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <div class="flex items-center">
                <div class="py-1">
                    <svg class="w-6 h-6 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <strong class="font-bold">Error! </strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-900">Profile Information</h2>
                <a href="{{ route('owner.profile.edit') }}"
                    class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
                    <i class="fas fa-edit mr-2"></i>Edit Profile
                </a>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- User Information -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2">User Information</h3>

                        <div class="flex items-center space-x-4 mb-6">
                            @if($user->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture"
                                    class="h-20 w-20 rounded-full object-cover">
                            @else
                                <div class="h-20 w-20 bg-gray-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                            <div>
                                <h4 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h4>
                                <p class="text-gray-600">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                                <p class="mt-1 text-lg text-gray-900">{{ $user->name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="mt-1 text-lg text-gray-900">{{ $user->email }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone</label>
                                <p class="mt-1 text-lg text-gray-900">{{ $user->phone ?? 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Restaurant Information -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Restaurant Information</h3>

                        @if($restaurant->background_image)
                            <div class="mb-4">
                                <img src="{{ asset('storage/' . $restaurant->background_image) }}"
                                    alt="Restaurant Background" class="w-full h-32 object-cover rounded-lg">
                            </div>
                        @endif

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Restaurant Name</label>
                                <p class="mt-1 text-lg text-gray-900">{{ $restaurant->name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Address</label>
                                <p class="mt-1 text-lg text-gray-900">{{ $restaurant->address }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Restaurant Phone</label>
                                <p class="mt-1 text-lg text-gray-900">{{ $restaurant->phone }}</p>
                            </div>

                            @if($restaurant->facebook_url)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Facebook URL</label>
                                    <a href="{{ $restaurant->facebook_url }}" target="_blank"
                                        class="mt-1 text-lg text-blue-600 hover:text-blue-800">
                                        {{ $restaurant->facebook_url }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>