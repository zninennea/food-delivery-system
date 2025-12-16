<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - NaNi Owner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        .hover-card {
            transition: all 0.3s ease;
        }

        .hover-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-stone-50 text-gray-800 antialiased">

    <nav
        class="fixed w-full top-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('owner.dashboard') }}" class="flex-shrink-0 flex items-center gap-2 group">
                    <img src="{{ asset('images/NaNi_Logo.png') }}" alt="NaNi Logo"
                        class="h-20 w-auto group-hover:rotate-12 transition-transform duration-300">
                </a>

                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('owner.dashboard') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-home mr-1"></i> Dashboard
                    </a>
                    <a href="{{ route('owner.orders.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-shopping-cart mr-1"></i> Orders
                    </a>
                    <a href="{{ route('owner.menu.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-utensils mr-1"></i> Menu
                    </a>
                    <a href="{{ route('owner.analytics.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-chart-line mr-1"></i> Analytics
                    </a>
                    <a href="{{ route('owner.reviews.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-star mr-1"></i> Reviews
                    </a>
                    <a href="{{ route('owner.riders.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-motorcycle mr-1"></i> Riders
                    </a>

                    <div class="ml-4 pl-4 border-l border-gray-200 flex items-center gap-3">
                        <!-- Profile Button (Active) -->
                        <a href="{{ route('owner.profile.show') }}"
                            class="text-orange-600 hover:text-orange-700 transition-colors">
                            <i class="fas fa-user-circle text-xl"></i>
                        </a>

                        <span class="text-sm font-bold text-gray-700">Admin</span>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors"
                                title="Logout">
                                <i class="fas fa-sign-out-alt text-lg"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="pt-32 pb-16 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 fade-in">
                <div
                    class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-green-100 rounded-xl">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                        <div>
                            <strong class="font-bold">Success!</strong>
                            <span class="block">{{ session('success') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 fade-in">
                <div
                    class="bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-red-100 rounded-xl">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div>
                            <strong class="font-bold">Error!</strong>
                            <span class="block">{{ session('error') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Header -->
        <div class="mb-8 fade-in">
            <div class="flex justify-between items-start">
                <div>
                    <a href="{{ route('owner.dashboard') }}"
                        class="inline-flex items-center gap-2 text-stone-600 hover:text-orange-600 font-medium transition-colors group mb-4">
                        <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                        Back to Dashboard
                    </a>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Profile Information</h1>
                    <p class="text-stone-500">View and manage your account details</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('owner.profile.edit') }}"
                        class="px-6 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white font-bold rounded-xl hover:from-orange-700 hover:to-red-700 transition-all shadow-lg shadow-orange-500/30">
                        <i class="fas fa-edit mr-2"></i>Edit Profile
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- User Information Card -->
            <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden fade-in hover-card"
                style="animation-delay: 0.1s;">
                <div class="bg-gradient-to-r from-stone-900 to-stone-800 text-white p-6">
                    <h3 class="text-xl font-bold flex items-center gap-3">
                        <div class="p-2 bg-white/10 rounded-xl">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        User Information
                    </h3>
                </div>
                <div class="p-6">
                    <!-- Profile Picture -->
                    <div class="flex items-center gap-6 mb-8">
                        @if($user->profile_picture)
                            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture"
                                class="h-28 w-28 rounded-full object-cover border-4 border-white shadow-lg">
                        @else
                            <div
                                class="h-28 w-28 bg-gradient-to-br from-stone-200 to-stone-300 rounded-full flex items-center justify-center shadow-lg">
                                <i class="fas fa-user text-stone-500 text-4xl"></i>
                            </div>
                        @endif
                        <div>
                            <h4 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h4>
                            <p class="text-stone-500">{{ $user->email }}</p>
                            <p class="text-sm text-stone-400 mt-1">
                                <i class="fas fa-shield-alt mr-1"></i>Restaurant Owner
                            </p>
                        </div>
                    </div>

                    <!-- User Details -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 p-3 bg-stone-50 rounded-xl">
                            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="flex-1">
                                <span class="text-sm text-stone-500">Full Name</span>
                                <p class="font-bold text-gray-900">{{ $user->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 bg-stone-50 rounded-xl">
                            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="flex-1">
                                <span class="text-sm text-stone-500">Email</span>
                                <p class="font-bold text-gray-900">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 bg-stone-50 rounded-xl">
                            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="flex-1">
                                <span class="text-sm text-stone-500">Phone</span>
                                <p class="font-bold text-gray-900">{{ $user->phone ?? 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Restaurant Information Card -->
            <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden fade-in hover-card"
                style="animation-delay: 0.2s;">
                <div class="bg-gradient-to-r from-stone-900 to-stone-800 text-white p-6">
                    <h3 class="text-xl font-bold flex items-center gap-3">
                        <div class="p-2 bg-white/10 rounded-xl">
                            <i class="fas fa-store"></i>
                        </div>
                        Restaurant Information
                    </h3>
                </div>
                <div class="p-6">
                    <!-- Restaurant Logo/Icon -->
                    <div class="flex items-center gap-6 mb-8">
                        <div
                            class="h-28 w-28 bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-utensils text-orange-500 text-4xl"></i>
                        </div>
                        <div>
                            <h4 class="text-2xl font-bold text-gray-900">{{ $restaurant->name }}</h4>
                            <p class="text-stone-500">Authentic Japanese Cuisine</p>
                            <p class="text-sm text-orange-500 mt-1">
                                <i class="fas fa-star mr-1"></i>NaNi Restaurant
                            </p>
                        </div>
                    </div>

                    <!-- Restaurant Details -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 p-3 bg-stone-50 rounded-xl">
                            <div class="p-2 bg-green-50 text-green-600 rounded-lg">
                                <i class="fas fa-signature"></i>
                            </div>
                            <div class="flex-1">
                                <span class="text-sm text-stone-500">Restaurant Name</span>
                                <p class="font-bold text-gray-900">{{ $restaurant->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 bg-stone-50 rounded-xl">
                            <div class="p-2 bg-green-50 text-green-600 rounded-lg">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="flex-1">
                                <span class="text-sm text-stone-500">Address</span>
                                <p class="font-bold text-gray-900">{{ $restaurant->address }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 bg-stone-50 rounded-xl">
                            <div class="p-2 bg-green-50 text-green-600 rounded-lg">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="flex-1">
                                <span class="text-sm text-stone-500">Restaurant Phone</span>
                                <p class="font-bold text-gray-900">{{ $restaurant->phone }}</p>
                            </div>
                        </div>

                        @if($restaurant->facebook_url)
                            <div class="flex items-center gap-3 p-3 bg-stone-50 rounded-xl">
                                <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                                    <i class="fab fa-facebook-f"></i>
                                </div>
                                <div class="flex-1">
                                    <span class="text-sm text-stone-500">Facebook Page</span>
                                    <a href="{{ $restaurant->facebook_url }}" target="_blank"
                                        class="font-bold text-blue-600 hover:text-blue-800 transition-colors">
                                        {{ parse_url($restaurant->facebook_url, PHP_URL_HOST) }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($restaurant->background_image)
                            <div class="mt-6 pt-6 border-t border-stone-200">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="p-2 bg-purple-50 text-purple-600 rounded-lg">
                                        <i class="fas fa-image"></i>
                                    </div>
                                    <div>
                                        <span class="text-sm font-bold text-stone-700">Welcome Page Background Image</span>
                                        <p class="text-xs text-stone-500">Current welcome page hero image</p>
                                    </div>
                                </div>
                                <div class="relative rounded-xl overflow-hidden border border-stone-200 shadow-sm">
                                    <img src="{{ asset('storage/' . $restaurant->background_image) }}"
                                        alt="Background Image" class="w-full h-48 object-cover">
                                    <div
                                        class="absolute top-3 right-3 bg-orange-500 text-white text-xs px-3 py-1 rounded-full font-bold">
                                        Active
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="mt-8 fade-in" style="animation-delay: 0.3s;">
            <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                    <div class="p-2 bg-orange-50 text-orange-600 rounded-xl">
                        <i class="fas fa-cogs"></i>
                    </div>
                    Quick Actions
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('owner.profile.edit') }}"
                        class="group flex items-center justify-between p-4 rounded-xl border border-stone-200 hover:border-orange-200 hover:bg-orange-50 transition-all">
                        <div class="flex items-center gap-3">
                            <div
                                class="p-2 bg-orange-100 text-orange-600 rounded-lg group-hover:bg-orange-200 transition-colors">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div>
                                <span class="font-bold text-gray-900">Edit Profile</span>
                                <p class="text-sm text-stone-500">Update personal information</p>
                            </div>
                        </div>
                        <i
                            class="fas fa-chevron-right text-stone-400 group-hover:text-orange-600 transition-colors"></i>
                    </a>

                    <a href="{{ route('owner.menu.index') }}"
                        class="group flex items-center justify-between p-4 rounded-xl border border-stone-200 hover:border-green-200 hover:bg-green-50 transition-all">
                        <div class="flex items-center gap-3">
                            <div
                                class="p-2 bg-green-100 text-green-600 rounded-lg group-hover:bg-green-200 transition-colors">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <div>
                                <span class="font-bold text-gray-900">Manage Menu</span>
                                <p class="text-sm text-stone-500">Add or edit menu items</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-stone-400 group-hover:text-green-600 transition-colors"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Auto-refresh logic
        setTimeout(function () {
            window.location.reload();
        }, 60000); // Refresh every minute

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
                            <p class="text-gray-700">Are you sure you want to logout from your rider account?</p>
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
    </script>
</body>

</html>