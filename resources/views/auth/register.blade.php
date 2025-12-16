<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - NaNi</title>
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

        .fade-in {
            animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Password Validation Styles */
        .requirement.valid i {
            color: #16a34a;
        }

        /* Green-600 */
        .requirement.valid span {
            color: #16a34a;
            text-decoration: line-through;
            opacity: 0.7;
        }

        .requirement.invalid i {
            color: #d1d5db;
        }

        /* Gray-300 */
        .requirement.invalid span {
            color: #6b7280;
        }
    </style>
</head>

<body class="bg-stone-900 antialiased text-gray-800">

    <nav
        class="fixed w-full top-0 z-50 transition-all duration-300 bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="/" class="flex-shrink-0 flex items-center gap-2 group">
                    <img src="{{ asset('images/NaNi_Logo.png') }}" alt="NaNi Logo"
                        class="h-20 w-auto group-hover:rotate-12 transition-transform duration-300">
                </a>

                <div class="flex items-center space-x-2 sm:space-x-6">
                    <a href="/"
                        class="text-gray-600 hover:text-orange-600 font-medium text-sm transition-colors duration-200">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                    <a href="/login"
                        class="text-orange-600 font-medium text-sm hover:text-orange-700 transition-colors">
                        Log In
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="relative min-h-screen flex items-center justify-center py-20 px-4 sm:px-6 lg:px-8">

        <div class="absolute inset-0 z-0">
            <div
                class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1553621042-f6e147245754?q=80&w=1925&auto=format&fit=crop')] bg-cover bg-center">
            </div>
            <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/60 to-stone-900/90"></div>
        </div>

        <div class="max-w-md w-full relative z-10 fade-in mt-10">
            <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl p-8 sm:p-10 border border-white/20">

                <div class="text-center mb-8">
                    <div class="flex justify-center mb-4 transition-all duration-300 transform hover:-translate-y-1">
                        <img src="{{ asset('images/NaNi_Slogan.png') }}" alt="NaNi Slogan"
                            class="h-70 w-auto drop-shadow-2xl animate-float">
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900">Join NaNi</h2>
                    <p class="mt-2 text-sm text-gray-600">Create your account to order</p>
                </div>

                <form class="space-y-5" action="/register" method="POST">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 ml-1 mb-1">Full Name</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input id="name" name="name" type="text" required
                                class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 sm:text-sm transition-all"
                                placeholder="Your full name">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 ml-1 mb-1">Email
                            Address</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input id="email" name="email" type="email" required
                                class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 sm:text-sm transition-all"
                                placeholder="your@email.com">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 ml-1 mb-1">Password</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="password" name="password" type="password" required
                                class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 sm:text-sm transition-all"
                                placeholder="••••••••" oninput="validatePassword(this.value)">
                        </div>

                        <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 mt-3 transition-all duration-300">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Password
                                Strength</p>
                            <div class="space-y-2 text-xs">
                                <div class="requirement invalid flex items-center gap-2" id="req-length">
                                    <i class="fas fa-circle text-[8px]"></i> <span>At least 8 characters</span>
                                </div>
                                <div class="requirement invalid flex items-center gap-2" id="req-uppercase">
                                    <i class="fas fa-circle text-[8px]"></i> <span>One uppercase letter</span>
                                </div>
                                <div class="requirement invalid flex items-center gap-2" id="req-lowercase">
                                    <i class="fas fa-circle text-[8px]"></i> <span>One lowercase letter</span>
                                </div>
                                <div class="requirement invalid flex items-center gap-2" id="req-number">
                                    <i class="fas fa-circle text-[8px]"></i> <span>One number</span>
                                </div>
                                <div class="requirement invalid flex items-center gap-2" id="req-special">
                                    <i class="fas fa-circle text-[8px]"></i> <span>One special char (@$!%*#?&)</span>
                                </div>
                            </div>
                        </div>

                        @error('password')
                            <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation"
                            class="block text-sm font-medium text-gray-700 ml-1 mb-1">Confirm Password</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-check-circle text-gray-400"></i>
                            </div>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 sm:text-sm transition-all"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 shadow-lg hover:shadow-orange-500/30 transition-all duration-200 hover:-translate-y-0.5 mt-4">
                        Create Account
                    </button>

                    <!-- Add this after the existing register form, before the "Already have an account?" section -->
                    <div class="mt-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-gray-500 rounded-full">Or sign up with</span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('google.login') }}"
                                class="w-full flex items-center justify-center gap-3 px-4 py-3 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                <img src="https://www.google.com/favicon.ico" alt="Google" class="w-5 h-5">
                                <span>Sign up with Google</span>
                            </a>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-sm text-gray-600">
                            Already have an account?
                            <a href="/login" class="font-bold text-orange-600 hover:text-orange-500 transition-colors">
                                Sign in here
                            </a>
                        </p>
                    </div>
                </form>
            </div>
            <p class="text-center text-white/40 text-xs mt-8">
                &copy; {{ date('Y') }} NaNi Restaurant. All rights reserved.
            </p>
        </div>
    </div>

    <script>
        function validatePassword(password) {
            const requirements = {
                length: document.getElementById('req-length'),
                uppercase: document.getElementById('req-uppercase'),
                lowercase: document.getElementById('req-lowercase'),
                number: document.getElementById('req-number'),
                special: document.getElementById('req-special')
            };

            const updateStatus = (element, isValid) => {
                if (isValid) {
                    element.classList.remove('invalid');
                    element.classList.add('valid');
                    element.querySelector('i').className = 'fas fa-check-circle text-[10px]';
                } else {
                    element.classList.remove('valid');
                    element.classList.add('invalid');
                    element.querySelector('i').className = 'fas fa-circle text-[8px]';
                }
            };

            updateStatus(requirements.length, password.length >= 8);
            updateStatus(requirements.uppercase, /[A-Z]/.test(password));
            updateStatus(requirements.lowercase, /[a-z]/.test(password));
            updateStatus(requirements.number, /[0-9]/.test(password));
            updateStatus(requirements.special, /[@$!%*#?&]/.test(password));
        }
    </script>
</body>

</html>