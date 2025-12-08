<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - NaNi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        /* Fix for gradient background */
        .profile-gradient-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 32px;
            /* Reduced height */
            background: linear-gradient(90deg, #1c1917 0%, #292524 100%);
            z-index: 0;
        }

        .profile-content {
            position: relative;
            z-index: 10;
        }
    </style>
</head>

<body class="bg-stone-50 text-gray-800 antialiased">

    <nav
        class="fixed w-full top-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('customer.dashboard') }}" class="flex-shrink-0 flex items-center gap-2 group">
                    <img src="https://i.imgur.com/vPOu1H2.png" alt="NaNi Icon"
                        class="h-20 w-auto group-hover:rotate-12 transition-transform duration-300">
                </a>

                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('customer.dashboard') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-home mr-1"></i> Home
                    </a>
                    <a href="{{ route('customer.orders.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-list mr-1"></i> My Orders
                    </a>
                    <div class="ml-4 flex items-center space-x-3 border-l pl-4 border-gray-200">
                        <a href="{{ route('customer.profile.show') }}"
                            class="flex justify-center mb-0 transition-all duration-300 transform hover:-translate-y-1">
                            @if($profilePictureUrl)
                                <img src="{{ $profilePictureUrl }}" alt="Profile"
                                    class="w-12 h-12 rounded-full object-cover border-2 border-orange-600 shadow-sm">
                            @else
                                <div
                                    class="w-12 h-12 rounded-full bg-gradient-to-r from-orange-400 to-red-500 flex items-center justify-center text-white text-sm font-bold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors"
                                title="Logout">
                                <i class="fas fa-sign-out-alt text-lg"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="pt-32 pb-16 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-3xl shadow-xl border border-stone-100 overflow-hidden fade-in relative mb-8">
            <!-- Reduced height gradient background -->
            <div class="profile-gradient-bg"></div>

            <div class="profile-content px-8 pb-8 pt-10 md:pt-12">
                <div class="flex flex-col sm:flex-row items-end gap-6">
                    <div class="relative">
                        @if($user->profile_picture)
                            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile"
                                class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">
                        @else
                            <div
                                class="w-32 h-32 bg-gradient-to-br from-stone-200 to-stone-300 rounded-full flex items-center justify-center text-stone-300 text-4xl border-4 border-white shadow-lg">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                        <a href="{{ route('customer.profile.edit') }}"
                            class="absolute bottom-2 right-0 bg-white p-2 rounded-full shadow-md text-stone-600 hover:text-orange-600 transition-colors hover:scale-110">
                            <i class="fas fa-camera text-sm"></i>
                        </a>
                    </div>

                    <div class="flex-1 mb-2 text-center sm:text-left">
                        <h1 class="text-3xl font-bold text-gray-900 font-serif">{{ $user->name }}</h1>
                        <p class="text-stone-500 mt-1">{{ $user->email }}</p>
                        <div class="flex flex-wrap gap-2 mt-3">
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">
                                <i class="fas fa-check-circle mr-1"></i> Verified Customer
                            </span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full">
                                Member since {{ $user->created_at->format('M Y') }}
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('customer.profile.edit') }}"
                        class="px-6 py-2.5 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-xl font-bold hover:shadow-lg shadow-orange-500/30 hover:-translate-y-0.5 transition-all mb-4 sm:mb-0">
                        <i class="fas fa-edit mr-2"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 fade-in" style="animation-delay: 0.1s;">

            <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8">
                <h3 class="text-lg font-bold text-gray-900 font-serif mb-6 flex items-center gap-2">
                    <i class="fas fa-address-card text-orange-500"></i> Contact Information
                </h3>

                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-orange-500">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-stone-400 uppercase tracking-wide">Email Address</p>
                            <p class="text-gray-900 font-medium">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-orange-500">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-stone-400 uppercase tracking-wide">Phone Number</p>
                            <p class="text-gray-900 font-medium">{{ $user->phone ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add this section in the Contact Information card, after the phone section -->
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-orange-500">
                    <i class="fab fa-google"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-stone-400 uppercase tracking-wide">Google Account</p>
                    <p class="text-gray-900 font-medium">
                        @if($user->oauth_provider === 'google')
                            <span class="text-green-600">
                                <i class="fas fa-check-circle mr-1"></i> Connected
                            </span>
                            <span class="text-xs text-stone-500 ml-2">({{ $user->email }})</span>
                        @else
                            <span class="text-stone-500">
                                <i class="fas fa-times-circle mr-1"></i> Not Connected
                            </span>
                            <a href="{{ route('google.login') }}"
                                class="text-xs text-orange-600 hover:text-orange-500 ml-3">
                                Connect Google
                            </a>
                        @endif
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8">
                <h3 class="text-lg font-bold text-gray-900 font-serif mb-6 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-orange-500"></i> Delivery Details
                </h3>

                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-orange-500">
                        <i class="fas fa-home"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-stone-400 uppercase tracking-wide">Default Address</p>
                        <p class="text-gray-900 font-medium leading-relaxed">
                            {{ $user->delivery_address ?? 'No delivery address set' }}
                        </p>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-stone-100">
                    <a href="{{ route('customer.profile.password') }}"
                        class="flex items-center justify-between text-stone-600 hover:text-orange-600 font-medium transition-colors group p-3 hover:bg-stone-50 rounded-xl">
                        <span class="flex items-center gap-2"><i class="fas fa-lock"></i> Change Password</span>
                        <i class="fas fa-chevron-right text-xs group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize SweetAlert2 Toast
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#1f2937',
                color: '#f9fafb',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

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
                            <p class="text-gray-700">Are you sure you want to logout from your account?</p>
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

            // Fade-in animation
            const fadeElements = document.querySelectorAll('.fade-in');
            fadeElements.forEach((element, index) => {
                element.style.animationDelay = `${index * 0.1}s`;
            });

            // Add click animation to buttons
            const buttons = document.querySelectorAll('button, a[href]');
            buttons.forEach(button => {
                button.addEventListener('click', function (e) {
                    if (!this.classList.contains('transition-all')) {
                        this.classList.add('active:scale-95', 'transition-transform');
                    }
                });
            });
        });
    </script>
</body>

</html>