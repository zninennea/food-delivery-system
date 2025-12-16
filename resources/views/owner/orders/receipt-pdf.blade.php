<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Official Receipt - Order #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 10px;
        }

        .container {
            max-width: 100%;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e5e5;
        }

        .business-info {
            flex: 1;
        }

        .business-name {
            font-size: 16px;
            font-weight: bold;
            color: #1a202c;
            margin-bottom: 2px;
        }

        .business-details {
            font-size: 9px;
            color: #666;
            margin-bottom: 2px;
        }

        .receipt-title {
            font-size: 14px;
            font-weight: bold;
            color: #1a202c;
            margin-bottom: 5px;
        }

        .receipt-info {
            text-align: right;
        }

        .receipt-no {
            font-size: 12px;
            font-weight: bold;
            color: #1a202c;
            margin-bottom: 3px;
        }

        .receipt-date {
            font-size: 9px;
            color: #666;
        }

        .order-summary {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 9px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .summary-item {
            margin-bottom: 3px;
        }

        .summary-label {
            color: #666;
            font-weight: 500;
        }

        .summary-value {
            font-weight: 600;
            color: #333;
        }

        .section-title {
            font-size: 10px;
            font-weight: bold;
            color: #1a202c;
            margin: 10px 0 5px 0;
            padding-bottom: 3px;
            border-bottom: 1px solid #e5e5e5;
            text-transform: uppercase;
        }

        .customer-info {
            margin-bottom: 10px;
        }

        .info-row {
            margin-bottom: 3px;
        }

        .info-label {
            color: #666;
            font-size: 9px;
        }

        .info-value {
            font-weight: 500;
            color: #333;
        }

        .two-column-layout {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }

        .order-items {
            flex: 2;
        }

        .summary-box {
            flex: 1;
            border: 1.5px solid #d1d5db;
            border-radius: 6px;
            padding: 12px;
            background: #f9fafb;
        }

        .summary-box-title {
            font-size: 10px;
            font-weight: bold;
            color: #1a202c;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
            font-size: 9px;
        }

        th {
            background-color: #f8f9fa;
            padding: 6px 4px;
            text-align: left;
            font-weight: bold;
            color: #666;
            border-bottom: 1px solid #e5e5e5;
            text-transform: uppercase;
            font-size: 8px;
        }

        td {
            padding: 6px 4px;
            border-bottom: 1px solid #e5e5e5;
            vertical-align: top;
        }

        .item-name {
            font-weight: 500;
            color: #333;
        }

        .text-right {
            text-align: right;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            font-size: 9px;
        }

        .total-row {
            margin-top: 6px;
            padding-top: 6px;
            border-top: 1px solid #d1d5db;
            font-weight: bold;
            font-size: 10px;
        }

        .total-value {
            font-size: 12px;
        }

        .cash-details {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 4px;
            padding: 6px;
            margin-top: 6px;
        }

        .payment-status {
            margin-top: 10px;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 4px;
            font-size: 9px;
        }

        .status-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .status-label {
            color: #666;
        }

        .status-value {
            font-weight: 600;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-delivered {
            background: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-other {
            background: #cce5ff;
            color: #004085;
        }

        .special-instructions {
            background: #fff8e1;
            border: 1px solid #ffeaa7;
            border-radius: 4px;
            padding: 6px;
            margin-top: 8px;
            font-size: 9px;
        }

        .instructions-label {
            font-weight: bold;
            color: #e65100;
            margin-bottom: 2px;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e5e5e5;
            text-align: center;
            color: #666;
            font-size: 8px;
        }

        .thank-you {
            font-weight: bold;
            margin-bottom: 3px;
            color: #333;
        }

        .generated-info {
            margin-top: 5px;
            color: #999;
        }

        @media print {
            body {
                padding: 8px;
                font-size: 9px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <div class="business-info">
                <div class="receipt-title">OFFICIAL RECEIPT</div>
                <div class="business-name">{{ $restaurant->name }}</div>
                <div class="business-details">{{ $restaurant->address }}</div>
                <div class="business-details">{{ $restaurant->phone }}</div>
            </div>

            <div class="receipt-info">
                <div class="receipt-no">#{{ $order->order_number }}</div>
                <div class="receipt-date">{{ $order->created_at->format('d M Y H:i') }}</div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="order-summary">
            <div class="summary-grid">
                <div class="summary-item">
                    <span class="summary-label">Order Time:</span>
                    <span class="summary-value">{{ $order->created_at->format('d M Y H:i') }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Payment Method:</span>
                    <span class="summary-value">
                        @if($order->payment_method == 'gcash')
                            GCash
                            @if($order->gcash_reference_number)
                                (Ref: {{ $order->gcash_reference_number }})
                            @endif
                        @else
                            Cash on Delivery
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Customer & Delivery Information -->
        <div class="section-title">Customer & Delivery</div>

        <div class="summary-grid">
            <div class="customer-info">
                <div class="info-row">
                    <div class="info-label">Customer</div>
                    <div class="info-value">{{ $order->customer->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Phone</div>
                    <div class="info-value">{{ $order->customer_phone }}</div>
                </div>
            </div>

            <div class="customer-info">
                <div class="info-row">
                    <div class="info-label">Delivery Address</div>
                    <div class="info-value">{{ $order->delivery_address }}</div>
                </div>
                @if($order->rider)
                    <div class="info-row">
                        <div class="info-label">Rider</div>
                        <div class="info-value">{{ $order->rider->name }}</div>
                    </div>
                @endif
            </div>
        </div>

        @if($order->special_instructions)
            <div class="special-instructions">
                <div class="instructions-label">Special Instructions:</div>
                <div class="instructions-value">{{ $order->special_instructions }}</div>
            </div>
        @endif

        <!-- Two Column Layout -->
        <div class="two-column-layout">
            <!-- Order Items -->
            <div class="order-items">
                <div class="section-title">Order Items</div>

                <table>
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="55%">Item</th>
                            <th width="15%" class="text-right">Price</th>
                            <th width="10%" class="text-right">Qty</th>
                            <th width="15%" class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="item-name">{{ $item->menuItem->name }}</td>
                                <td class="text-right">₱{{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-right">{{ $item->quantity }}</td>
                                <td class="text-right item-name">₱{{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Receipt Summary in Box -->
            <div class="summary-box">
                <div class="summary-box-title">Payment Summary</div>

                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>₱{{ number_format($order->total_amount, 2) }}</span>
                </div>

                <div class="summary-row">
                    <span>Delivery Fee:</span>
                    <span>₱{{ number_format($order->delivery_fee, 2) }}</span>
                </div>

                @if($order->payment_method === 'cash_on_delivery' && $order->cash_provided)
                    <div class="cash-details">
                        <div class="summary-row">
                            <span>Cash Provided:</span>
                            <span style="color: #059669;">₱{{ number_format($order->cash_provided, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Change Due:</span>
                            <span style="color: #2563eb; font-weight: bold;">
                                ₱{{ number_format($order->cash_provided - $order->grand_total, 2) }}
                            </span>
                        </div>
                    </div>
                @endif

                <div class="summary-row total-row">
                    <span>TOTAL:</span>
                    <span class="total-value">₱{{ number_format($order->grand_total, 2) }}</span>
                </div>

                <!-- Payment Status -->
                <div class="payment-status">
                    <div class="status-row">
                        <span class="status-label">Payment:</span>
                        <span class="status-value">
                            @if($order->payment_method === 'gcash')
                                {{ ucfirst($order->gcash_payment_status ?? 'pending') }}
                            @else
                                @if($order->status === 'delivered')
                                    Paid
                                @else
                                    To be collected
                                @endif
                            @endif
                        </span>
                    </div>

                    <div class="status-row">
                        <span class="status-label">Order:</span>
                        <span class="status-badge 
                            @if($order->status == 'delivered') status-delivered
                            @elseif($order->status == 'cancelled') status-cancelled
                            @elseif($order->status == 'pending') status-pending
                            @else status-other @endif">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </div>

                    @if($order->status === 'delivered')
                        <div class="status-row">
                            <span class="status-label">Delivered:</span>
                            <span class="status-value">
                                @if($order->delivered_at)
                                    {{ \Carbon\Carbon::parse($order->delivered_at)->format('d M Y H:i') }}
                                @else
                                    {{ $order->updated_at->format('d M Y H:i') }}
                                @endif
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="thank-you">Thank you for choosing {{ $restaurant->name }}!</div>
            <div>Contact: {{ $restaurant->phone }}</div>
            <div class="generated-info">
                Computer-generated receipt. No signature required.<br>
                Generated: {{ now()->format('d M Y H:i') }}
            </div>
        </div>
    </div>
</body>

</html>