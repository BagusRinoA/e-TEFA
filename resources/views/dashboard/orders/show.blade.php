@extends('layouts.app')

@section('title', 'Order #{{ $order->id }} - Dashboard')

@section('content')
    <div class="user-dash-page">
        <div class="user-dash-container">
            <div class="user-dash-layout">
                @include('dashboard.partials.sidebar')

                <div class="user-dash-main">
                    {{-- Header --}}
                    <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
                        <div>
                            <h1 class="user-dash-title" style="font-size:1.75rem;">Order #{{ $order->id }}</h1>
                            <p class="user-dash-subtitle">Placed on {{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <a href="{{ route('dashboard.orders') }}" class="btn-outline" style="font-size:0.85rem; padding:0.45rem 1.1rem;">
                            ← Back to Orders
                        </a>
                    </div>

                    {{-- Status Banner --}}
                    @php
                        $bannerMap = [
                            'pending'    => ['bg' => 'rgba(251,191,36,0.15)', 'border' => '#fbbf24', 'color' => '#92400e'],
                            'processing' => ['bg' => 'rgba(59,130,246,0.1)',  'border' => '#3b82f6', 'color' => '#1e40af'],
                            'packed'     => ['bg' => 'rgba(139,92,246,0.1)', 'border' => '#8b5cf6', 'color' => '#5b21b6'],
                            'delivered'  => ['bg' => 'rgba(16,185,129,0.1)', 'border' => '#10b981', 'color' => '#065f46'],
                            'completed'  => ['bg' => 'rgba(34,197,94,0.1)',  'border' => '#22c55e', 'color' => '#14532d'],
                            'canceled'   => ['bg' => 'rgba(239,68,68,0.1)',  'border' => '#ef4444', 'color' => '#7f1d1d'],
                        ];
                        $b = $bannerMap[$order->status] ?? ['bg' => '#f3f4f6', 'border' => '#d1d5db', 'color' => '#374151'];
                    @endphp
                    <div class="user-order-status-banner" style="background:{{ $b['bg'] }}; border-color:{{ $b['border'] }}; color:{{ $b['color'] }};">
                        <div class="user-order-status-banner-dot" style="background:{{ $b['border'] }};"></div>
                        <div>
                            <p class="font-semibold">Status: {{ ucfirst($order->status) }}</p>
                            <p class="text-sm" style="opacity:0.8; margin-top:0.1rem;">
                                @if ($order->status === 'pending') Your order is awaiting payment or confirmation.
                                @elseif ($order->status === 'processing') Your order is being prepared.
                                @elseif ($order->status === 'packed') Your order has been packed and is ready for shipping.
                                @elseif ($order->status === 'delivered') Your order is on the way!
                                @elseif ($order->status === 'completed') Order complete. Thank you for your purchase!
                                @elseif ($order->status === 'canceled') This order has been canceled.
                                @endif
                            </p>
                        </div>
                        @if ($order->payment_status !== 'completed' && $order->status === 'pending')
                            <button
                                class="btn-pill pay-now-btn ml-auto"
                                style="font-size:0.85rem;"
                                data-order-id="{{ $order->id }}"
                                data-redirect-url="{{ route('payment.redirect', $order) }}">
                                Pay Now
                            </button>
                        @endif
                    </div>

                    <div class="user-order-detail-grid">
                        {{-- Left: Order Items --}}
                        <div class="space-y-4">
                            <div class="dash-item-card">
                                <h2 class="font-bold text-lg mb-4" style="color:var(--color-foreground, #111)">Order Items</h2>
                                <div class="user-order-detail-items">
                                    @foreach ($order->items as $item)
                                        <div class="user-order-detail-item">
                                            <div class="user-order-detail-item-img">
                                                @if ($item->product && $item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}"
                                                         alt="{{ $item->product->name }}">
                                                @else
                                                    <div class="user-order-item-img-placeholder">
                                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-semibold" style="color:var(--color-foreground, #111)">
                                                    {{ $item->product->name ?? 'Product deleted' }}
                                                </p>
                                                <p class="text-sm" style="color:#6b7280">
                                                    {{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}
                                                </p>
                                            </div>
                                            <p class="font-bold" style="color:var(--color-primary, #16a34a)">
                                                Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Total --}}
                                <div class="user-order-detail-total">
                                    <span class="text-sm" style="color:#6b7280">Total</span>
                                    <span class="text-xl font-bold" style="color:var(--color-primary, #16a34a)">
                                        Rp {{ number_format($order->total, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Right: Info sidebar --}}
                        <div class="space-y-4">
                            {{-- Payment Info --}}
                            <div class="dash-item-card">
                                <h2 class="font-bold mb-3" style="color:var(--color-foreground, #111)">Payment</h2>
                                <div class="user-order-info-row">
                                    <span>Method</span>
                                    <span>{{ ucwords(str_replace('_', ' ', $order->payment_method ?? '-')) }}</span>
                                </div>
                                <div class="user-order-info-row">
                                    <span>Payment Status</span>
                                    <span class="user-order-status-badge status-{{ $order->payment_status ?? 'pending' }}">
                                        {{ ucfirst($order->payment_status ?? 'pending') }}
                                    </span>
                                </div>
                                @if ($order->payment_completed_at)
                                    <div class="user-order-info-row">
                                        <span>Paid at</span>
                                        <span>{{ $order->payment_completed_at->format('d M Y, H:i') }}</span>
                                    </div>
                                @endif
                                @if ($order->midtrans_transaction_id)
                                    <div class="user-order-info-row">
                                        <span>Transaction ID</span>
                                        <span style="font-family:monospace; font-size:0.8rem;">{{ $order->midtrans_transaction_id }}</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Shipping Info --}}
                            <div class="dash-item-card">
                                <h2 class="font-bold mb-3" style="color:var(--color-foreground, #111)">Shipping</h2>
                                <div class="user-order-info-row">
                                    <span>Name</span>
                                    <span>{{ $order->customer_name }}</span>
                                </div>
                                <div class="user-order-info-row">
                                    <span>Phone</span>
                                    <span>{{ $order->customer_phone }}</span>
                                </div>
                                <div class="user-order-info-row">
                                    <span>Address</span>
                                    <span>{{ $order->shipping_address }}, {{ $order->shipping_city }} {{ $order->shipping_postal_code }}</span>
                                </div>
                                @if ($order->notes)
                                    <div class="user-order-info-row" style="align-items: flex-start;">
                                        <span>Notes</span>
                                        <span>{{ $order->notes }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Snap.js --}}
    <script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const finishBase = @json(url('/payment/finish'));
        const pendingBase = @json(url('/payment/pending'));

        document.querySelectorAll('.pay-now-btn').forEach(btn => {
            btn.addEventListener('click', async function () {
                const orderId = this.dataset.orderId;
                const redirectUrl = this.dataset.redirectUrl;

                this.disabled = true;
                this.textContent = 'Loading…';

                try {
                    const res = await fetch(redirectUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });
                    const data = await res.json();

                    if (data.status !== 'success' || !data.snap_token) {
                        alert(data.message || 'Gagal memuat pembayaran. Coba lagi.');
                        this.disabled = false;
                        this.textContent = 'Pay Now';
                        return;
                    }

                    window.snap.pay(data.snap_token, {
                        onSuccess: () => { window.location.href = finishBase + '/' + orderId; },
                        onPending: () => { window.location.href = pendingBase + '/' + orderId; },
                        onError:   () => { alert('Pembayaran gagal. Silakan coba kembali.'); },
                        onClose:   () => {
                            // User menutup popup tanpa bayar — kembalikan tombol ke semula
                            this.disabled = false;
                            this.textContent = 'Pay Now';
                        }
                    });
                } catch (err) {
                    console.error(err);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                    this.disabled = false;
                    this.textContent = 'Pay Now';
                }
            });
        });
    </script>
@endsection
