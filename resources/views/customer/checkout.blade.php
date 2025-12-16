<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - NaNi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Add SweetAlert2 -->
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

        /* Custom Radio styling */
        .payment-radio:checked+div {
            border-color: #ea580c;
            background-color: #fff7ed;
        }

        .payment-radio:checked+div .check-icon {
            display: block;
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f5f5f4;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #d6d3d1;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a8a29e;
        }
    </style>
</head>

<body class="bg-stone-50 text-gray-800 antialiased">

    <nav
        class="fixed w-full top-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('customer.dashboard') }}" class="flex-shrink-0 flex items-center gap-2 group">
                    <img src="{{ asset('images/NaNi_Logo.png') }}" alt="NaNi Logo"
                        class="h-20 w-auto group-hover:rotate-12 transition-transform duration-300">
                </a>

                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('customer.dashboard') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-home mr-1"></i> Home
                    </a>
                    <a href="{{ route('customer.cart.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Cart
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

    <div class="pt-32 pb-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8 fade-in">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Checkout</h1>
            <p class="text-stone-500">Complete your order details below.</p>
        </div>

        <form action="{{ route('customer.orders.store') }}" method="POST" enctype="multipart/form-data" class="fade-in"
            id="checkoutForm">
            @csrf

            <input type="hidden" name="delivery_address" value="{{ Auth::user()->delivery_address }}">
            <input type="hidden" name="customer_phone" value="{{ Auth::user()->phone }}">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-8">

                    <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6 sm:p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold text-gray-900 font-serif flex items-center gap-2">
                                <span
                                    class="bg-stone-900 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-sans">1</span>
                                Delivery Details
                            </h3>
                            <a href="{{ route('customer.profile.edit') }}"
                                class="text-sm text-orange-600 hover:underline font-medium">Edit</a>
                        </div>

                        @error('delivery_address')
                            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </div>
                        @enderror

                        @error('customer_phone')
                            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                            <div class="bg-stone-50 p-4 rounded-xl">
                                <p class="text-stone-400 text-xs uppercase tracking-wider font-bold mb-1">Deliver To</p>
                                <p class="text-gray-900 font-medium">{{ Auth::user()->name }}</p>
                                <p class="text-gray-600">{{ Auth::user()->delivery_address ?? 'No address set' }}</p>
                            </div>
                            <div class="bg-stone-50 p-4 rounded-xl">
                                <p class="text-stone-400 text-xs uppercase tracking-wider font-bold mb-1">Contact</p>
                                <p class="text-gray-900 font-medium">{{ Auth::user()->phone ?? 'No phone set' }}</p>
                                <p class="text-gray-600">{{ Auth::user()->email }}</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="special_instructions"
                                class="block text-sm font-medium text-gray-700 mb-2">Delivery Instructions
                                (Optional)</label>
                            <textarea name="special_instructions" id="special_instructions" rows="2"
                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm resize-none"
                                placeholder="Gate code, landmark, etc...">{{ old('special_instructions') }}</textarea>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6 sm:p-8">
                        <h3 class="text-xl font-bold text-gray-900 font-serif flex items-center gap-2 mb-6">
                            <span
                                class="bg-stone-900 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-sans">2</span>
                            Payment Method
                        </h3>

                        <div class="space-y-4">
                            <label class="relative block cursor-pointer group">
                                <input type="radio" name="payment_method" value="cash_on_delivery"
                                    class="peer sr-only payment-radio" {{ old('payment_method', 'cash_on_delivery') == 'cash_on_delivery' ? 'checked' : '' }}
                                    onchange="togglePaymentFields()">
                                <div
                                    class="p-5 rounded-xl border border-gray-200 hover:border-orange-200 transition-all flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">Cash on Delivery</p>
                                        <p class="text-xs text-gray-500">Pay when you receive your order</p>
                                    </div>
                                    <i
                                        class="fas fa-check-circle text-orange-600 text-xl ml-auto hidden check-icon"></i>
                                </div>
                            </label>

                            <div id="cash_provided_field"
                                class="ml-14 transition-all duration-300 {{ old('payment_method', 'cash_on_delivery') == 'gcash' ? 'hidden' : '' }}">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">I
                                    will pay with (Optional)</label>
                                <div class="relative max-w-xs">
                                    <span
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">₱</span>
                                    <input type="number" name="cash_provided" step="0.01"
                                        class="pl-7 block w-full border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                                        placeholder="1000" value="{{ old('cash_provided') }}">
                                </div>
                                @error('cash_provided')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-stone-400 mt-1">Enter amount if you need change.</p>
                            </div>

                            <label class="relative block cursor-pointer group">
                                <input type="radio" name="payment_method" value="gcash"
                                    class="peer sr-only payment-radio" {{ old('payment_method') == 'gcash' ? 'checked' : '' }} onchange="togglePaymentFields()">
                                <div
                                    class="p-5 rounded-xl border border-gray-200 hover:border-orange-200 transition-all flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                                        <i class="fas fa-mobile-alt"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">GCash</p>
                                        <p class="text-xs text-gray-500">Scan QR and upload receipt</p>
                                    </div>
                                    <i
                                        class="fas fa-check-circle text-orange-600 text-xl ml-auto hidden check-icon"></i>
                                </div>
                            </label>

                            <div id="gcash_fields"
                                class="ml-14 space-y-4 bg-stone-50 p-4 rounded-xl border border-stone-100 {{ old('payment_method') == 'gcash' ? '' : 'hidden' }}">
                                @error('gcash_reference_number')
                                    <div class="p-2 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                @error('gcash_receipt')
                                    <div class="p-2 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-bold text-gray-700">Send to: 09123775192</span>
                                        <button type="button"
                                            class="text-blue-600 text-xs font-bold hover:underline flex items-center gap-1 view-qr-btn"
                                            data-qr-url="{{ asset('images/naniqr.jpg') }}">
                                            <i class="fas fa-qrcode"></i> View QR Code
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Ref
                                        Number <span class="text-red-500">*</span></label>
                                    <input type="text" name="gcash_reference_number" id="gcash_reference_number"
                                        class="block w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm {{ $errors->has('gcash_reference_number') ? 'border-red-300' : '' }}"
                                        placeholder="e.g. 100234..." value="{{ old('gcash_reference_number') }}">
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Upload
                                        Receipt <span class="text-red-500">*</span></label>
                                    <input type="file" name="gcash_receipt" id="gcash_receipt" accept="image/*"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 {{ $errors->has('gcash_receipt') ? 'border-red-300' : '' }}">
                                    <p class="text-xs text-stone-400 mt-1">Upload a screenshot of your payment
                                        confirmation</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-xl border border-stone-100 p-6 sm:p-8 sticky top-28">
                        <h3 class="text-xl font-bold text-gray-900 font-serif mb-6">Order Summary</h3>

                        <div class="space-y-3 mb-6 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                            @foreach($cartItems as $item)
                                <div class="flex justify-between text-sm">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $item->menuItem->name }} <span
                                                class="text-stone-400">x{{ $item->quantity }}</span></p>
                                    </div>
                                    <p class="text-gray-600">
                                        ₱{{ number_format($item->quantity * $item->menuItem->price, 2) }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t border-gray-100 pt-4 space-y-2 mb-6">
                            <div class="flex justify-between text-stone-600 text-sm">
                                <span>Subtotal</span>
                                <span>₱{{ number_format($total, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-stone-600 text-sm">
                                <span>Delivery Fee</span>
                                <span>₱{{ number_format($deliveryFee, 2) }}</span>
                            </div>
                            <div
                                class="flex justify-between text-gray-900 font-bold text-lg pt-2 mt-2 border-t border-gray-100">
                                <span>Total</span>
                                <span class="text-orange-600">₱{{ number_format($total + $deliveryFee, 2) }}</span>
                            </div>
                        </div>

                        <button type="submit" id="submitOrderBtn"
                            class="w-full bg-gradient-to-r from-orange-600 to-red-600 text-white py-4 rounded-xl font-bold shadow-lg hover:shadow-orange-500/30 transform hover:-translate-y-1 transition-all duration-200">
                            Place Order
                        </button>

                        <p class="text-xs text-center text-stone-400 mt-4">
                            By placing your order, you agree to our terms of service.
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Fixed QR Modal - Properly Centered -->
    <div id="qrModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[60] hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl max-w-sm w-full overflow-hidden shadow-2xl transform transition-all">
                <div class="p-6 text-center">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Scan to Pay</h3>
                    <div class="bg-blue-50 p-4 rounded-xl inline-block mb-4 border border-blue-100">
                        <img id="qrImage" src="" alt="GCash QR" class="w-64 h-64 object-contain rounded-lg mx-auto">
                    </div>
                    <p class="text-sm text-gray-600 mb-6">Scan with your GCash app</p>
                    <button type="button" id="closeModal"
                        class="w-full bg-stone-100 text-stone-700 font-bold py-3 rounded-xl hover:bg-stone-200 transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Payment Toggle Logic
        function togglePaymentFields() {
            const codRadio = document.querySelector('input[value="cash_on_delivery"]');
            const gcashRadio = document.querySelector('input[value="gcash"]');
            const cashField = document.getElementById('cash_provided_field');
            const gcashFields = document.getElementById('gcash_fields');

            // Input elements to clear
            const gcashRef = document.getElementById('gcash_reference_number');
            const gcashReceipt = document.getElementById('gcash_receipt');
            const cashInput = document.querySelector('input[name="cash_provided"]');

            if (codRadio.checked) {
                cashField.classList.remove('hidden');
                gcashFields.classList.add('hidden');
                // Clear GCash inputs so they don't block submission if required
                gcashRef.value = '';
                gcashReceipt.value = '';
            } else {
                cashField.classList.add('hidden');
                gcashFields.classList.remove('hidden');
                // Clear Cash input
                cashInput.value = '';
            }
        }

        // QR Modal Logic
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Toast for notifications
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

            const modal = document.getElementById('qrModal');
            const qrImage = document.getElementById('qrImage');
            const closeBtn = document.getElementById('closeModal');

            // Open QR Modal
            document.querySelectorAll('.view-qr-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    qrImage.src = this.dataset.qrUrl;
                    modal.classList.remove('hidden');
                });
            });

            // Close QR Modal
            closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
            modal.addEventListener('click', (e) => { if (e.target === modal) modal.classList.add('hidden'); });

            // Form Validation
            const checkoutForm = document.getElementById('checkoutForm');
            const submitBtn = document.getElementById('submitOrderBtn');

            if (checkoutForm) {
                checkoutForm.addEventListener('submit', function (e) {
                    const gcashRadio = document.querySelector('input[value="gcash"]:checked');

                    if (gcashRadio) {
                        const gcashRef = document.getElementById('gcash_reference_number');
                        const gcashReceipt = document.getElementById('gcash_receipt');

                        // Check if GCash reference is empty
                        if (!gcashRef.value.trim()) {
                            e.preventDefault();
                            Toast.fire({
                                icon: 'error',
                                title: 'GCash Reference Number is required'
                            });
                            gcashRef.focus();
                            return false;
                        }

                        // Check if GCash receipt is empty
                        if (!gcashReceipt.value) {
                            e.preventDefault();
                            Toast.fire({
                                icon: 'error',
                                title: 'GCash receipt is required'
                            });
                            return false;
                        }
                    }

                    // Optional: Show loading state
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
                });
            }

            // Show old validation errors
            @if($errors->any())
                // Scroll to first error
                const firstError = document.querySelector('.text-red-600, [class*="border-red"]');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            @endif

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
        });
    </script>
</body>

</html>