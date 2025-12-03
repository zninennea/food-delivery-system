<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NaNi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease-out;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .scale-in {
            transform: scale(0.9);
            opacity: 0;
            transition: all 0.5s ease-out;
        }

        .scale-in.visible {
            transform: scale(1);
            opacity: 1;
        }

        .hover-lift {
            transition: transform 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #f97316 0%, #fb923c 100%);
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <!-- NaNi Logo -->
                    <div class="flex items-center">
                        <div class="flex justify-center mb-4">
                            <img src="{{ asset('images/nani.png') }}" alt="NaNi Logo" class="h-20 w-20">
                        </div>
                        <div>
                            <a href="/" class="text-xl font-bold text-gray-800">
                                {{ $restaurant->name ?? 'NaNi' }}
                            </a>
                            <p class="text-xs text-gray-500 -mt-1">NaNi's On Your Plate?</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/login"
                        class="text-gray-700 hover:text-orange-500 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-sign-in-alt mr-1"></i>Login
                    </a>
                    <a href="/register"
                        class="bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600 text-sm font-medium transition-colors duration-200 shadow-sm">
                        <i class="fas fa-user-plus mr-1"></i>Register
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative h-screen flex items-center justify-center overflow-hidden">
        @if($restaurant->background_image)
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-400/90 to-white-600/90 z-1"></div>
            <div class="absolute inset-0 bg-cover bg-center z-0"
                style="background-image: url('{{ asset('storage/' . $restaurant->background_image) }}');">
            </div>
        @else
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-400/90 to-white-600/90 z-1"></div>
            <div class="absolute inset-0 bg-cover bg-center z-0"
                style="background-image: url('{{ asset('images/ac.gif') }}');">
            </div>
        @endif

        <div class="relative z-10 text-center text-white max-w-4xl mx-auto px-4 scale-in">
            <div class="flex justify-center mb-6">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('images/nani.png') }}" alt="NaNi Logo" class="h-20 w-20">
                </div>
            </div>
            <h1 class="text-5xl md:text-6xl font-bold mb-4">Welcome to {{ $restaurant->name ?? 'NaNi' }}</h1>
            <p class="text-xl md:text-2xl mb-8 font-light">Authentic Japanese Cuisine in Davao City</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('menu.public') }}"
                    class="bg-white text-orange-500 px-8 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors duration-200 shadow-lg hover-lift">
                    View Our Menu
                </a>
            </div>
        </div>

        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-10">
            <div class="animate-bounce">
                <i class="fas fa-chevron-down text-white text-xl"></i>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Why Choose {{ $restaurant->name ?? 'NaNi' }}?</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Experience the best Japanese cuisine in town with our
                    commitment to quality and authenticity</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- Feature 1 -->
                <div class="text-center fade-in hover-lift">
                    <div class="bg-orange-50 rounded-full p-6 inline-block mb-6">
                        <i class="fas fa-utensils text-orange-500 text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-900 mb-4">Fresh Ingredients</h3>
                    <p class="text-gray-600 leading-relaxed">We use only the freshest ingredients sourced locally and
                        imported from Japan for authentic flavors</p>
                </div>

                <!-- Feature 2 -->
                <div class="text-center fade-in hover-lift">
                    <div class="bg-orange-50 rounded-full p-6 inline-block mb-6">
                        <i class="fas fa-shipping-fast text-orange-500 text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-900 mb-4">Fast Delivery</h3>
                    <p class="text-gray-600 leading-relaxed">Quick and reliable delivery service ensuring your food
                        arrives fresh and hot at your doorstep</p>
                </div>

                <!-- Feature 3 -->
                <div class="text-center fade-in hover-lift">
                    <div class="bg-orange-50 rounded-full p-6 inline-block mb-6">
                        <i class="fas fa-medal text-orange-500 text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-900 mb-4">Quality Guaranteed</h3>
                    <p class="text-gray-600 leading-relaxed">Consistent quality and authentic Japanese recipes prepared
                        by our skilled chefs with years of experience</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 gradient-bg">
        <div class="max-w-4xl mx-auto text-center px-4 fade-in">
            <h2 class="text-4xl font-bold text-white mb-6">Ready to Experience Authentic Japanese Cuisine?</h2>
            <p class="text-xl text-orange-100 mb-8 max-w-2xl mx-auto">Join our growing community of food lovers and
                discover why {{ $restaurant->name ?? 'NaNi' }} is Davao's favorite Japanese restaurant</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register"
                    class="bg-white text-orange-500 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors duration-200 shadow-lg hover-lift">
                    Create an Account
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <div class="flex justify-center mb-4">
                        <img src="{{ asset('images/nani.png') }}" alt="NaNi Logo" class="h-20 w-20">
                    </div>
                </div>
                <h4 class="text-xl font-semibold mb-2">{{ $restaurant->name ?? 'NaNi' }}</h4>
                <p class="text-gray-400 mb-1">{{ $restaurant->address ?? 'JP Laurel Ave, Davao City' }}</p>
                <p class="text-gray-400 mb-6">{{ $restaurant->phone ?? '09194445566' }}</p>

                <!-- Social Media Links -->
                <div class="flex justify-center space-x-6 mb-6">
                    @if($restaurant->facebook_url)
                        <a href="{{ $restaurant->facebook_url }}" target="_blank"
                            class="text-gray-400 hover:text-white transition-colors duration-200">
                            <i class="fab fa-facebook text-2xl"></i>
                        </a>
                    @endif
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                        <i class="fab fa-instagram text-2xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                        <i class="fab fa-twitter text-2xl"></i>
                    </a>
                </div>

                <!-- Business Hours -->
                <div class="mb-6">
                    <h5 class="text-lg font-medium mb-2">Business Hours</h5>
                    <p class="text-gray-400">Monday - Sunday: 10:00 AM - 10:00 PM</p>
                </div>

                <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} {{ $restaurant->name ?? 'NaNi Restaurant' }}.
                    All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Animation on scroll
        document.addEventListener('DOMContentLoaded', function () {
            // Initial animations
            setTimeout(() => {
                document.querySelector('.scale-in').classList.add('visible');
            }, 300);

            // Scroll animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, observerOptions);

            // Observe all fade-in elements
            document.querySelectorAll('.fade-in').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
</body>

</html>