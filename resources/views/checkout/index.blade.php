@extends('layouts.app')

@section('title', 'Checkout - e-TEFA Kompeni')

@section('content')
    <div class="checkout-page">
        <div class="page-container" style="max-width:56rem">
            @include('components.back-button', ['href' => route('cart.index'), 'label' => 'Kembali ke keranjang'])
            <h1 class="page-title">Checkout</h1>
            <p class="page-subtitle-text" style="margin-bottom:2rem">Konfirmasi pesanan, isi alamat pengiriman, lalu bayar dengan Midtrans</p>

            @if ($errors->any())
                <div class="auth-alert-error" style="margin-bottom:1.5rem">
                    <ul style="list-style:disc;padding-left:1.25rem;font-size:0.875rem">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="checkout-grid">
                {{-- Left: form --}}
                <div>
                    <div class="checkout-section">
                        <h2 class="checkout-section-title">Data pengiriman</h2>
                        <form id="checkout-form">
                            @csrf
                            <input type="hidden" id="selectedProductIds" name="selected_product_ids" value="{{ $selectedProductIdsJson }}">

                            <div class="checkout-field">
                                <label class="checkout-label">Nama lengkap</label>
                                <input type="text" name="customer_name" value="{{ Auth::user()->full_name }}" class="checkout-input" required>
                            </div>
                            <div class="checkout-field">
                                <label class="checkout-label">Email</label>
                                <input type="email" name="customer_email" value="{{ Auth::user()->email }}" class="checkout-input" required>
                            </div>
                            <div class="checkout-field">
                                <label class="checkout-label">Nomor telepon</label>
                                <input type="tel" name="customer_phone" class="checkout-input" required>
                            </div>
                            <div class="checkout-field">
                                <label class="checkout-label">Alamat jalan / detail</label>
                                <input type="text" name="shipping_address" class="checkout-input" required>
                            </div>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                                <div class="checkout-field">
                                    <label class="checkout-label">Kota</label>
                                    <input type="text" name="shipping_city" class="checkout-input" required>
                                </div>
                                <div class="checkout-field">
                                    <label class="checkout-label">Kode pos</label>
                                    <input type="text" name="shipping_postal_code" class="checkout-input" required>
                                </div>
                            </div>
                            <button type="submit" id="place-order-btn" class="checkout-submit-btn">
                                <span id="btn-text">Bayar sekarang</span>
                                <span id="btn-spinner" style="display:none">Memproses…</span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Right: order summary --}}
                <div>
                    <div class="checkout-section">
                        <h2 class="checkout-section-title">Pesananmu</h2>
                        @foreach ($checkoutItems as $item)
                            <div class="checkout-item">
                                <div class="checkout-item-thumb">
                                    @if ($item['product']->image)
                                        <img src="{{ $item['product']->image_url }}" alt="">
                                    @else
                                        <div style="width:100%;height:100%;background:var(--color-accent)"></div>
                                    @endif
                                </div>
                                <div style="flex:1;min-width:0">
                                    <p class="checkout-item-name">{{ $item['product']->name }}</p>
                                    <p class="checkout-item-sub">{{ $item['quantity'] }} × Rp {{ number_format($item['product']->price, 0, ',', '.') }}</p>
                                    <p class="checkout-item-price">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                        <div class="checkout-grand-total" style="margin-top:1rem">
                            <span>Total</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:0.75rem;padding:1rem;margin-top:1rem;font-size:0.875rem;color:#14532d">
                        <p style="font-weight:600;margin-bottom:0.25rem">Pembayaran: Midtrans</p>
                        <p>Setelah klik &ldquo;Bayar sekarang&rdquo;, kamu akan memilih metode bayar di halaman aman Midtrans.</p>
                    </div>
                </div>
            </div>

            {{-- Loading overlay --}}
            <div id="loadingOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:50;align-items:center;justify-content:center">
                <div style="background:#fff;border-radius:1rem;padding:2rem;max-width:22rem;width:calc(100% - 2rem);text-align:center">
                    <div style="position:relative;width:4rem;height:4rem;margin:0 auto 1rem">
                        <div style="position:absolute;inset:0;background:#dcfce7;border-radius:9999px;animation:pulse 2s infinite"></div>
                        <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center">
                            <svg style="width:2rem;height:2rem;color:var(--color-primary);animation:spin 1s linear infinite" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <h3 style="font-size:1.125rem;font-weight:600;margin-bottom:0.5rem">Memproses pembayaran</h3>
                    <p style="font-size:0.875rem;color:var(--color-muted-foreground)">Tunggu sebentar…</p>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <script>
        const finishUrlBase = @json(url('/payment/finish'));
        const pendingUrlBase = @json(url('/payment/pending'));

        document.getElementById('checkout-form').addEventListener('submit', async function (e) {
            e.preventDefault();
            showLoading();
            const formData = new FormData(this);
            try {
                const response = await fetch('{{ route("checkout.process") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                    body: formData
                });
                const data = await response.json();
                if (data.status === 'success') {
                    const payRes = await fetch(data.redirect_url, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
                    });
                    const paymentData = await payRes.json();
                    if (paymentData.status !== 'success' || !paymentData.snap_token) { hideLoading(); showError(paymentData.message || 'Gagal memuat pembayaran Midtrans'); return; }
                    hideLoading();
                    window.snap.pay(paymentData.snap_token, {
                        onSuccess: () => { showLoading(); window.location.href = finishUrlBase + '/' + data.order_id; },
                        onPending: () => { showLoading(); window.location.href = pendingUrlBase + '/' + data.order_id; },
                        onError: () => showError('Pembayaran gagal atau dibatalkan'),
                        onClose: () => hideLoading()
                    });
                } else { showError(data.message || 'Gagal memproses pesanan'); }
            } catch (err) { console.error(err); showError('Terjadi kesalahan. Coba lagi.'); }
        });

        function showLoading() {
            document.getElementById('loadingOverlay').style.display = 'flex';
            document.getElementById('btn-text').style.display = 'none';
            document.getElementById('btn-spinner').style.display = 'inline';
            document.getElementById('place-order-btn').disabled = true;
        }
        function hideLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
            document.getElementById('btn-text').style.display = 'inline';
            document.getElementById('btn-spinner').style.display = 'none';
            document.getElementById('place-order-btn').disabled = false;
        }
        function showError(msg) { hideLoading(); alert(msg); }
    </script>
    <style>
        @keyframes spin { to { transform: rotate(360deg); } }
        @keyframes pulse { 0%,100% { opacity:1; } 50% { opacity:0.5; } }
    </style>
@endsection
