<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NaNi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>


<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <!-- NaNi Logo -->
                    <div class="flex items-center">
                        <img src="{{ asset('images/nani-logo.png') }}" alt="NaNi Logo" class="h-10 w-10 mr-5">
                        <div>
                            <a href="/" class="text-xl font-bold text-gray-800">NaNi</a>
                            <p class="text-xs text-gray-500 -mt-1">Japanese Restaurant</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/" class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <a href="/login" class="text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-sign-in-alt mr-1"></i>Login
                    </a>
                    <a href="/register"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-user-plus mr-1"></i>Register
                    </a>
                </div>
            </div>
        </div>
    </nav>


    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <!-- Logo -->
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('images/nani-logo.png') }}" alt="NaNi Logo" class="h-25 w-25">
                </div>
                <h2 class="text-3xl font-bold text-gray-900">Sign in to your account</h2>
                <p class="mt-2 text-gray-600">Welcome back! Please sign in to your account.</p>
            </div>


            <!-- Error Message Container -->
            @if ($errors->has('email') || session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                {{ $errors->first('email') ?: session('error') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif


            <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input id="email" name="email" type="email" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                            placeholder="your@email.com"
                            value="{{ old('email') }}">
                    </div>


                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input id="password" name="password" type="password" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                            placeholder="••••••••">
                    </div>
                </div>


                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Sign In
                    </button>
                </div>


                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Don't have an account?
                        <a href="/register" class="font-medium text-orange-600 hover:text-orange-500">
                            Create one here
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>


</html>


