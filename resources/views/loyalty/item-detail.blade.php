@extends('layouts.app')

@section('title', $item->name . ' - Loyalty Shop')

@section('content')
    <div class="loyalty-page">
        <div class="page-container" style="max-width:56rem">
            <a href="{{ route('loyalty.shop') }}" class="btn-outline" style="margin-bottom:1.5rem;display:inline-flex">← Kembali ke Toko</a>

            @if (session('success'))<div class="auth-alert-success" style="margin-bottom:1.5rem">{{ session('success') }}</div>@endif
            @if (session('error'))<div class="auth-alert-error" style="margin-bottom:1.5rem">{{ session('error') }}</div>@endif

            <div style="display:grid;gap:2rem" class="loyalty-detail-grid">
                <div>
                    @if ($item->image_url)
                        <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->name }}" style="width:100%;border-radius:1rem;box-shadow:0 10px 30px rgba(0,0,0,0.1)">
                    @else
                        <div style="width:100%;aspect-ratio:1;background:var(--color-accent);border-radius:1rem;display:flex;align-items:center;justify-content:center;color:var(--color-muted-foreground)">No Image</div>
                    @endif
                </div>

                <div>
                    <h1 style="font-size:2rem;font-weight:800;color:var(--color-foreground);margin-bottom:1.5rem">{{ $item->name }}</h1>
                    <div style="background:#eff6ff;border:2px solid #bfdbfe;border-radius:0.75rem;padding:1.5rem;margin-bottom:1.5rem">
                        <p style="font-size:0.875rem;color:var(--color-muted-foreground);margin-bottom:0.25rem">Harga Poin</p>
                        <p style="font-size:3rem;font-weight:800;color:#2563eb">{{ $item->points_cost }}</p>
                        <p style="font-size:0.875rem;color:var(--color-muted-foreground)">poin yang diperlukan</p>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem">
                        <div style="background:var(--color-secondary);padding:1rem;border-radius:0.75rem">
                            <p style="font-size:0.875rem;color:var(--color-muted-foreground)">Stok Tersedia</p>
                            <p style="font-size:1.5rem;font-weight:700">{{ $item->stock }}</p>
                        </div>
                        <div style="background:var(--color-secondary);padding:1rem;border-radius:0.75rem">
                            <p style="font-size:0.875rem;color:var(--color-muted-foreground)">Max Per User</p>
                            <p style="font-size:1.5rem;font-weight:700">{{ $item->max_redemption_per_user }}x</p>
                        </div>
                    </div>

                    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:0.75rem;padding:1rem;margin-bottom:1.5rem">
                        <p style="font-size:0.875rem;color:var(--color-muted-foreground)">Poin Anda</p>
                        <p style="font-size:2rem;font-weight:700;color:var(--color-primary)">{{ $loyaltyPoint->current_points }}</p>
                        @if ($loyaltyPoint->current_points >= $item->points_cost)
                            <p style="font-size:0.875rem;color:#16a34a">✓ Poin Anda cukup!</p>
                        @else
                            <p style="font-size:0.875rem;color:var(--color-destructive)">✕ Kurang {{ $item->points_cost - $loyaltyPoint->current_points }} poin</p>
                        @endif
                    </div>

                    @if ($item->isAvailable() && $canRedeem)
                        <form action="{{ route('loyalty.redeem', $item) }}" method="POST" style="margin-bottom:1rem;background:var(--color-card);border:1px solid var(--color-border);border-radius:1rem;padding:1.5rem">
                            @csrf
                            <h3 style="font-size:1.125rem;font-weight:700;margin-bottom:1rem;color:var(--color-foreground)">Detail Pengiriman</h3>
                            
                            <div style="margin-bottom:1rem">
                                <label style="display:block;font-size:0.875rem;font-weight:500;margin-bottom:0.5rem">Nama Penerima <span style="color:var(--color-destructive)">*</span></label>
                                <input type="text" name="recipient_name" required class="profile-input" value="{{ old('recipient_name', Auth::user()->username) }}">
                            </div>

                            <div style="margin-bottom:1rem">
                                <label style="display:block;font-size:0.875rem;font-weight:500;margin-bottom:0.5rem">No. Telepon <span style="color:var(--color-destructive)">*</span></label>
                                <input type="text" name="recipient_phone" required class="profile-input" value="{{ old('recipient_phone') }}">
                            </div>

                            <div style="margin-bottom:1rem">
                                <label style="display:block;font-size:0.875rem;font-weight:500;margin-bottom:0.5rem">Alamat Lengkap <span style="color:var(--color-destructive)">*</span></label>
                                <textarea name="shipping_address" required rows="3" class="profile-input">{{ old('shipping_address') }}</textarea>
                            </div>

                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem">
                                <div>
                                    <label style="display:block;font-size:0.875rem;font-weight:500;margin-bottom:0.5rem">Kota <span style="color:var(--color-destructive)">*</span></label>
                                    <input type="text" name="shipping_city" required class="profile-input" value="{{ old('shipping_city') }}">
                                </div>
                                <div>
                                    <label style="display:block;font-size:0.875rem;font-weight:500;margin-bottom:0.5rem">Kode Pos <span style="color:var(--color-destructive)">*</span></label>
                                    <input type="text" name="shipping_postal_code" required class="profile-input" value="{{ old('shipping_postal_code') }}">
                                </div>
                            </div>

                            <div style="border-top:1px solid var(--color-border);padding-top:1.5rem;margin-bottom:1.5rem">
                                <label style="display:block;font-size:0.875rem;font-weight:500;margin-bottom:0.5rem">Jumlah Item <span style="color:var(--color-destructive)">*</span></label>
                                <input type="number" name="quantity" min="1" max="{{ $item->max_redemption_per_user }}" value="1" required class="profile-input" style="width:8rem">
                                <p style="font-size:0.75rem;color:var(--color-muted-foreground);margin-top:0.25rem">Maksimal penukaran per user: {{ $item->max_redemption_per_user }}x</p>
                            </div>
                            
                            <button type="submit" class="loyalty-redeem-btn" style="font-size:1rem;padding:0.875rem;width:100%">🎁 Konfirmasi Penukaran</button>
                        </form>
                    @elseif (!$item->is_active)
                        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:0.75rem;padding:1rem;text-align:center;margin-bottom:1rem"><p style="color:#b91c1c;font-weight:600">Item tidak dapat ditukar saat ini</p></div>
                    @elseif ($item->stock <= 0)
                        <div style="background:var(--color-secondary);border:1px solid var(--color-border);border-radius:0.75rem;padding:1rem;text-align:center;margin-bottom:1rem"><p style="font-weight:600">Stok item telah habis</p></div>
                    @elseif (!$canRedeem)
                        <div style="background:#fff7ed;border:1px solid #fed7aa;border-radius:0.75rem;padding:1rem;text-align:center;margin-bottom:1rem"><p style="color:#c2410c;font-weight:600">Anda sudah mencapai batas maksimal penukaran</p></div>
                    @endif

                    <a href="{{ route('loyalty.shop') }}" class="btn-outline" style="width:100%;justify-content:center">Kembali ke Toko</a>
                </div>
            </div>

            @if ($item->description)
                <div class="content-card" style="margin-top:2rem">
                    <h2 style="font-size:1.25rem;font-weight:700;margin-bottom:0.75rem">Deskripsi Lengkap</h2>
                    <p style="color:var(--color-foreground);line-height:1.75">{{ $item->description }}</p>
                </div>
            @endif

            <div style="margin-top:1.5rem;background:#eff6ff;border:1px solid #bfdbfe;border-radius:1rem;padding:1.5rem">
                <h3 style="font-size:1rem;font-weight:700;margin-bottom:1rem">ℹ️ Proses Penukaran</h3>
                <ol style="display:flex;flex-direction:column;gap:0.75rem">
                    @foreach(['Pilih jumlah item','Klik "Tukar Sekarang"','Admin memproses konfirmasi','Barang dikirimkan setelah disetujui'] as $i => $step)
                        <li style="display:flex;align-items:center;gap:0.75rem">
                            <span style="background:#2563eb;color:#fff;border-radius:9999px;width:1.5rem;height:1.5rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:0.75rem;font-weight:700">{{ $i+1 }}</span>
                            <span style="font-size:0.9375rem">{{ $step }}</span>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
    <style>@media (min-width:768px) { .loyalty-detail-grid { grid-template-columns:1fr 1fr; } }</style>
@endsection
