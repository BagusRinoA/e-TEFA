@extends('layouts.app')

@section('title', 'My Orders - Dashboard')

@section('content')
    <div class="user-dash-page">
        <div class="user-dash-container">
            <div class="user-dash-layout">
                @include('dashboard.partials.sidebar')

                <div class="user-dash-main">
                    <div>
                        <h1 class="user-dash-title">My Orders</h1>
                        <p class="user-dash-subtitle">Track and manage all your purchases</p>
                    </div>

                    {{-- Filter Status --}}
                    <div class="user-order-filter-bar">
                        @php
                            $statuses = [
                                'all'        => 'All',
                                'pending'    => 'Pending',
                                'processing' => 'Processing',
                                'packed'     => 'Packed',
                                'delivered'  => 'Delivered',
                                'completed'  => 'Completed',
                                'canceled'   => 'Canceled',
                            ];
                            $current = $status ?? 'all';
                        @endphp
                        @foreach ($statuses as $val => $label)
                            <a href="{{ route('dashboard.orders', $val !== 'all' ? ['status' => $val] : []) }}"
                               class="user-order-filter-btn {{ $current === $val ? 'is-active' : '' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>

                    @if ($orders->count())
                        <div class="user-order-list">
                            @foreach ($orders as $order)
                                <div class="user-order-card">
                                    <div class="user-order-card-header">
                                        <div>
                                            <span class="user-order-id">#{{ $order->id }}</span>
                                            <span class="user-order-date">{{ $order->created_at->format('d M Y, H:i') }}</span>
                                        </div>
                                        <span class="user-order-status-badge status-{{ $order->status }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>

                                    <div class="user-order-items-preview">
                                        @foreach ($order->items->take(3) as $item)
                                            <div class="user-order-item-row">
                                                <div class="user-order-item-img">
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
                                                <div class="user-order-item-info">
                                                    <span class="user-order-item-name">{{ $item->product->name ?? 'Product deleted' }}</span>
                                                    <span class="user-order-item-qty">{{ $item->quantity }}x · Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if ($order->items->count() > 3)
                                            <p class="user-order-more">+{{ $order->items->count() - 3 }} more item(s)</p>
                                        @endif
                                    </div>

                                    <div class="user-order-card-footer">
                                        <div>
                                            <span class="user-order-label">Total</span>
                                            <span class="user-order-total">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="user-order-footer-actions">
                                            @if ($order->payment_status !== 'completed' && $order->status === 'pending')
                                                <button
                                                    class="btn-pill pay-now-btn"
                                                    style="font-size:0.8rem; padding: 0.4rem 1rem;"
                                                    data-order-id="{{ $order->id }}"
                                                    data-redirect-url="{{ route('payment.redirect', $order) }}">
                                                    Pay Now
                                                </button>
                                            @endif
                                            <a href="{{ route('dashboard.orders.show', $order) }}" class="btn-outline" style="font-size:0.8rem; padding: 0.4rem 1rem;">
                                                View Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="page-pagination">{{ $orders->links() }}</div>
                    @else
                        <div class="content-card--large empty-state">
                            <div class="empty-state-icon-circle">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <h2 class="empty-state-title">No orders found</h2>
                            <p class="empty-state-desc">You haven't placed any orders yet. Start shopping!</p>
                            <a href="{{ route('products.index') }}" class="btn-pill">Browse Products</a>
                        </div>
                    @endif
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
