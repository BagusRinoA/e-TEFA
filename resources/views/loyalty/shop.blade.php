@extends('layouts.app')

@section('title', 'Toko Penukar Poin - Loyalty')

@section('content')
    <div class="loyalty-page">
        <div class="page-container">
            <div style="margin-bottom:2rem">
                <h1 class="page-title">Toko Penukar Poin</h1>
                <p class="page-subtitle-text">Pilih barang impian Anda dan tukarkan dengan poin yang telah Anda kumpulkan</p>
            </div>

            @if (session('success'))
                <div class="auth-alert-success" style="margin-bottom:1.5rem">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="auth-alert-error" style="margin-bottom:1.5rem">{{ session('error') }}</div>
            @endif

            <div class="loyalty-points-banner" style="margin-bottom:2rem">
                <div>
                    <p class="loyalty-points-label">Poin Tersedia Anda</p>
                    <p class="loyalty-points-value">{{ $userPoints }}</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn-outline" style="color:#fff;border-color:rgba(255,255,255,0.4);background:rgba(255,255,255,0.15)">
                    Lihat Dashboard →
                </a>
            </div>

            @if ($items->count() > 0)
                <div class="loyalty-grid">
                    @foreach ($items as $item)
                        <div class="loyalty-item-card">
                            <div class="loyalty-item-thumb" style="position:relative">
                                @if ($item->image_url)
                                    <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->name }}">
                                @else
                                    <div style="width:100%;height:100%;background:var(--color-accent);display:flex;align-items:center;justify-content:center;color:var(--color-muted-foreground)">No Image</div>
                                @endif
                                @if ($item->stock <= 0)
                                    <div style="position:absolute;inset:0;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center">
                                        <span style="color:#fff;font-weight:700;font-size:1.125rem">HABIS</span>
                                    </div>
                                @elseif ($item->stock < 5)
                                    <span style="position:absolute;top:0.5rem;right:0.5rem;background:#ef4444;color:#fff;padding:0.25rem 0.5rem;border-radius:0.375rem;font-size:0.75rem;font-weight:700">Stok Terbatas</span>
                                @endif
                            </div>
                            <div class="loyalty-item-body">
                                <h3 class="loyalty-item-name">{{ $item->name }}</h3>
                                <p class="loyalty-item-desc">{{ Str::limit($item->description, 80) }}</p>
                                <div class="loyalty-item-points">
                                    <svg style="width:1rem;height:1rem" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    {{ $item->points_cost }} pts
                                </div>
                                <p class="loyalty-item-stock">Stok: {{ $item->stock }}</p>
                                <a href="{{ route('loyalty.item-detail', $item) }}" class="loyalty-redeem-btn" style="display:block;text-align:center;text-decoration:none">Lihat Detail</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="page-pagination">{{ $items->links() }}</div>
            @else
                <div class="empty-state">
                    <div style="font-size:4rem;margin-bottom:1rem">📭</div>
                    <p class="empty-state-title" style="font-size:1.25rem">Tidak ada item yang tersedia saat ini</p>
                    <p class="empty-state-desc">Silakan cek kembali nanti</p>
                </div>
            @endif
        </div>
    </div>
@endsection
