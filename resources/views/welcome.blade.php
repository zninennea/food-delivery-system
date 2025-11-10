<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NaNi - Japanese Restaurant</title>
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
                        <img src="{{ asset('images/nani-logo.png') }}" alt="NaNi Logo" class="h-10 w-10 mr-3">
                        <div>
                            <a href="/" class="text-xl font-bold text-gray-800">NaNi</a>
                            <p class="text-xs text-gray-500 -mt-1">Japanese Restaurant</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/login"
                        class="text-gray-700 hover:text-orange-500 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-sign-in-alt mr-1"></i>Login
                    </a>
                    <a href="/register"
                        class="bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600 text-sm font-medium">
                        <i class="fas fa-user-plus mr-1"></i>Register
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-cover bg-center h-96"
        style="background-image: url('https://images.unsplash.com/photo-1555939594-58d7cb5f3adf?q=80&w=2070');">
        <div class="bg-white bg-opacity-50 h-full flex items-center justify-center">
            <div class="text-center text-white">
                <!-- Logo in Hero Section -->
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('images/nani-logo.png') }}" alt="NaNi Logo" class="h-20 w-20">
                </div>
                <h2 class="text-4xl text-orange-500 font-bold mb-2">Welcome to NaNi</h2>
                <p class="text-xl text-orange-500 mb-6">Authentic Japanese Cuisine in Davao City</p>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="bg-orange-200 max-w-8xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h3 class="text-3xl font-bold text-gray-900">Why Choose NaNi?</h3>
            <p class="mt-4 text-lg text-gray-600">Experience the best Japanese cuisine in town</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="text-center">
                <div class="bg-blue-100 rounded-full p-4 inline-block">
                    <i class="fas fa-utensils text-blue-600 text-2xl"></i>
                </div>
                <h4 class="mt-4 text-xl font-semibold text-gray-900">Fresh Ingredients</h4>
                <p class="mt-2 text-gray-600">We use only the freshest ingredients for authentic Japanese flavors</p>
            </div>

            <!-- Feature 2 -->
            <div class="text-center">
                <div class="bg-green-100 rounded-full p-4 inline-block">
                    <i class="fas fa-shipping-fast text-green-600 text-2xl"></i>
                </div>
                <h4 class="mt-4 text-xl font-semibold text-gray-900">Fast Delivery</h4>
                <p class="mt-2 text-gray-600">Quick and reliable delivery to your doorstep</p>
            </div>

            <!-- Feature 3 -->
            <div class="text-center">
                <div class="bg-purple-100 rounded-full p-4 inline-block">
                    <i class="fas fa-medal text-purple-600 text-2xl"></i>
                </div>
                <h4 class="mt-4 text-xl font-semibold text-gray-900">Quality Guaranteed</h4>
                <p class="mt-2 text-gray-600">Consistent quality and authentic Japanese recipes</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('images/nani-logo.png') }}" alt="NaNi Logo" class="h-20 w-20">
                </div>
                <h4 class="text-lg text-orange-500 font-semibold">NaNi Restaurant</h4>
                <p class="mt-2 text-black">JP Laurel Ave, Davao City</p>
                <p class="text-black">09194445566</p>
                <div class="mt-4">
                    <a href="#" class="text-gray-400 hover:text-white mx-2">
                        <i class="fab fa-facebook text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white mx-2">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>