<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Receipt - Order #{{ $order->order_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
                font-size: 12px;
            }

            .no-print {
                display: none !important;
            }

            .print-only {
                display: block !important;
            }

            .receipt-container {
                box-shadow: none !important;
                border: none !important;
                max-width: 100% !important;
                padding: 10px !important;
            }

            .hide-on-print {
                display: none !important;
            }
        }

        @page {
            margin: 0.5cm;
        }

        .print-only {
            display: none;
        }

        .compact-table th,
        .compact-table td {
            padding: 8px 4px !important;
            font-size: 13px;
        }

        .summary-box {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px;
            background: #f9fafb;
        }
    </style>
</head>

<body class="bg-gray-50 p-4 md:p-6">

    <!-- Back Button (Only on screen) -->
    <div class="max-w-3xl mx-auto mb-4 no-print">
        <a href="{{ route('owner.orders.show', $order) }}"
            class="inline-flex items-center gap-2 text-gray-600 hover:text-orange-600 font-medium text-sm transition-colors group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            Back to Order Details
        </a>
    </div>

    <!-- Receipt Container -->
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-sm receipt-container">

        <!-- Receipt Header - Compact like GrabFood -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <img src="{{ asset(path: 'images/NaNi_Logo.png') }}" alt="NaNi Logo" class="h-20 w-auto">
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">OFFICIAL RECEIPT</h1>
                            <p class="text-gray-700 font-medium">{{ $restaurant->name }}</p>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500">
                        <p>{{ $restaurant->address }}</p>
                        <p>{{ $restaurant->phone }}</p>
                    </div>
                </div>

                <div class="text-right">
                    <p class="text-sm text-gray-500">Receipt No.</p>
                    <p class="text-lg font-bold text-gray-900">#{{ $order->order_number }}</p>
                    <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y') }}
                        {{ $order->created_at->format('H:i') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Order Time</p>
                    <p class="font-medium">{{ $order->created_at->format('d M Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Payment Method</p>
                    <p class="font-medium capitalize">
                        @if($order->payment_method == 'gcash')
                            <span class="flex items-center gap-2">
                                <i class="fas fa-mobile-alt text-green-600"></i>
                                GCash
                                @if($order->gcash_reference_number)
                                    (Ref: {{ $order->gcash_reference_number }})
                                @endif
                            </span>
                        @else
                            <span class="flex items-center gap-2">
                                <i class="fas fa-money-bill text-gray-600"></i>
                                Cash on Delivery
                            </span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Customer & Delivery Info -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 mb-3 uppercase tracking-wide">Customer</h3>
                    <div class="space-y-2">
                        <div>
                            <p class="text-xs text-gray-500">Name</p>
                            <p class="font-medium">{{ $order->customer->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Phone</p>
                            <p class="font-medium">{{ $order->customer_phone }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-gray-900 mb-3 uppercase tracking-wide">Delivery</h3>
                    <div class="space-y-2">
                        <div>
                            <p class="text-xs text-gray-500">Address</p>
                            <p class="font-medium">{{ $order->delivery_address }}</p>
                        </div>
                        @if($order->rider)
                            <div>
                                <p class="text-xs text-gray-500">Rider</p>
                                <p class="font-medium">{{ $order->rider->name }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($order->special_instructions)
                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-100 rounded">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-sticky-note text-yellow-500 mt-0.5 text-sm"></i>
                        <div>
                            <span class="text-xs font-bold text-yellow-700 block mb-1">Special Instructions</span>
                            <p class="text-yellow-800 text-sm">{{ $order->special_instructions }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Two Column Layout: Order Items & Receipt Summary -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-1">
                <!-- Order Items - Takes 2/3 width -->
                <div class="lg:col-span-1">
                    <h3 class="text-sm font-bold text-gray-900 mb-3 uppercase tracking-wide">Order Items</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full compact-table">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-2 text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Item</th>
                                    <th
                                        class="text-right py-2 text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Price</th>
                                    <th
                                        class="text-right py-2 text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Qty</th>
                                    <th
                                        class="text-right py-2 text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="py-3">
                                            <div class="font-medium text-gray-900">{{ $item->menuItem->name }}</div>
                                        </td>
                                        <td class="text-right py-3 whitespace-nowrap">
                                            <div class="text-gray-900">₱{{ number_format($item->unit_price, 2) }}</div>
                                        </td>
                                        <td class="text-right py-3 whitespace-nowrap">
                                            <div class="text-gray-900">{{ $item->quantity }}</div>
                                        </td>
                                        <td class="text-right py-3 whitespace-nowrap">
                                            <div class="font-bold text-gray-900">₱{{ number_format($item->total, 2) }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Receipt Summary - In a box, takes 1/3 width -->
                <div>
                    <div class="summary-box">
                        <h3 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-wide">Payment Summary</h3>

                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium">₱{{ number_format($order->total_amount, 2) }}</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-600">Delivery Fee</span>
                                <span class="font-medium">₱{{ number_format($order->delivery_fee, 2) }}</span>
                            </div>

                            @if($order->payment_method === 'cash_on_delivery' && $order->cash_provided)
                                <div class="pt-3 border-t border-gray-200 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Cash Provided</span>
                                        <span
                                            class="font-medium text-green-600">₱{{ number_format($order->cash_provided, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Change Due</span>
                                        <span class="font-bold text-blue-600">
                                            ₱{{ number_format($order->cash_provided - $order->grand_total, 2) }}
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <div class="pt-3 border-t border-gray-200">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-gray-900">TOTAL</span>
                                    <span
                                        class="text-xl font-bold text-gray-900">₱{{ number_format($order->grand_total, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Status -->
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <p class="text-xs text-gray-500">Payment Status</p>
                                    <p class="font-bold capitalize">
                                        @if($order->payment_method === 'gcash')
                                            {{ $order->gcash_payment_status ?? 'pending' }}
                                        @else
                                            @if($order->status === 'delivered')
                                                Paid
                                            @else
                                                To be collected
                                            @endif
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Order Status</p>
                                    <span class="px-2 py-1 text-xs font-bold rounded-full 
                                        @if($order->status == 'delivered') bg-green-100 text-green-800
                                        @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </div>
                            </div>

                            @if($order->status === 'delivered')
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">Delivered</p>
                                    <p class="text-sm font-medium">
                                        @if($order->delivered_at)
                                            {{ \Carbon\Carbon::parse($order->delivered_at)->format('d M Y H:i') }}
                                        @else
                                            {{ $order->updated_at->format('d M Y H:i') }}
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Notes -->
            <div class="mt-6 text-center text-xs text-gray-500 space-y-1">
                <p>Thank you for choosing {{ $restaurant->name }}!</p>
                <p>Contact: {{ $restaurant->phone }}</p>
                <p class="print-only">Computer-generated receipt. No signature required.</p>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="max-w-3xl mx-auto mt-6 flex flex-col sm:flex-row gap-3 no-print">
        <button onclick="window.print()"
            class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-all shadow-sm">
            <i class="fas fa-print"></i>
            Print Receipt
        </button>

        <a href="{{ route('owner.orders.receipt.download', $order) }}"
            class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition-all shadow-sm">
            <i class="fas fa-download"></i>
            Download PDF
        </a>

        <a href="{{ route('owner.orders.show', $order) }}"
            class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-gray-600 text-white font-bold rounded-lg hover:bg-gray-700 transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i>
            Back to Order
        </a>
    </div>

    <!-- Print Notice -->
    <div class="max-w-3xl mx-auto mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg text-center no-print">
        <p class="text-yellow-800 text-sm">
            <i class="fas fa-info-circle mr-2"></i>
            Click "Print Receipt" to generate a printable version.
        </p>
    </div>

    <script>
        // SweetAlert for print confirmation
        document.querySelector('button[onclick="window.print()"]')?.addEventListener('click', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Print Receipt',
                text: 'Do you want to print this official receipt?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-print mr-2"></i>Print',
                cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
                customClass: {
                    popup: 'rounded-2xl'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.print();
                }
            });
        });
    </script>
</body>

</html>