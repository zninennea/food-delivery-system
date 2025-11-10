<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Dashboard - NaNi</title>
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
                            <p class="text-xs text-gray-500 -mt-1">Rider Dashboard</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Welcome, {{ Auth::user()->name }}! (Rider)</span>
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

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Rider Dashboard</h1>
            <p class="text-gray-600">Welcome to your rider dashboard! Manage your deliveries here.</p>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <i class="fas fa-clock text-yellow-600 text-2xl mb-2"></i>
                    <h3 class="font-semibold">Pending Deliveries</h3>
                    <p class="text-2xl font-bold mt-2">0</p>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg">
                    <i class="fas fa-motorcycle text-blue-600 text-2xl mb-2"></i>
                    <h3 class="font-semibold">Active Deliveries</h3>
                    <p class="text-2xl font-bold mt-2">0</p>
                </div>

                <div class="bg-green-50 p-4 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-2xl mb-2"></i>
                    <h3 class="font-semibold">Completed Today</h3>
                    <p class="text-2xl font-bold mt-2">0</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>