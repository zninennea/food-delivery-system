<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Profile - NaNi Rider</title>
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

        .profile-card {
            background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%);
            border: 1px solid rgba(229, 231, 235, 0.5);
            transition: all 0.3s ease;
        }

        .profile-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
        }

        .info-item {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid rgba(226, 232, 240, 0.5);
        }
    </style>
</head>

<body class="bg-stone-50 text-gray-800 antialiased">
    <!-- Navigation -->
    <nav
        class="fixed w-full top-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-2 group">
                    <img src="https://i.imgur.com/vPOu1H2.png" alt="NaNi Icon"
                        class="h-20 w-auto group-hover:rotate-12 transition-transform duration-300">
                    <div class="ml-2">
                        <a href="/" class="text-xl font-bold text-gray-800 font-serif">NaNi</a>
                        <p class="text-xs text-gray-500 -mt-1">My Profile</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('rider.dashboard') }}"
                        class="text-gray-600 hover:text-orange-600 hover:bg-gray-50 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="text-gray-400 hover:text-red-600 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors"
                            title="Logout">
                            <i class="fas fa-sign-out-alt text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="pt-32 pb-16 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 fade-in">
        <!-- Profile Header -->
        <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 mb-8">
            <div class="flex flex-col md:flex-row md:items-start gap-8">
                <!-- Profile Picture -->
                <div class="flex flex-col items-center">
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture"
                            class="h-36 w-36 rounded-2xl object-cover border-4 border-white shadow-lg">
                    @else
                        <div
                            class="h-36 w-36 bg-gradient-to-br from-stone-200 to-stone-300 rounded-2xl flex items-center justify-center border-4 border-white shadow-lg">
                            <i class="fas fa-user text-stone-500 text-5xl"></i>
                        </div>
                    @endif
                    <span class="mt-4 px-4 py-1.5 text-xs font-bold rounded-full uppercase tracking-wider
                        @if($user->status == 'active') bg-green-100 text-green-800
                        @elseif($user->status == 'inactive') bg-gray-100 text-gray-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ ucfirst($user->status) }} Rider
                    </span>
                </div>

                <!-- Profile Info -->
                <div class="flex-1">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 font-serif">{{ $user->name }}</h1>
                            <p class="text-stone-500 mt-2 flex items-center gap-2">
                                <i class="fas fa-envelope"></i> {{ $user->email }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="text-right">
                                <p class="text-xs text-gray-500 uppercase tracking-wider">Member Since</p>
                                <p class="text-sm font-medium text-gray-900">{{ $user->created_at->format('M j, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Badges -->
                    <div class="flex flex-wrap gap-3">
                        @if($user->phone)
                            <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 rounded-xl">
                                <i class="fas fa-phone text-blue-500"></i>
                                <span class="text-sm font-medium text-gray-900">{{ $user->phone }}</span>
                            </div>
                        @endif

                        @if($user->vehicle_type)
                            <div class="flex items-center gap-2 px-4 py-2 bg-green-50 rounded-xl">
                                <i class="fas fa-motorcycle text-green-500"></i>
                                <span class="text-sm font-medium text-gray-900">{{ $user->vehicle_type }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Personal Information -->
            <div class="space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden">
                    <div class="p-6 border-b border-stone-100 bg-stone-50/50">
                        <h3 class="text-xl font-bold text-gray-900 font-serif">Personal Information</h3>
                        <p class="text-gray-600 text-sm mt-1">Your account details and contact information</p>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="info-item rounded-2xl p-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-id-card text-blue-500 text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 uppercase tracking-wider mb-1">Full Name</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $user->name }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="info-item rounded-2xl p-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-envelope text-purple-500 text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 uppercase tracking-wider mb-1">Email Address</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $user->email }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="info-item rounded-2xl p-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-phone text-green-500 text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 uppercase tracking-wider mb-1">Phone Number</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $user->phone ?? 'Not provided' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="info-item rounded-2xl p-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-user-check text-orange-500 text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 uppercase tracking-wider mb-1">Account Status</p>
                                    <p class="text-lg font-bold text-gray-900 capitalize">{{ $user->status }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vehicle Information -->
            <div class="space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden">
                    <div class="p-6 border-b border-stone-100 bg-stone-50/50">
                        <h3 class="text-xl font-bold text-gray-900 font-serif">Vehicle Information</h3>
                        <p class="text-gray-600 text-sm mt-1">Your delivery vehicle details</p>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="info-item rounded-2xl p-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-motorcycle text-red-500 text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 uppercase tracking-wider mb-1">Vehicle Type</p>
                                    <p class="text-lg font-bold text-gray-900">
                                        {{ $user->vehicle_type ?? 'Not specified' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="info-item rounded-2xl p-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-tag text-indigo-500 text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 uppercase tracking-wider mb-1">Vehicle Plate Number
                                    </p>
                                    <p class="text-lg font-bold text-gray-900">
                                        {{ $user->vehicle_plate ?? 'Not specified' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="info-item rounded-2xl p-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-id-badge text-yellow-500 text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 uppercase tracking-wider mb-1">Driver's License</p>
                                    <div class="flex items-center justify-between">
                                        @if($user->drivers_license)
                                            <div>
                                                <p class="text-lg font-bold text-gray-900">Verified</p>
                                                <p class="text-xs text-gray-500 mt-1">License on file</p>
                                            </div>
                                            <button type="button"
                                                onclick="viewLicense('{{ asset('storage/' . $user->drivers_license) }}')"
                                                class="license-view-btn px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors text-sm font-medium flex items-center gap-2">
                                                <i class="fas fa-external-link-alt"></i> View
                                            </button>
                                        @else
                                            <div>
                                                <p class="text-lg font-bold text-gray-900">Not Uploaded</p>
                                                <p class="text-xs text-gray-500 mt-1">License required</p>
                                            </div>
                                            <span
                                                class="px-4 py-2 bg-gray-100 text-gray-600 rounded-xl text-sm font-medium">
                                                <i class="fas fa-times mr-2"></i> Missing
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Information Notice -->
        <div class="mt-8 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-3xl border border-yellow-200 p-7">
            <div class="flex flex-col md:flex-row md:items-center gap-5">
                <div class="flex-shrink-0">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-info-circle text-white text-2xl"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-gray-900 font-serif mb-2">Need to Update Your Information?</h3>
                    <p class="text-gray-700">
                        To update your profile information, vehicle details, or upload new documents,
                        please contact the restaurant owner directly. They will assist you with all
                        profile-related updates and document verification.
                    </p>
                    <div class="mt-4 flex flex-wrap gap-3">
                        <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-xl border border-yellow-200">
                            <i class="fas fa-user-tie text-yellow-500"></i>
                            <span class="text-sm font-medium text-gray-900">Contact Restaurant Owner</span>
                        </div>
                        <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-xl border border-yellow-200">
                            <i class="fas fa-shield-alt text-yellow-500"></i>
                            <span class="text-sm font-medium text-gray-900">Documents Verified by Admin</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // License viewing function
        function viewLicense(licenseUrl) {
            Swal.fire({
                title: 'View Driver\'s License',
                html: `<div class="text-center">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-id-badge text-blue-600 text-2xl"></i>
                    </div>
                    <p class="text-lg font-medium text-gray-900">Driver's License Document</p>
                    <p class="text-gray-600 mt-2">You are about to view your driver's license document.</p>
                    <div class="mt-4 p-4 bg-yellow-50 rounded-xl border border-yellow-100">
                        <p class="text-sm text-yellow-800">
                            <i class="fas fa-shield-alt mr-2"></i>
                            This document contains sensitive personal information.
                        </p>
                    </div>
                </div>`,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-external-link-alt mr-2"></i>Open Document',
                cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-3 font-medium',
                    cancelButton: 'rounded-xl px-6 py-3 font-medium'
                },
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise((resolve) => {
                        setTimeout(() => {
                            // Open the license in a new tab after confirmation
                            window.open(licenseUrl, '_blank');
                            resolve();
                        }, 500);
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show success toast
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        background: '#10b981',
                        color: '#ffffff',
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer);
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                        }
                    });

                    Toast.fire({
                        icon: 'success',
                        title: 'License document opened in new tab'
                    });
                }
            });
        }

        // Fade-in animation for page load
        document.addEventListener('DOMContentLoaded', function () {
            const fadeElements = document.querySelectorAll('.fade-in');
            fadeElements.forEach((element, index) => {
                element.style.animationDelay = `${index * 0.1}s`;
            });

            // Initialize SweetAlert2 toast theme
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

            // Add click animation to buttons
            const buttons = document.querySelectorAll('button, a[href]');
            buttons.forEach(button => {
                button.addEventListener('click', function (e) {
                    if (!this.classList.contains('transition-all')) {
                        this.classList.add('active:scale-95', 'transition-transform');
                    }
                });
            });

            // Profile card hover effect
            const profileCards = document.querySelectorAll('.profile-card');
            profileCards.forEach(card => {
                card.addEventListener('mouseenter', function () {
                    this.style.transform = 'translateY(-4px)';
                });
                card.addEventListener('mouseleave', function () {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Simple animation for info items
            const infoItems = document.querySelectorAll('.info-item');
            infoItems.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
                item.classList.add('fade-in');
            });
        });
    </script>
</body>

</html>