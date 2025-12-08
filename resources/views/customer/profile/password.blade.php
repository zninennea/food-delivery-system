<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - NaNi</title>
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

        .requirement.valid {
            color: #16a34a;
        }

        .requirement.invalid {
            color: #9ca3af;
        }

        .password-strength-bar {
            height: 4px;
            transition: all 0.3s ease;
            border-radius: 2px;
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

    <div class="pt-32 pb-16 max-w-xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-3xl shadow-xl border border-stone-100 overflow-hidden fade-in">
            <div class="p-8 border-b border-stone-100">
                <h2 class="text-2xl font-bold text-gray-900 font-serif">Change Password</h2>
                <p class="text-stone-500 text-sm mt-1">Ensure your account is using a long, random password to stay
                    secure.</p>
            </div>

            <form id="password-form" action="{{ route('customer.profile.update-password') }}" method="POST"
                class="p-8 space-y-6">
                @csrf

                <div>
                    <label for="current_password" class="block text-sm font-bold text-gray-700 mb-2">Current
                        Password</label>
                    <div class="relative">
                        <input type="password" name="current_password" id="current_password" required
                            class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm transition-all pr-10"
                            placeholder="Enter your current password">
                        <button type="button" class="absolute right-3 top-3 text-stone-400 hover:text-gray-600"
                            id="toggle-current">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-gray-700 mb-2">New Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm transition-all pr-10"
                            placeholder="Create a strong password">
                        <button type="button" class="absolute right-3 top-3 text-stone-400 hover:text-gray-600"
                            id="toggle-password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <!-- Password Strength Meter -->
                    <div class="mt-3">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-medium text-stone-500">Password Strength</span>
                            <span class="text-xs font-bold" id="strength-text">Weak</span>
                        </div>
                        <div class="w-full bg-stone-200 rounded-full overflow-hidden">
                            <div id="strength-bar" class="password-strength-bar w-0 bg-red-500"></div>
                        </div>
                    </div>

                    <div class="bg-stone-50 rounded-xl p-4 mt-4 border border-stone-100">
                        <p class="text-xs font-bold text-stone-400 uppercase tracking-wide mb-2">Security Requirements
                        </p>
                        <div class="space-y-2 text-xs">
                            <div class="requirement invalid flex items-center gap-2" id="req-length">
                                <i class="fas fa-circle text-[6px]"></i>
                                <span>At least 8 characters</span>
                            </div>
                            <div class="requirement invalid flex items-center gap-2" id="req-uppercase">
                                <i class="fas fa-circle text-[6px]"></i>
                                <span>At least one uppercase letter (A-Z)</span>
                            </div>
                            <div class="requirement invalid flex items-center gap-2" id="req-lowercase">
                                <i class="fas fa-circle text-[6px]"></i>
                                <span>At least one lowercase letter (a-z)</span>
                            </div>
                            <div class="requirement invalid flex items-center gap-2" id="req-number">
                                <i class="fas fa-circle text-[6px]"></i>
                                <span>At least one number (0-9)</span>
                            </div>
                            <div class="requirement invalid flex items-center gap-2" id="req-special">
                                <i class="fas fa-circle text-[6px]"></i>
                                <span>At least one special character (!@#$%^&*)</span>
                            </div>
                        </div>
                    </div>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-2">Confirm New
                        Password</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm transition-all pr-10"
                            placeholder="Confirm your new password">
                        <button type="button" class="absolute right-3 top-3 text-stone-400 hover:text-gray-600"
                            id="toggle-confirm">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div id="password-match" class="text-xs mt-2 font-medium hidden">
                        <i class="fas fa-check-circle mr-1"></i> Passwords match
                    </div>
                    <div id="password-mismatch" class="text-xs mt-2 font-medium hidden">
                        <i class="fas fa-times-circle mr-1"></i> Passwords do not match
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3">
                    <a href="{{ route('customer.profile.show') }}"
                        class="px-6 py-3 text-stone-500 font-bold hover:bg-stone-50 rounded-xl transition-colors text-sm">Cancel</a>
                    <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-stone-900 to-stone-800 text-white font-bold rounded-xl shadow-lg hover:bg-orange-600 hover:from-orange-600 hover:to-red-600 transition-all text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                        id="submit-btn" disabled>
                        <i class="fas fa-lock mr-2"></i> Update Password
                    </button>
                </div>
            </form>
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

            // Toggle password visibility
            const togglePasswordVisibility = (inputId, buttonId) => {
                const input = document.getElementById(inputId);
                const button = document.getElementById(buttonId);

                button.addEventListener('click', () => {
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    button.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
                });
            };

            // Initialize toggle buttons
            togglePasswordVisibility('current_password', 'toggle-current');
            togglePasswordVisibility('password', 'toggle-password');
            togglePasswordVisibility('password_confirmation', 'toggle-confirm');

            // Form elements
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');
            const currentInput = document.getElementById('current_password');
            const submitBtn = document.getElementById('submit-btn');
            const matchMsg = document.getElementById('password-match');
            const mismatchMsg = document.getElementById('password-mismatch');
            const strengthBar = document.getElementById('strength-bar');
            const strengthText = document.getElementById('strength-text');

            // Requirements elements
            const reqs = {
                length: document.getElementById('req-length'),
                uppercase: document.getElementById('req-uppercase'),
                lowercase: document.getElementById('req-lowercase'),
                number: document.getElementById('req-number'),
                special: document.getElementById('req-special')
            };

            function calculatePasswordStrength(password) {
                let score = 0;

                // Length score
                if (password.length >= 8) score += 20;
                if (password.length >= 12) score += 10;
                if (password.length >= 16) score += 10;

                // Character type scores
                if (/[A-Z]/.test(password)) score += 20;
                if (/[a-z]/.test(password)) score += 20;
                if (/[0-9]/.test(password)) score += 20;
                if (/[@$!%*#?&]/.test(password)) score += 10;

                return Math.min(score, 100);
            }

            function updatePasswordStrength(score) {
                let color, text, width;

                if (score < 40) {
                    color = '#ef4444'; // red-500
                    text = 'Weak';
                    width = '25%';
                } else if (score < 70) {
                    color = '#f59e0b'; // yellow-500
                    text = 'Fair';
                    width = '50%';
                } else if (score < 90) {
                    color = '#3b82f6'; // blue-500
                    text = 'Good';
                    width = '75%';
                } else {
                    color = '#10b981'; // green-500
                    text = 'Strong';
                    width = '100%';
                }

                strengthBar.style.width = width;
                strengthBar.style.backgroundColor = color;
                strengthText.textContent = text;
                strengthText.className = 'text-xs font-bold';
                strengthText.style.color = color;
            }

            function validate() {
                const password = passwordInput.value;
                const confirm = confirmInput.value;
                const current = currentInput.value;

                let allValid = true;

                // Check requirements
                const checks = [
                    { test: password.length >= 8, element: reqs.length },
                    { test: /[A-Z]/.test(password), element: reqs.uppercase },
                    { test: /[a-z]/.test(password), element: reqs.lowercase },
                    { test: /[0-9]/.test(password), element: reqs.number },
                    { test: /[@$!%*#?&]/.test(password), element: reqs.special }
                ];

                checks.forEach(check => {
                    if (check.test) {
                        check.element.classList.add('valid');
                        check.element.classList.remove('invalid');
                        check.element.querySelector('i').className = 'fas fa-check text-green-500 mr-1';
                    } else {
                        check.element.classList.remove('valid');
                        check.element.classList.add('invalid');
                        check.element.querySelector('i').className = 'fas fa-circle text-[6px] mr-1';
                        if (password.length > 0) allValid = false;
                    }
                });

                // Calculate and update password strength
                if (password.length > 0) {
                    const strength = calculatePasswordStrength(password);
                    updatePasswordStrength(strength);
                } else {
                    strengthBar.style.width = '0%';
                    strengthText.textContent = 'Weak';
                }

                // Check password match
                if (password.length > 0 && confirm.length > 0) {
                    if (password === confirm) {
                        matchMsg.classList.remove('hidden');
                        mismatchMsg.classList.add('hidden');
                    } else {
                        matchMsg.classList.add('hidden');
                        mismatchMsg.classList.remove('hidden');
                    }
                } else {
                    matchMsg.classList.add('hidden');
                    mismatchMsg.classList.add('hidden');
                }

                // Enable/disable submit button
                const passwordsMatch = password === confirm && password.length > 0 && confirm.length > 0;
                const currentFilled = current.trim() !== '';

                submitBtn.disabled = !(allValid && passwordsMatch && currentFilled);
            }

            // Event listeners for real-time validation
            passwordInput.addEventListener('input', validate);
            confirmInput.addEventListener('input', validate);
            currentInput.addEventListener('input', validate);

            // Initial validation
            validate();

            // Form submission with SweetAlert2 confirmation
            const form = document.getElementById('password-form');
            if (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    // Check if all requirements are met
                    const password = passwordInput.value;
                    const confirm = confirmInput.value;
                    const current = currentInput.value;

                    if (password !== confirm) {
                        Toast.fire({
                            icon: 'error',
                            title: 'Passwords do not match'
                        });
                        return;
                    }

                    // Show confirmation modal
                    Swal.fire({
                        title: 'Update Password?',
                        html: `<div class="text-center">
                            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-lock text-blue-600 text-2xl"></i>
                            </div>
                            <p class="text-gray-700">Are you sure you want to change your password?</p>
                            <div class="mt-4 p-3 bg-yellow-50 rounded-xl border border-yellow-100">
                                <p class="text-sm text-yellow-800">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    You will need to use your new password for your next login.
                                </p>
                            </div>
                        </div>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3b82f6',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: '<i class="fas fa-lock mr-2"></i>Change Password',
                        cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-2xl',
                            confirmButton: 'rounded-xl px-6 py-3 font-medium',
                            cancelButton: 'rounded-xl px-6 py-3 font-medium'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading state
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';

                            // Submit the form
                            form.submit();
                        }
                    });
                });
            }

            // Cancel button confirmation (if there are changes)
            const cancelLink = document.querySelector('a[href="{{ route('customer.profile.show') }}"]');
            if (cancelLink) {
                cancelLink.addEventListener('click', function (e) {
                    const current = currentInput.value;
                    const password = passwordInput.value;
                    const confirm = confirmInput.value;

                    if (current.trim() || password.trim() || confirm.trim()) {
                        e.preventDefault();

                        Swal.fire({
                            title: 'Discard Changes?',
                            html: `<div class="text-center">
                                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                                </div>
                                <p class="text-gray-700">You have unsaved changes. Are you sure you want to leave?</p>
                            </div>`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#ef4444',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: '<i class="fas fa-times mr-2"></i>Discard Changes',
                            cancelButtonText: '<i class="fas fa-arrow-left mr-2"></i>Continue Editing',
                            reverseButtons: true,
                            customClass: {
                                popup: 'rounded-2xl',
                                confirmButton: 'rounded-xl px-6 py-3 font-medium',
                                cancelButton: 'rounded-xl px-6 py-3 font-medium'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = this.href;
                            }
                        });
                    }
                });
            }

            // Fade-in animation
            const fadeElements = document.querySelectorAll('.fade-in');
            fadeElements.forEach((element, index) => {
                element.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>

</html>