@extends('layouts.app')

@section('title', 'Detail Transaksi Penukaran')

@section('content')
    <div class="loyalty-page">
        <div class="page-container" style="max-width:40rem">
            @if (session('success'))<div class="auth-alert-success" style="margin-bottom:1.5rem">{{ session('success') }}</div>@endif
            @if (session('error'))<div class="auth-alert-error" style="margin-bottom:1.5rem">{{ session('error') }}</div>@endif

            {{-- Status --}}
            <div class="payment-card" style="margin-bottom:1.5rem">
                @if ($transaction->status === 'pending')
                    <div class="payment-icon payment-icon--pending"><svg style="width:2.5rem;height:2.5rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <h1 class="payment-title" style="color:#a16207">Menunggu Persetujuan</h1>
                    <p class="payment-desc">Admin sedang memproses permintaan penukaran Anda</p>
                @elseif ($transaction->status === 'completed')
                    <div class="payment-icon payment-icon--success"><svg style="width:2.5rem;height:2.5rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
                    <h1 class="payment-title" style="color:#16a34a">Penukaran Berhasil</h1>
                    <p class="payment-desc">Barang akan segera dikirimkan kepada Anda</p>
                @else
                    <div class="payment-icon payment-icon--failed"><svg style="width:2.5rem;height:2.5rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></div>
                    <h1 class="payment-title" style="color:#dc2626">Penukaran Dibatalkan</h1>
                    <p class="payment-desc">Poin Anda telah dikembalikan</p>
                @endif
            </div>

            {{-- Transaction details --}}
            <div class="content-card" style="margin-bottom:1.5rem">
                <h2 style="font-size:1.25rem;font-weight:700;margin-bottom:1rem">Detail Transaksi</h2>
                <div class="payment-detail-row"><span class="payment-detail-label">ID Transaksi:</span><span class="payment-detail-value">#{{ $transaction->id }}</span></div>
                <div class="payment-detail-row"><span class="payment-detail-label">Tanggal Request:</span><span class="payment-detail-value">{{ $transaction->created_at->format('d/m/Y H:i') }}</span></div>
                @if ($transaction->redeemed_at)
                    <div class="payment-detail-row"><span class="payment-detail-label">Tanggal Disetujui:</span><span class="payment-detail-value">{{ $transaction->redeemed_at->format('d/m/Y H:i') }}</span></div>
                @endif
                <div class="payment-detail-row">
                    <span class="payment-detail-label">Status:</span>
                    <span>
                        @if ($transaction->status === 'pending')
                            <span style="padding:0.25rem 0.75rem;background:#fef9c3;color:#a16207;border-radius:9999px;font-size:0.875rem;font-weight:600">⏳ Pending</span>
                        @elseif ($transaction->status === 'completed')
                            <span style="padding:0.25rem 0.75rem;background:#f0fdf4;color:#166534;border-radius:9999px;font-size:0.875rem;font-weight:600">✓ Completed</span>
                        @else
                            <span style="padding:0.25rem 0.75rem;background:#fef2f2;color:#991b1b;border-radius:9999px;font-size:0.875rem;font-weight:600">✕ Cancelled</span>
                        @endif
                    </span>
                </div>
            </div>

            {{-- Item details --}}
            <div class="content-card" style="margin-bottom:1.5rem">
                <h2 style="font-size:1.25rem;font-weight:700;margin-bottom:1rem">Detail Item</h2>
                <div style="display:flex;gap:1rem">
                    @if ($transaction->item->image_url)
                        <img src="{{ asset('storage/' . $transaction->item->image_url) }}" alt="{{ $transaction->item->name }}"
                            style="width:8rem;height:8rem;object-fit:cover;border-radius:0.5rem;flex-shrink:0">
                    @else
                        <div style="width:8rem;height:8rem;background:var(--color-accent);border-radius:0.5rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--color-muted-foreground);font-size:0.75rem">No Image</div>
                    @endif
                    <div style="flex:1">
                        <h3 style="font-size:1.25rem;font-weight:700;margin-bottom:0.5rem">{{ $transaction->item->name }}</h3>
                        <p style="color:var(--color-muted-foreground);margin-bottom:1rem;font-size:0.875rem">{{ $transaction->item->description }}</p>
                        <div class="checkout-total-row"><span style="color:var(--color-muted-foreground)">Harga Per Unit:</span><span style="font-weight:600;color:var(--color-primary)">{{ $transaction->item->points_cost }} poin</span></div>
                        <div class="checkout-total-row"><span style="color:var(--color-muted-foreground)">Jumlah:</span><span style="font-weight:600">{{ $transaction->quantity }}</span></div>
                        <div class="checkout-grand-total"><span>Total Poin:</span><span style="color:var(--color-primary)">{{ $transaction->points_spent }}</span></div>
                    </div>
                </div>
            </div>

            @if ($transaction->notes)
                <div style="background:#fff7ed;border:1px solid #fed7aa;border-radius:0.75rem;padding:1.25rem;margin-bottom:1.5rem">
                    <h3 style="font-weight:700;margin-bottom:0.5rem">📝 Catatan</h3>
                    <p style="color:var(--color-foreground)">{{ $transaction->notes }}</p>
                </div>
            @endif

            @if ($transaction->isPending())
                <div style="background:#fffbeb;border:1px solid #fcd34d;border-radius:0.75rem;padding:1.25rem;margin-bottom:1.5rem">
                    <p style="margin-bottom:1rem">Transaksi masih dalam status pending. Anda dapat membatalkannya jika berubah pikiran.</p>
                    <form action="{{ route('loyalty.transaction.cancel', $transaction) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin membatalkan? Poin Anda akan dikembalikan.');">
                        @csrf
                        <button type="submit" class="btn-danger" style="width:auto;padding:0.5rem 1.5rem">✕ Batalkan Transaksi</button>
                    </form>
                </div>
            @endif

            <div style="text-align:center">
                <a href="{{ route('loyalty.history') }}" class="btn-primary">Kembali ke Riwayat</a>
            </div>
        </div>
    </div>
@endsection
