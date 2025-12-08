<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - NaNi</title>
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

        /* Profile picture preview */
        #profile-preview {
            transition: all 0.3s ease;
        }

        /* File input styling */
        .file-input-label:hover .camera-icon {
            transform: scale(1.1);
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

    <div class="pt-32 pb-16 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-3xl shadow-xl border border-stone-100 overflow-hidden fade-in">
            <div class="p-8 sm:p-10 border-b border-stone-100">
                <h2 class="text-3xl font-bold text-gray-900 font-serif">Edit Profile</h2>
                <p class="text-stone-500 mt-2">Update your personal information and delivery details.</p>
            </div>

            <form id="profile-form" action="{{ route('customer.profile.update') }}" method="POST"
                enctype="multipart/form-data" class="p-8 sm:p-10 space-y-8">
                @csrf
                <!-- REMOVED: @method('PUT') - Using POST as defined in routes -->

                <div class="flex flex-col items-center sm:flex-row gap-8">
                    <div class="relative group">
                        <div id="profile-preview"
                            class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-lg">
                            @if($user->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile"
                                    class="w-full h-full object-cover" id="profile-image">
                            @else
                                <div
                                    class="w-full h-full bg-stone-100 flex items-center justify-center text-stone-300 text-4xl">
                                    <i class="fas fa-user" id="profile-icon"></i>
                                </div>
                            @endif
                        </div>
                        <label for="profile_picture"
                            class="file-input-label absolute bottom-0 right-0 bg-orange-600 text-white p-2 rounded-full shadow-md cursor-pointer hover:bg-orange-700 transition-colors">
                            <i class="fas fa-camera text-sm camera-icon transition-transform"></i>
                        </label>
                        <input type="file" name="profile_picture" id="profile_picture" accept="image/*" class="hidden">
                        @error('profile_picture')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <h3 class="text-lg font-bold text-gray-900">Profile Photo</h3>
                        <p class="text-sm text-stone-500 mt-1">Upload a new photo to personalize your account. Max 2MB.
                        </p>
                    </div>
                </div>

                <hr class="border-stone-100">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <h3 class="text-lg font-bold text-gray-900 font-serif mb-4">Personal Details</h3>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm transition-all">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" required
                            class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm transition-all">
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <hr class="border-stone-100">

                <div>
                    <h3 class="text-lg font-bold text-gray-900 font-serif mb-4">Delivery Information</h3>

                    <div>
                        <label for="delivery_address" class="block text-sm font-bold text-gray-700 mb-2">Default
                            Delivery Address</label>
                        <textarea name="delivery_address" id="delivery_address" rows="3" required
                            class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm transition-all resize-none">{{ old('delivery_address', $user->delivery_address) }}</textarea>
                        @error('delivery_address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Add this section after the Delivery Information section, before the buttons -->
                <hr class="border-stone-100">

                <div>
                    <h3 class="text-lg font-bold text-gray-900 font-serif mb-4">Social Accounts</h3>

                    <div class="flex items-center justify-between p-4 bg-stone-50 rounded-xl border border-stone-200">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 rounded-full bg-white border border-stone-300 flex items-center justify-center">
                                <img src="https://www.google.com/favicon.ico" alt="Google" class="w-6 h-6">
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Google</p>
                                <p class="text-sm text-stone-500">
                                    @if($user->oauth_provider === 'google')
                                        <span class="text-green-600">
                                            <i class="fas fa-check-circle mr-1"></i> Connected
                                        </span>
                                    @else
                                        <span class="text-stone-500">Not connected</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if(!$user->hasGoogleAccount())
                            <a href="{{ route('google.login') }}" onclick="setConnectionIntent()"
                                class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-lg transition-all shadow-sm">
                                <i class="fab fa-google mr-1"></i> Connect
                            </a>
                        @endif
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4">
                    <a href="{{ route('customer.profile.password') }}"
                        class="text-stone-500 hover:text-orange-600 text-sm font-medium transition-colors">
                        <i class="fas fa-lock mr-1"></i> Change Password
                    </a>

                    <div class="flex gap-3">
                        <a href="{{ route('customer.profile.show') }}"
                            class="px-6 py-3 border border-stone-200 text-stone-600 font-bold rounded-xl hover:bg-stone-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" id="save-btn"
                            class="px-8 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white font-bold rounded-xl shadow-lg hover:shadow-orange-500/30 transform hover:-translate-y-0.5 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                            Save Changes
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script>
        function setConnectionIntent() {
            // Store that user is connecting from profile page
            localStorage.setItem('google_connect_intent', 'profile');
        }

        // Check on page load if we need to set session
        document.addEventListener('DOMContentLoaded', function () {
            const intent = localStorage.getItem('google_connect_intent');
            if (intent === 'profile') {
                // Send to server to set session
                fetch('{{ route("set.google.connect.intent") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ intent: 'profile' })
                });
                localStorage.removeItem('google_connect_intent');
            }
        });
    </script>

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

            // Profile picture preview
            const profileInput = document.getElementById('profile_picture');
            const profilePreview = document.getElementById('profile-preview');

            if (profileInput) {
                profileInput.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (file) {
                        // Check file size (max 2MB)
                        if (file.size > 2 * 1024 * 1024) {
                            Toast.fire({
                                icon: 'error',
                                title: 'File too large. Maximum size is 2MB.'
                            });
                            profileInput.value = '';
                            return;
                        }

                        // Check file type
                        if (!file.type.match('image.*')) {
                            Toast.fire({
                                icon: 'error',
                                title: 'Please select an image file.'
                            });
                            profileInput.value = '';
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function (e) {
                            profilePreview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover" alt="Profile Preview">`;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Form submission handler
            const form = document.getElementById('profile-form');
            const saveBtn = document.getElementById('save-btn');

            if (form) {
                form.addEventListener('submit', async function (e) {
                    e.preventDefault();

                    // Disable save button
                    saveBtn.disabled = true;
                    const originalBtnText = saveBtn.innerHTML;
                    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';

                    try {
                        const formData = new FormData(this);

                        // Check for changes
                        const nameChanged = formData.get('name') !== '{{ $user->name }}';
                        const phoneChanged = formData.get('phone') !== '{{ $user->phone }}';
                        const addressChanged = formData.get('delivery_address') !== '{{ $user->delivery_address }}';
                        const fileChanged = formData.get('profile_picture').size > 0;

                        if (!nameChanged && !phoneChanged && !addressChanged && !fileChanged) {
                            Toast.fire({
                                icon: 'info',
                                title: 'No changes detected'
                            });
                            saveBtn.disabled = false;
                            saveBtn.innerHTML = originalBtnText;
                            return;
                        }

                        // Show saving confirmation
                        const saveConfirm = await Swal.fire({
                            title: 'Save Changes?',
                            html: `<div class="text-center">
                                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-save text-blue-600 text-xl"></i>
                                </div>
                                <p class="text-gray-700">Are you sure you want to save these changes to your profile?</p>
                            </div>`,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#10b981',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: '<i class="fas fa-save mr-2"></i>Save Changes',
                            cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
                            reverseButtons: true,
                            customClass: {
                                popup: 'rounded-2xl',
                                confirmButton: 'rounded-xl px-6 py-3 font-medium',
                                cancelButton: 'rounded-xl px-6 py-3 font-medium'
                            }
                        });

                        if (!saveConfirm.isConfirmed) {
                            saveBtn.disabled = false;
                            saveBtn.innerHTML = originalBtnText;
                            return;
                        }

                        const response = await fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });

                        // Check if response is JSON
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            const data = await response.json();

                            if (response.ok && data.success) {
                                // Show success message
                                await Swal.fire({
                                    title: 'Profile Updated!',
                                    html: `<div class="text-center">
                                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                                        </div>
                                        <p class="text-gray-700">Your profile has been updated successfully.</p>
                                    </div>`,
                                    icon: 'success',
                                    confirmButtonColor: '#10b981',
                                    confirmButtonText: '<i class="fas fa-check mr-2"></i>Continue',
                                    customClass: {
                                        popup: 'rounded-2xl',
                                        confirmButton: 'rounded-xl px-6 py-3 font-medium'
                                    }
                                });

                                // Redirect to profile page
                                window.location.href = '{{ route('customer.profile.show') }}';
                            } else {
                                throw new Error(data.message || 'Failed to update profile');
                            }
                        } else {
                            // Handle HTML response (likely an error page)
                            const text = await response.text();
                            console.error('Non-JSON response:', text.substring(0, 500));
                            throw new Error('Server returned an error. Please try again.');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        Toast.fire({
                            icon: 'error',
                            title: error.message || 'Failed to update profile. Please try again.'
                        });

                        // Re-enable save button
                        saveBtn.disabled = false;
                        saveBtn.innerHTML = originalBtnText;
                    }
                });
            }

            // Cancel confirmation
            const cancelLink = document.querySelector('a[href="{{ route('customer.profile.show') }}"]');
            if (cancelLink && form) {
                cancelLink.addEventListener('click', function (e) {
                    // Check if form has changes
                    const formData = new FormData(form);
                    const nameChanged = formData.get('name') !== '{{ $user->name }}';
                    const phoneChanged = formData.get('phone') !== '{{ $user->phone }}';
                    const addressChanged = formData.get('delivery_address') !== '{{ $user->delivery_address }}';
                    const fileChanged = formData.get('profile_picture').size > 0;

                    if (nameChanged || phoneChanged || addressChanged || fileChanged) {
                        e.preventDefault();

                        Swal.fire({
                            title: 'Unsaved Changes',
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

            // Form validation
            const validateForm = () => {
                const name = document.getElementById('name').value.trim();
                const phone = document.getElementById('phone').value.trim();
                const address = document.getElementById('delivery_address').value.trim();

                const isValid = name && phone && address;
                saveBtn.disabled = !isValid;

                return isValid;
            };

            // Live validation
            ['name', 'phone', 'delivery_address'].forEach(field => {
                document.getElementById(field).addEventListener('input', validateForm);
            });

            // Initial validation
            validateForm();
        });
    </script>

    <script>
        function disconnectGoogle() {
            Swal.fire({
                title: 'Disconnect Google Account?',
                html: `<div class="text-center">
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
            </div>
            <p class="text-gray-700">Are you sure you want to disconnect your Google account?</p>
            <div class="mt-3 p-3 bg-red-50 rounded-xl border border-red-100">
                <p class="text-sm text-red-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    You'll need to set a password if you want to log in with email.
                </p>
            </div>
        </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-unlink mr-2"></i>Disconnect',
                cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-3 font-medium',
                    cancelButton: 'rounded-xl px-6 py-3 font-medium'
                }
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch('{{ route('google.disconnect') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire({
                                title: 'Disconnected!',
                                html: `<div class="text-center">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                            <p class="text-gray-700">Google account has been disconnected.</p>
                        </div>`,
                                icon: 'success',
                                confirmButtonColor: '#10b981',
                                confirmButtonText: '<i class="fas fa-check mr-2"></i>OK',
                                customClass: {
                                    popup: 'rounded-2xl',
                                    confirmButton: 'rounded-xl px-6 py-3 font-medium'
                                }
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            throw new Error(data.message || 'Failed to disconnect');
                        }
                    } catch (error) {
                        Swal.fire({
                            title: 'Error!',
                            text: error.message || 'Failed to disconnect Google account.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        }
    </script>

</body>

</html>