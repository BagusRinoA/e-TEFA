@extends('layouts.app')

@section('title', 'Payment Status - e-TEFA Kompeni')

@section('content')
    <div class="payment-page">
        <div style="max-width:40rem;width:100%">
            @if ($order->payment_status === 'completed')
                <div class="payment-card">
                    <div class="payment-icon payment-icon--success">
                        <svg style="width:2.5rem;height:2.5rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h1 class="payment-title">Payment Successful!</h1>
                    <p class="payment-desc">Your payment has been confirmed and your order is being processed.</p>

                    <div class="payment-detail-box">
                        <h2 style="font-size:1rem;font-weight:600;margin-bottom:0.75rem">Order Details</h2>
                        <div class="payment-detail-row"><span class="payment-detail-label">Order ID:</span><span class="payment-detail-value">#{{ $order->id }}</span></div>
                        <div class="payment-detail-row"><span class="payment-detail-label">Transaction ID:</span><span class="payment-detail-value" style="word-break:break-all">{{ $order->midtrans_transaction_id }}</span></div>
                        <div class="payment-detail-row"><span class="payment-detail-label">Order Date:</span><span class="payment-detail-value">{{ $order->created_at->format('d M Y H:i') }}</span></div>
                        <div class="payment-detail-row"><span class="payment-detail-label">Payment Date:</span><span class="payment-detail-value">{{ $order->payment_completed_at?->format('d M Y H:i') ?? '-' }}</span></div>
                    </div>

                    <div class="payment-detail-box" style="margin-top:1rem">
                        <h2 style="font-size:1rem;font-weight:600;margin-bottom:0.75rem">Shipping Information</h2>
                        <div class="payment-detail-row"><span class="payment-detail-label">Name:</span><span class="payment-detail-value">{{ $order->customer_name }}</span></div>
                        <div class="payment-detail-row"><span class="payment-detail-label">Email:</span><span class="payment-detail-value">{{ $order->customer_email }}</span></div>
                        <div class="payment-detail-row"><span class="payment-detail-label">Phone:</span><span class="payment-detail-value">{{ $order->customer_phone }}</span></div>
                        <div class="payment-detail-row"><span class="payment-detail-label">Address:</span><span class="payment-detail-value">{{ $order->shipping_address }}, {{ $order->shipping_city }} {{ $order->shipping_postal_code }}</span></div>
                    </div>

                    <div class="payment-detail-box" style="margin-top:1rem">
                        <h2 style="font-size:1rem;font-weight:600;margin-bottom:0.75rem">Items Ordered</h2>
                        @foreach ($order->items as $item)
                            <div class="payment-detail-row">
                                <div><p style="font-weight:500">{{ $item->product_name }}</p><p style="font-size:0.875rem;color:var(--color-muted-foreground)">Qty: {{ $item->quantity }}</p></div>
                                <span style="font-weight:500">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                        <div style="display:flex;justify-content:space-between;padding-top:0.75rem;margin-top:0.75rem;border-top:1px solid var(--color-border);font-size:1.125rem;font-weight:700">
                            <span>Total Amount</span><span style="color:var(--color-primary)">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="payment-actions">
                        <a href="{{ route('dashboard') }}" class="btn-primary">Go to Dashboard</a>
                        <a href="{{ route('cart.index') }}" class="btn-outline">Continue Shopping</a>
                    </div>
                </div>

            @elseif ($order->payment_status === 'failed')
                <div class="payment-card">
                    <div class="payment-icon payment-icon--failed">
                        <svg style="width:2.5rem;height:2.5rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <h1 class="payment-title">Payment Failed</h1>
                    <p class="payment-desc">Your payment could not be processed. Please try again.</p>
                    <div class="payment-detail-box">
                        <div class="payment-detail-row"><span class="payment-detail-label">Order ID:</span><span class="payment-detail-value">#{{ $order->id }}</span></div>
                        <div class="payment-detail-row"><span class="payment-detail-label">Status:</span><span class="payment-detail-value">Payment Failed</span></div>
                        <div class="payment-detail-row"><span class="payment-detail-label">Attempt Time:</span><span class="payment-detail-value">{{ $order->updated_at->format('d M Y H:i') }}</span></div>
                        <div class="payment-detail-row"><span class="payment-detail-label">Total Amount:</span><span class="payment-detail-value" style="color:var(--color-primary)">Rp {{ number_format($order->total, 0, ',', '.') }}</span></div>
                    </div>
                    <p style="font-size:0.875rem;color:var(--color-muted-foreground);margin-bottom:1.5rem">You can retry this payment or contact our support team for assistance.</p>
                    <div class="payment-actions">
                        <a href="{{ route('cart.index') }}" class="btn-primary">Ke keranjang</a>
                        <a href="{{ route('pages.contact') }}" class="btn-outline">Contact Support</a>
                    </div>
                </div>

            @elseif ($order->payment_status === 'pending')
                <div class="payment-card">
                    <div class="payment-icon payment-icon--pending">
                        <svg style="width:2.5rem;height:2.5rem;animation:spin 1s linear infinite" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h1 class="payment-title">Payment Processing</h1>
                    <p class="payment-desc">Your payment is being processed. Please wait...</p>
                    <div class="payment-detail-box">
                        <div class="payment-detail-row"><span class="payment-detail-label">Order ID:</span><span class="payment-detail-value">#{{ $order->id }}</span></div>
                        <div class="payment-detail-row"><span class="payment-detail-label">Status:</span><span class="payment-detail-value">Payment Pending</span></div>
                        <div class="payment-detail-row"><span class="payment-detail-label">Transaction ID:</span><span class="payment-detail-value">{{ $order->midtrans_transaction_id }}</span></div>
                        <div class="payment-detail-row"><span class="payment-detail-label">Total Amount:</span><span class="payment-detail-value" style="color:var(--color-primary)">Rp {{ number_format($order->total, 0, ',', '.') }}</span></div>
                    </div>
                    <p style="font-size:0.875rem;color:var(--color-muted-foreground);margin-bottom:1.5rem">Your payment is currently being verified. You will receive an email confirmation once completed.</p>
                    <div class="payment-actions">
                        <a href="{{ route('cart.index') }}" class="btn-primary">Ke keranjang</a>
                        <a href="{{ route('dashboard') }}" class="btn-outline">Go to Dashboard</a>
                    </div>
                </div>

            @else
                <div class="payment-card">
                    <h1 class="payment-title">Payment Status</h1>
                    <p class="payment-desc">Status: {{ $order->payment_status ?? 'Unknown' }}</p>
                    <div class="payment-actions">
                        <a href="{{ route('cart.index') }}" class="btn-primary">Ke keranjang</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <style>@keyframes spin { to { transform: rotate(360deg); } }</style>
@endsection
