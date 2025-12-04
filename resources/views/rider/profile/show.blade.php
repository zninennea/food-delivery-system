<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - NaNi Rider</title>
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
                        <p class="text-xs text-gray-500 -mt-1">My Profile</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('rider.dashboard') }}" 
                       class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-arrow-left mr-1"></i>Back to Dashboard
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

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-orange-600">Profile Information</h2>
                <p class="text-gray-600">Contact the restaurant owner to update your profile information</p>
            </div>

            <div class="p-6">
                <div class="flex items-center space-x-6 mb-8">
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/'. $user->profile_picture) }}" alt="Profile Picture" class="h-24 w-24 rounded-full object-cover">
                    @else
                        <div class="h-24 w-24 bg-gray-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-gray-400 text-3xl"></i>
                        </div>
                    @endif
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h3>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-2">
                            {{ ucfirst($user->status) }} Rider
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Personal Information -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-medium text-gray-900">Personal Information</h4>
                        
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

                    <!-- Vehicle Information -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-medium text-gray-900">Vehicle Information</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Vehicle Type</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $user->vehicle_type ?? 'Not specified' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Vehicle Plate</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $user->vehicle_plate ?? 'Not specified' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Driver's License</label>
                            @if($user->drivers_license)
                                <a href="{{ asset('storage/'. $user->drivers_license) }}" target="_blank" 
                                   class="mt-1 text-blue-600 hover:text-blue-800 inline-flex items-center">
                                    <i class="fas fa-external-link-alt mr-2"></i>View License
                                </a>
                            @else
                                <p class="mt-1 text-lg text-gray-900">Not uploaded</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Note for riders -->
                <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Profile Updates</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>To update your profile information, vehicle details, or upload new documents, please contact the restaurant owner directly.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>