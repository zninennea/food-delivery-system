<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NaNi - Authentic Japanese Cuisine</title>
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

        /* Custom Animations */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .scale-in {
            transform: scale(0.95);
            opacity: 0;
            transition: all 0.6s ease-out;
        }

        .scale-in.visible {
            transform: scale(1);
            opacity: 1;
        }

        .hover-card {
            transition: all 0.3s ease;
        }

        .hover-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>

<body class="bg-stone-50 text-gray-800 antialiased selection:bg-orange-200 selection:text-orange-900">

    <nav
        class="fixed w-full top-0 z-50 transition-all duration-300 bg-white/100 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="/" class="flex-shrink-0 flex items-center gap-2 group">
                    <img src="https://i.imgur.com/vPOu1H2.png" alt="NaNi Icon"
                        class="h-20 w-auto group-hover:rotate-12 transition-transform duration-300">
                </a>

                <div class="flex items-center space-x-2 sm:space-x-6">
                    <a href="/login"
                        class="text-orange-600 hover:text-orange-700 font-medium text-sm transition-colors duration-200">
                        <i class="fas fa-sign-in-alt mr-2"></i>Log In
                    </a>
                    <a href="/register"
                        class="bg-orange-600 text-white px-5 py-2.5 rounded-full hover:bg-orange-700 text-sm font-medium transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="fas fa-utensils mr-2 text-xs"></i>Order Now
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <section class="relative h-screen min-h-[600px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            @if(isset($restaurant) && $restaurant->background_image)
                <div class="absolute inset-0 bg-cover bg-center"
                    style="background-image: url('{{ asset('storage/' . $restaurant->background_image) }}');"></div>
            @else
                <div
                    class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1579027989536-b7b1f875659b?q=80&w=2070&auto=format&fit=crop')] bg-cover bg-center">
                </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-stone-900"></div>
        </div>

        <div class="relative z-10 w-full max-w-4xl px-4 scale-in mt-16">
            <div
                class="bg-gradient-to-b from-black/80 to-black/60 backdrop-blur-sm shadow-2xl rounded-3xl p-8 sm:p-12 border border-white/10 text-center text-white relative overflow-hidden">

                <div
                    class="absolute top-0 left-1/2 -translate-x-1/2 w-64 h-64 bg-orange-500/20 blur-[100px] rounded-full pointer-events-none">
                </div>

                <div class="relative z-10">
                    <div class="flex justify-center mb-4 transition-all duration-300 transform hover:-translate-y-1">
                        <img src="https://i.imgur.com/rjPEil9.png" alt="NaNi Text"
                            class="h-30 w-auto drop-shadow-2xl animate-float">
                    </div>

                    <p class="text-orange-500 text-lg sm:text-lg font-bold tracking-[0.4em] uppercase mb-3">Welcome to
                        {{ $restaurant->name ?? 'NaNi' }}
                    </p>

                    <div class="flex items-center justify-center gap-4 mb-8 opacity-90">
                        <div class="h-px w-12 bg-gradient-to-r from-transparent via-orange-400 to-transparent"></div>
                        <p class="text-lg sm:text-xl font-light italic text-gray-200">Authentic Japanese Cuisine in
                            Davao City</p>
                        <div class="h-px w-12 bg-gradient-to-r from-transparent via-orange-400 to-transparent"></div>
                    </div>

                    <div class="flex justify-center">
                        <a href="{{ route('menu.public') }}"
                            class="group relative px-8 py-4 bg-orange-600 text-white rounded-xl font-bold overflow-hidden transition-all hover:scale-105 shadow-[0_0_20px_rgba(234,88,12,0.5)]">
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-orange-600 to-red-600 opacity-100 group-hover:opacity-90 transition-opacity">
                            </div>
                            <span class="relative flex items-center gap-3">
                                View Our Menu
                                <i
                                    class="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 z-10 animate-bounce">
            <a href="#features" class="text-white/50 hover:text-white transition-colors cursor-pointer">
                <i class="fas fa-chevron-down text-2xl"></i>
            </a>
        </div>
    </section>

    <section id="features" class="py-24 bg-stone-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in">
                <span class="text-orange-600 font-bold tracking-wider uppercase text-sm">Experience Excellence</span>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mt-2 mb-6">Why Choose <span
                        class="text-orange-600">NaNi</span>?</h2>
                <div class="w-24 h-1 bg-orange-500 mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 sm:gap-12">
                <div
                    class="bg-white p-8 rounded-2xl shadow-sm hover-card border border-stone-100 fade-in text-center group">
                    <div
                        class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-orange-500 transition-colors duration-300">
                        <i class="fas fa-leaf text-2xl text-orange-500 group-hover:text-white transition-colors"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Fresh Ingredients</h3>
                    <p class="text-gray-500 leading-relaxed">Sourced daily from local markets and imported directly from
                        Japan to ensure the most authentic taste.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm hover-card border border-stone-100 fade-in text-center group"
                    style="transition-delay: 100ms;">
                    <div
                        class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-orange-500 transition-colors duration-300">
                        <i
                            class="fas fa-shipping-fast text-2xl text-orange-500 group-hover:text-white transition-colors"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Fast Delivery</h3>
                    <p class="text-gray-500 leading-relaxed">Quick and reliable delivery service ensuring your food
                        arrives fresh and hot at your doorstep.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm hover-card border border-stone-100 fade-in text-center group"
                    style="transition-delay: 200ms;">
                    <div
                        class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-orange-500 transition-colors duration-300">
                        <i class="fas fa-award text-2xl text-orange-500 group-hover:text-white transition-colors"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Master Chefs</h3>
                    <p class="text-gray-500 leading-relaxed">Prepared by chefs with decades of experience in the art of
                        traditional Japanese culinary skills.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="relative py-28 bg-fixed bg-cover bg-center"
        style="background-image: url('https://images.unsplash.com/photo-1553621042-f6e147245754?q=80&w=1925&auto=format&fit=crop');">
        <div class="absolute inset-0 bg-black/70"></div>
        <div class="relative z-10 max-w-4xl mx-auto text-center px-4 fade-in">
            <h2 class="text-4xl md:text-6xl font-bold text-white mb-6">Taste the Tradition</h2>
            <p class="text-xl text-gray-300 mb-10 max-w-2xl mx-auto font-light">Join our community of food lovers. Sign
                up today to order online, and discover why {{ $restaurant->name ?? 'NaNi' }} is Davao's favorite
                Japanese restaurant.</p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register"
                    class="bg-orange-600 text-white px-10 py-4 rounded-full font-bold text-lg hover:bg-orange-700 transition-all duration-300 shadow-lg hover:shadow-orange-500/50 transform hover:-translate-y-1">
                    Create Account
                </a>
                <a href="/login"
                    class="bg-transparent border-2 border-white text-white px-10 py-4 rounded-full font-bold text-lg hover:bg-white hover:text-black transition-all duration-300">
                    <i class="fas fa-sign-in-alt mr-1"></i>Login
                </a>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-white pt-20 pb-10 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-16 border-b border-gray-800 pb-12">
                <div class="text-center md:text-left">
                    <a href="/" class="flex-shrink-0 flex items-center gap-2 group">
                        <img src="https://i.imgur.com/vPOu1H2.png" alt="NaNi Icon"
                            class="h-20 w-auto group-hover:rotate-12 transition-transform duration-300">
                    </a>
                    <p class="text-gray-400 leading-relaxed mb-6">Bringing the authentic flavors of Japan to the heart
                        of Davao City. Dedicated to freshness, quality, and tradition.</p>
                    <div class="flex justify-center md:justify-start space-x-4">
                        <a href="{{ $restaurant->facebook_url ?? '#' }}"
                            class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-blue-600 transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-pink-600 transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-blue-400 transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>

                <div class="text-center">
                    <h4 class="text-xl font-bold mb-6 text-white">Contact Us</h4>
                    <ul class="space-y-4 text-gray-400">
                        <li class="flex items-center justify-center gap-3">
                            <i class="fas fa-map-marker-alt text-orange-500"></i>
                            <span>{{ $restaurant->address ?? 'JP Laurel Ave, Davao City' }}</span>
                        </li>
                        <li class="flex items-center justify-center gap-3">
                            <i class="fas fa-phone-alt text-orange-500"></i>
                            <span>{{ $restaurant->phone ?? '0919 444 5566' }}</span>
                        </li>
                        <li class="flex items-center justify-center gap-3">
                            <i class="fas fa-envelope text-orange-500"></i>
                            <span>contact@nani.com</span>
                        </li>
                    </ul>
                </div>

                <div class="text-center md:text-right">
                    <h4 class="text-xl font-bold mb-6 text-white">Opening Hours</h4>
                    <ul class="space-y-3 text-gray-400">
                        <li class="flex md:justify-end justify-between">
                            <span>Monday - Friday</span>
                            <span class="ml-4 text-white">10:00 AM - 10:00 PM</span>
                        </li>
                        <li class="flex md:justify-end justify-between">
                            <span>Saturday</span>
                            <span class="ml-4 text-white">09:00 AM - 11:00 PM</span>
                        </li>
                        <li class="flex md:justify-end justify-between">
                            <span>Sunday</span>
                            <span class="ml-4 text-white">09:00 AM - 10:00 PM</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="text-center text-gray-600 text-sm">
                <p>&copy; {{ date('Y') }} {{ $restaurant->name ?? 'NaNi Restaurant' }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initial Hero Animation
            setTimeout(() => {
                document.querySelector('.scale-in').classList.add('visible');
            }, 100);

            // Intersection Observer for scroll animations
            const observerOptions = { threshold: 0.1, rootMargin: '0px' };
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
        });
    </script>
</body>

</html>