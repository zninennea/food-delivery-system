<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Rider - NaNi Owner</title>
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
                        class="text-orange-600 bg-orange-50 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-motorcycle mr-1"></i> Riders
                    </a>

                    <div class="ml-4 pl-4 border-l border-gray-200 flex items-center gap-3">
                        <!-- Profile Button (Active) -->
                        <a href="{{ route('owner.profile.show') }}"
                            class="text-grey-600 hover:text-orange-700 transition-colors">
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

        <!-- Header -->
        <div class="mb-8 fade-in">
            <div class="flex justify-between items-start">
                <div>
                    <a href="{{ route('owner.riders.index') }}"
                        class="inline-flex items-center gap-2 text-stone-600 hover:text-orange-600 font-medium transition-colors group mb-4">
                        <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                        Back to Riders
                    </a>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Edit Rider</h1>
                    <p class="text-stone-500">Update {{ $rider->name }}'s information</p>
                </div>
                <div class="flex items-center gap-3">
                    @if($rider->profile_picture)
                        <img src="{{ asset('storage/' . $rider->profile_picture) }}" alt="{{ $rider->name }}"
                            class="h-16 w-16 rounded-full object-cover border-4 border-white shadow-lg">
                    @else
                        <div
                            class="h-16 w-16 bg-gradient-to-br from-stone-200 to-stone-300 rounded-full flex items-center justify-center border-4 border-white shadow-lg">
                            <i class="fas fa-user text-stone-400 text-2xl"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden fade-in"
            style="animation-delay: 0.1s;">
            <form action="{{ route('owner.riders.update', $rider) }}" method="POST" enctype="multipart/form-data"
                class="p-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Personal Information Card -->
                    <div class="space-y-6">
                        <div class="bg-gradient-to-r from-stone-50 to-white p-6 rounded-2xl border border-stone-200">
                            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <div class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                Personal Information
                            </h3>

                            <div class="space-y-5">
                                <div>
                                    <label for="name" class="block text-sm font-bold text-stone-700 mb-2">
                                        Full Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $rider->name) }}"
                                        required
                                        class="w-full px-4 py-3 border border-stone-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-2 flex items-center gap-2">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-bold text-stone-700 mb-2">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="email" id="email"
                                        value="{{ old('email', $rider->email) }}" required
                                        class="w-full px-4 py-3 border border-stone-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-2 flex items-center gap-2">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-bold text-stone-700 mb-2">
                                        Phone Number <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone', $rider->phone) }}"
                                        required
                                        class="w-full px-4 py-3 border border-stone-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                                    @error('phone')
                                        <p class="text-red-500 text-sm mt-2 flex items-center gap-2">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Current Profile Picture -->
                                @if($rider->profile_picture)
                                    <div class="mt-4 p-4 bg-stone-50 rounded-xl border border-stone-200">
                                        <p class="text-sm font-medium text-stone-700 mb-2">Current Profile Picture:</p>
                                        <div class="relative inline-block">
                                            <img src="{{ asset('storage/' . $rider->profile_picture) }}"
                                                alt="Current Profile"
                                                class="h-24 w-24 rounded-full object-cover border-4 border-white shadow-md">
                                            <div
                                                class="absolute -top-2 -right-2 bg-orange-500 text-white text-xs px-2 py-1 rounded-full">
                                                Current
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div>
                                    <label for="profile_picture" class="block text-sm font-bold text-stone-700 mb-2">
                                        Update Profile Picture
                                    </label>
                                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*"
                                        class="w-full px-4 py-3 border border-stone-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-stone-100 file:text-stone-700 hover:file:bg-stone-200">
                                    <p class="text-xs text-stone-500 mt-2">
                                        Leave empty to keep current picture
                                    </p>
                                    @error('profile_picture')
                                        <p class="text-red-500 text-sm mt-2 flex items-center gap-2">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle & Account Information Card -->
                    <div class="space-y-6">
                        <div class="bg-gradient-to-r from-stone-50 to-white p-6 rounded-2xl border border-stone-200">
                            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <div class="p-2 bg-green-50 text-green-600 rounded-xl">
                                    <i class="fas fa-motorcycle"></i>
                                </div>
                                Vehicle & Status
                            </h3>

                            <div class="space-y-5">
                                <div>
                                    <label for="vehicle_type" class="block text-sm font-bold text-stone-700 mb-2">
                                        Vehicle Type <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="vehicle_type" id="vehicle_type"
                                        value="{{ old('vehicle_type', $rider->vehicle_type) }}" required
                                        class="w-full px-4 py-3 border border-stone-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all"
                                        placeholder="e.g., Motorcycle, Bicycle">
                                    @error('vehicle_type')
                                        <p class="text-red-500 text-sm mt-2 flex items-center gap-2">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="vehicle_plate" class="block text-sm font-bold text-stone-700 mb-2">
                                        Vehicle Plate <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="vehicle_plate" id="vehicle_plate"
                                        value="{{ old('vehicle_plate', $rider->vehicle_plate) }}" required
                                        class="w-full px-4 py-3 border border-stone-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                                    @error('vehicle_plate')
                                        <p class="text-red-500 text-sm mt-2 flex items-center gap-2">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="status" class="block text-sm font-bold text-stone-700 mb-2">
                                        Status <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status" id="status" required
                                        class="w-full px-4 py-3 border border-stone-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all appearance-none bg-white">
                                        <option value="active" {{ $rider->status == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ $rider->status == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                    @error('status')
                                        <p class="text-red-500 text-sm mt-2 flex items-center gap-2">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Current Driver's License -->
                                @if($rider->drivers_license)
                                    <div class="mt-4 p-4 bg-stone-50 rounded-xl border border-stone-200">
                                        <p class="text-sm font-medium text-stone-700 mb-2">Current Driver's License:</p>
                                        <div class="relative">
                                            <img src="{{ asset('storage/' . $rider->drivers_license) }}"
                                                alt="Current License"
                                                class="h-32 w-full object-contain rounded-lg border border-stone-200">
                                            <div
                                                class="absolute top-3 right-3 bg-orange-500 text-white text-xs px-3 py-1 rounded-full font-bold">
                                                Current
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div>
                                    <label for="drivers_license" class="block text-sm font-bold text-stone-700 mb-2">
                                        Update Driver's License
                                    </label>
                                    <input type="file" name="drivers_license" id="drivers_license" accept="image/*"
                                        class="w-full px-4 py-3 border border-stone-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-stone-100 file:text-stone-700 hover:file:bg-stone-200">
                                    <p class="text-xs text-stone-500 mt-2">
                                        Leave empty to keep current license
                                    </p>
                                    @error('drivers_license')
                                        <p class="text-red-500 text-sm mt-2 flex items-center gap-2">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Password Update Card -->
                        <div class="bg-gradient-to-r from-stone-50 to-white p-6 rounded-2xl border border-stone-200">
                            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <div class="p-2 bg-purple-50 text-purple-600 rounded-xl">
                                    <i class="fas fa-key"></i>
                                </div>
                                Change Password
                            </h3>

                            <div class="space-y-4">
                                <p class="text-sm text-stone-500 mb-3">
                                    Leave password fields blank to keep current password
                                </p>

                                <div>
                                    <label for="password" class="block text-sm font-bold text-stone-700 mb-2">
                                        New Password
                                    </label>
                                    <input type="password" name="password" id="password"
                                        class="w-full px-4 py-3 border border-stone-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all"
                                        placeholder="Enter new password">
                                    @error('password')
                                        <p class="text-red-500 text-sm mt-2 flex items-center gap-2">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation"
                                        class="block text-sm font-bold text-stone-700 mb-2">
                                        Confirm New Password
                                    </label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="w-full px-4 py-3 border border-stone-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all"
                                        placeholder="Confirm new password">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 pt-8 border-t border-stone-200 flex justify-end gap-4">
                    <a href="{{ route('owner.riders.index') }}"
                        class="px-6 py-3 bg-stone-200 text-stone-700 font-bold rounded-xl hover:bg-stone-300 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg shadow-blue-500/30">
                        <i class="fas fa-save mr-2"></i>
                        Update Rider
                    </button>
                </div>
            </form>
        </div>

        <!-- Current Information Summary -->
        <div class="mt-8 fade-in" style="animation-delay: 0.2s;">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gradient-to-br from-stone-50 to-white p-4 rounded-2xl border border-stone-200">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                            <i class="fas fa-motorcycle"></i>
                        </div>
                        <div>
                            <p class="text-xs text-stone-500">Vehicle</p>
                            <p class="font-bold text-gray-900">{{ $rider->vehicle_type }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-stone-50 to-white p-4 rounded-2xl border border-stone-200">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-green-50 text-green-600 rounded-lg">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div>
                            <p class="text-xs text-stone-500">Status</p>
                            <span
                                class="px-2 py-1 text-xs font-bold rounded-full 
                                {{ $rider->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($rider->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-stone-50 to-white p-4 rounded-2xl border border-stone-200">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-purple-50 text-purple-600 rounded-lg">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div>
                            <p class="text-xs text-stone-500">Member Since</p>
                            <p class="font-bold text-gray-900">{{ $rider->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
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