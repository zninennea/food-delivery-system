<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - NaNi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <img src="{{ asset('images/nani-logo.png') }}" alt="NaNi Logo" class="h-10 w-10 mr-3">
                    <div>
                        <a href="/" class="text-xl font-bold text-gray-800">NaNi</a>
                        <p class="text-xs text-gray-500 -mt-1">Checkout</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('customer.dashboard') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <a href="{{ route('customer.cart.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-shopping-cart mr-1"></i>Cart
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-sign-out-alt mr-1"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Checkout</h2>
            </div>

            <form action="{{ route('customer.orders.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <strong class="font-bold">Error!</strong>
                        <ul class="mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                {{-- ADD THESE HIDDEN FIELDS --}}
                <input type="hidden" name="delivery_address" value="{{ Auth::user()->delivery_address }}">
                <input type="hidden" name="customer_phone" value="{{ Auth::user()->phone }}">
                {{-- END HIDDEN FIELDS --}}

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Delivery Information</h3>

                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Customer Name</label>
                                    <p class="mt-1 text-gray-900">{{ Auth::user()->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Delivery Address</label>
                                    <p class="mt-1 text-gray-900">
                                        {{ Auth::user()->delivery_address ?? 'No address set' }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                                    <p class="mt-1 text-gray-900">{{ Auth::user()->phone ?? 'No phone set' }}</p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('customer.profile.edit') }}"
                                    class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-edit mr-1"></i>Update delivery information
                                </a>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Method</h3>

                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="radio" id="cash_on_delivery" name="payment_method"
                                        value="cash_on_delivery"
                                        class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" checked
                                        onchange="togglePaymentFields()">
                                    <label for="cash_on_delivery" class="ml-3 block text-sm font-medium text-gray-700">
                                        Cash on Delivery
                                    </label>
                                    <div id="cash_provided_field" class="ml-7 space-y-2">
                                        <label for="cash_provided" class="block text-sm font-medium text-gray-700">Cash
                                            Provided (Optional)</label>
                                        <input type="number" name="cash_provided" id="cash_provided" step="0.01" min="0"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="0.00">
                                        <p class="text-xs text-gray-500 mt-1">Enter amount if you need change</p>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <input type="radio" id="gcash" name="payment_method" value="gcash"
                                        class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300"
                                        onchange="togglePaymentFields()">
                                    <label for="gcash" class="ml-3 block text-sm font-medium text-gray-700">
                                        GCash
                                    </label>
                                </div>

                                <div id="gcash_fields" class="ml-7 hidden space-y-4">
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Send Payment to
                                                SA*****A NI***E B. :</label>
                                            <div class="flex items-center justify-between mt-1">
                                                <p class="text-gray-900 font-bold text-lg">09123775192</p>
                                                <button type="button"
                                                    class="view-qr-btn text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center"
                                                    data-qr-url="{{ asset('images/naniqr.jpg') }}"
                                                    data-qr-name="GCash Payment">
                                                    <i class="fas fa-qrcode mr-1"></i> View QR
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <label for="gcash_reference_number"
                                                class="block text-sm font-medium text-gray-700">Reference Number</label>
                                            <input type="text" name="gcash_reference_number" id="gcash_reference_number"
                                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Enter GCash reference number">
                                        </div>
                                        <div>
                                            <label for="gcash_receipt"
                                                class="block text-sm font-medium text-gray-700">Payment Receipt</label>
                                            <input type="file" name="gcash_receipt" id="gcash_receipt" accept="image/*"
                                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            <p class="text-xs text-gray-500 mt-1">Upload screenshot of your GCash
                                                payment
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="special_instructions" class="block text-sm font-medium text-gray-700">Special
                                Instructions</label>
                            <textarea name="special_instructions" id="special_instructions" rows="3"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Any special delivery instructions..."></textarea>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>

                            <div class="space-y-3">
                                @foreach($cartItems as $item)
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $item->menuItem->name }}</p>
                                            <p class="text-sm text-gray-500">₱{{ number_format($item->menuItem->price, 2) }}
                                                × {{ $item->quantity }}</p>
                                        </div>
                                        <p class="text-sm font-medium text-gray-900">
                                            ₱{{ number_format($item->quantity * $item->menuItem->price, 2) }}</p>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t border-gray-200 mt-4 pt-4 space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Subtotal</span>
                                    <span
                                        class="text-sm font-medium text-gray-900">₱{{ number_format($total, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Delivery Fee</span>
                                    <span
                                        class="text-sm font-medium text-gray-900">₱{{ number_format($deliveryFee, 2) }}</span>
                                </div>
                                <div class="flex justify-between border-t border-gray-200 pt-2">
                                    <span class="text-base font-medium text-gray-900">Total</span>
                                    <span
                                        class="text-base font-bold text-gray-900">₱{{ number_format($total + $deliveryFee, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-50 p-4 rounded-lg">
                            <button type="submit" id="place-order-btn"
                                class="w-full bg-orange-600 text-white py-3 px-4 rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 font-medium">
                                Place Order
                            </button>
                            <p class="text-xs text-gray-600 mt-2 text-center">
                                By placing your order, you agree to our terms and conditions
                            </p>
                        </div>

                        <div class="text-center">
                            <a href="{{ route('customer.cart.index') }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-arrow-left mr-2"></i>Back to Cart
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="qrModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl max-w-sm w-full mx-4">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Scan QR to Pay</h3>
                <button type="button" id="closeModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 flex justify-center bg-gray-50">
                <img id="qrImage" src="" alt="Payment QR Code" class="max-w-full h-auto rounded-md shadow-sm">
            </div>
            <div class="px-6 py-4 border-t bg-gray-50 flex justify-end">
                <button type="button" id="closeModalBtn"
                    class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        function togglePaymentFields() {
            const codRadio = document.getElementById('cash_on_delivery');
            const gcashRadio = document.getElementById('gcash');
            const cashProvidedField = document.getElementById('cash_provided_field');
            const gcashFields = document.getElementById('gcash_fields');

            if (codRadio.checked) {
                cashProvidedField.classList.remove('hidden');
                gcashFields.classList.add('hidden');
                // Clear GCash fields when switching to COD
                document.getElementById('gcash_reference_number').value = '';
                document.getElementById('gcash_receipt').value = '';
            } else if (gcashRadio.checked) {
                cashProvidedField.classList.add('hidden');
                gcashFields.classList.remove('hidden');
            }
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('qrModal');
            const qrImage = document.getElementById('qrImage');
            const modalTitle = document.getElementById('modalTitle');
            const closeModal = document.getElementById('closeModal');
            const closeModalBtn = document.getElementById('closeModalBtn');

            // Add event listeners to all view qr buttons
            document.querySelectorAll('.view-qr-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const qrUrl = this.getAttribute('data-qr-url');
                    const qrName = this.getAttribute('data-qr-name');

                    qrImage.src = qrUrl;
                    if (qrName) {
                        modalTitle.textContent = qrName;
                    }
                    modal.classList.remove('hidden');
                });
            });

            // Close modal when X is clicked
            closeModal.addEventListener('click', function () {
                modal.classList.add('hidden');
            });

            // Close modal when close button is clicked
            closeModalBtn.addEventListener('click', function () {
                modal.classList.add('hidden');
            });

            // Close modal when clicking outside the image
            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
    </script>
</body>

</html>