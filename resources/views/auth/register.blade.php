<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - NaNi</title>
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
                    <a href="/" class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <a href="/login"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-sign-in-alt mr-1"></i>Login
                    </a>
                    <a href="/register" class="text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
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
                <p class="mt-2 text-gray-600">Create your User Account and Fill your plate!</p>
            </div>

            <form class="mt-8 space-y-6" action="/register" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input id="name" name="name" type="text" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                            placeholder="Your full name">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input id="email" name="email" type="email" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                            placeholder="your@email.com">
                    </div>

                    <!-- In the register form, update the password section -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input id="password" name="password" type="password" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                            placeholder="••••••••" oninput="validatePassword(this.value)">
                        <div class="password-requirements bg-gray-50 p-3 rounded-md mt-2">
                            <p class="text-sm font-medium text-gray-700 mb-2">Password must contain:</p>
                            <div class="space-y-1">
                                <div class="requirement invalid" id="req-length">
                                    <i class="fas fa-circle"></i>
                                    <span>At least 8 characters</span>
                                </div>
                                <div class="requirement invalid" id="req-uppercase">
                                    <i class="fas fa-circle"></i>
                                    <span>One uppercase letter (A-Z)</span>
                                </div>
                                <div class="requirement invalid" id="req-lowercase">
                                    <i class="fas fa-circle"></i>
                                    <span>One lowercase letter (a-z)</span>
                                </div>
                                <div class="requirement invalid" id="req-number">
                                    <i class="fas fa-circle"></i>
                                    <span>One number (0-9)</span>
                                </div>
                                <div class="requirement invalid" id="req-special">
                                    <i class="fas fa-circle"></i>
                                    <span>One special character (@$!%*#?&)</span>
                                </div>
                            </div>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                            Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                            placeholder="••••••••">
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Create Account
                    </button>
                </div>

                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account?
                        <a href="/login" class="font-medium text-orange-600 hover:text-orange-500">
                            Sign in here
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
<script>
    function validatePassword(password) {
        const requirements = {
            length: document.getElementById('req-length'),
            uppercase: document.getElementById('req-uppercase'),
            lowercase: document.getElementById('req-lowercase'),
            number: document.getElementById('req-number'),
            special: document.getElementById('req-special')
        };

        // Update requirements
        if (password.length >= 8) {
            requirements.length.classList.remove('invalid');
            requirements.length.classList.add('valid');
            requirements.length.querySelector('i').className = 'fas fa-check-circle';
        } else {
            requirements.length.classList.remove('valid');
            requirements.length.classList.add('invalid');
            requirements.length.querySelector('i').className = 'fas fa-circle';
        }

        if (/[A-Z]/.test(password)) {
            requirements.uppercase.classList.remove('invalid');
            requirements.uppercase.classList.add('valid');
            requirements.uppercase.querySelector('i').className = 'fas fa-check-circle';
        } else {
            requirements.uppercase.classList.remove('valid');
            requirements.uppercase.classList.add('invalid');
            requirements.uppercase.querySelector('i').className = 'fas fa-circle';
        }

        if (/[a-z]/.test(password)) {
            requirements.lowercase.classList.remove('invalid');
            requirements.lowercase.classList.add('valid');
            requirements.lowercase.querySelector('i').className = 'fas fa-check-circle';
        } else {
            requirements.lowercase.classList.remove('valid');
            requirements.lowercase.classList.add('invalid');
            requirements.lowercase.querySelector('i').className = 'fas fa-circle';
        }

        if (/[0-9]/.test(password)) {
            requirements.number.classList.remove('invalid');
            requirements.number.classList.add('valid');
            requirements.number.querySelector('i').className = 'fas fa-check-circle';
        } else {
            requirements.number.classList.remove('valid');
            requirements.number.classList.add('invalid');
            requirements.number.querySelector('i').className = 'fas fa-circle';
        }

        if (/[@$!%*#?&]/.test(password)) {
            requirements.special.classList.remove('invalid');
            requirements.special.classList.add('valid');
            requirements.special.querySelector('i').className = 'fas fa-check-circle';
        } else {
            requirements.special.classList.remove('valid');
            requirements.special.classList.add('invalid');
            requirements.special.querySelector('i').className = 'fas fa-circle';
        }
    }
</script>

</html>