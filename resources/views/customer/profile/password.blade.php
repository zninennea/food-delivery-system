<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - NaNi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .password-requirements {
            margin-top: 0.25rem;
            font-size: 0.75rem;
            line-height: 1rem;
        }
        .requirement {
            display: flex;
            align-items: center;
            margin-bottom: 2px;
        }
        .requirement.valid {
            color: #10B981;
        }
        .requirement.invalid {
            color: #6B7280;
        }
        .requirement i {
            margin-right: 4px;
            font-size: 0.875rem;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <img src="{{ asset('images/nani-logo.png') }}" alt="NaNi Logo" class="h-10 w-10 mr-3">
                    <div>
                        <a href="/" class="text-xl font-bold text-gray-800">NaNi</a>
                        <p class="text-xs text-gray-500 -mt-1">Change Password</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('customer.dashboard') }}" class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <a href="{{ route('customer.profile.show') }}" class="text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-user mr-1"></i>Profile
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-sign-out-alt mr-1"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-2xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Change Password</h2>
            </div>

            <form action="{{ route('customer.profile.update-password') }}" method="POST" class="p-6" id="password-form">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                        <input type="password" name="current_password" id="current_password" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('current_password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" name="password" id="password" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        
                        <!-- Password Requirements -->
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
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <div id="password-match" class="text-xs mt-1 hidden"></div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('customer.profile.show') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700" id="submit-btn" disabled>
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');
            const submitBtn = document.getElementById('submit-btn');
            const passwordMatch = document.getElementById('password-match');
            
            const requirements = {
                length: document.getElementById('req-length'),
                uppercase: document.getElementById('req-uppercase'),
                lowercase: document.getElementById('req-lowercase'),
                number: document.getElementById('req-number'),
                special: document.getElementById('req-special')
            };

            function validatePassword() {
                const password = passwordInput.value;
                let isValid = true;

                // Check each requirement
                if (password.length >= 8) {
                    requirements.length.classList.remove('invalid');
                    requirements.length.classList.add('valid');
                    requirements.length.querySelector('i').className = 'fas fa-check-circle';
                } else {
                    requirements.length.classList.remove('valid');
                    requirements.length.classList.add('invalid');
                    requirements.length.querySelector('i').className = 'fas fa-circle';
                    isValid = false;
                }

                if (/[A-Z]/.test(password)) {
                    requirements.uppercase.classList.remove('invalid');
                    requirements.uppercase.classList.add('valid');
                    requirements.uppercase.querySelector('i').className = 'fas fa-check-circle';
                } else {
                    requirements.uppercase.classList.remove('valid');
                    requirements.uppercase.classList.add('invalid');
                    requirements.uppercase.querySelector('i').className = 'fas fa-circle';
                    isValid = false;
                }

                if (/[a-z]/.test(password)) {
                    requirements.lowercase.classList.remove('invalid');
                    requirements.lowercase.classList.add('valid');
                    requirements.lowercase.querySelector('i').className = 'fas fa-check-circle';
                } else {
                    requirements.lowercase.classList.remove('valid');
                    requirements.lowercase.classList.add('invalid');
                    requirements.lowercase.querySelector('i').className = 'fas fa-circle';
                    isValid = false;
                }

                if (/[0-9]/.test(password)) {
                    requirements.number.classList.remove('invalid');
                    requirements.number.classList.add('valid');
                    requirements.number.querySelector('i').className = 'fas fa-check-circle';
                } else {
                    requirements.number.classList.remove('valid');
                    requirements.number.classList.add('invalid');
                    requirements.number.querySelector('i').className = 'fas fa-circle';
                    isValid = false;
                }

                if (/[@$!%*#?&]/.test(password)) {
                    requirements.special.classList.remove('invalid');
                    requirements.special.classList.add('valid');
                    requirements.special.querySelector('i').className = 'fas fa-check-circle';
                } else {
                    requirements.special.classList.remove('valid');
                    requirements.special.classList.add('invalid');
                    requirements.special.querySelector('i').className = 'fas fa-circle';
                    isValid = false;
                }

                return isValid;
            }

            function checkPasswordMatch() {
                const password = passwordInput.value;
                const confirm = confirmInput.value;
                
                if (confirm === '') {
                    passwordMatch.classList.add('hidden');
                    return false;
                }
                
                if (password === confirm) {
                    passwordMatch.textContent = '✓ Passwords match';
                    passwordMatch.className = 'text-green-600 text-xs mt-1';
                    passwordMatch.classList.remove('hidden');
                    return true;
                } else {
                    passwordMatch.textContent = '✗ Passwords do not match';
                    passwordMatch.className = 'text-red-600 text-xs mt-1';
                    passwordMatch.classList.remove('hidden');
                    return false;
                }
            }

            function updateSubmitButton() {
                const isPasswordValid = validatePassword();
                const isMatch = checkPasswordMatch();
                const hasCurrentPassword = document.getElementById('current_password').value.trim() !== '';
                
                submitBtn.disabled = !(isPasswordValid && isMatch && hasCurrentPassword);
            }

            // Add event listeners
            passwordInput.addEventListener('input', updateSubmitButton);
            confirmInput.addEventListener('input', updateSubmitButton);
            document.getElementById('current_password').addEventListener('input', updateSubmitButton);

            // Initial validation
            updateSubmitButton();
        });
    </script>
</body>
</html>