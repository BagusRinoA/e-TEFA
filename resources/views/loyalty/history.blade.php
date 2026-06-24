@extends('layouts.app')

@section('title', 'Riwayat Penukaran - Loyalty')

@section('content')
    <div class="loyalty-page">
        <div class="page-container">
            <div class="page-header-row">
                <div>
                    <h1 class="page-title">Riwayat Penukaran</h1>
                    <p class="page-subtitle-text">Semua transaksi penukaran poin Anda</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn-primary">Kembali ke Dashboard</a>
            </div>

            @if (session('success'))
                <div class="auth-alert-success" style="margin-bottom:1.5rem">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="auth-alert-error" style="margin-bottom:1.5rem">{{ session('error') }}</div>
            @endif

            @if ($transactions->count() > 0)
                <div class="loyalty-history-list">
                    @foreach ($transactions as $transaction)
                        <div class="content-card" style="padding:0;overflow:hidden">
                            <div style="display:flex;flex-direction:column;gap:0">
                                <div style="display:flex;gap:0">
                                    <div style="width:6rem;flex-shrink:0">
                                        @if ($transaction->item->image_url)
                                            <img src="{{ asset('storage/' . $transaction->item->image_url) }}"
                                                alt="{{ $transaction->item->name }}" style="width:100%;height:6rem;object-fit:cover">
                                        @else
                                            <div style="width:100%;height:6rem;background:var(--color-accent);display:flex;align-items:center;justify-content:center;color:var(--color-muted-foreground);font-size:0.75rem">No Image</div>
                                        @endif
                                    </div>
                                    <div style="flex:1;padding:1rem">
                                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:0.75rem">
                                            <div>
                                                <h3 style="font-size:1.125rem;font-weight:600;color:var(--color-foreground)">{{ $transaction->item->name }}</h3>
                                                <p style="font-size:0.875rem;color:var(--color-muted-foreground);margin-top:0.25rem">{{ Str::limit($transaction->item->description, 60) }}</p>
                                            </div>
                                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;font-size:0.875rem">
                                                <div>
                                                    <p style="color:var(--color-muted-foreground)">Poin Dipakai</p>
                                                    <p style="font-size:1.125rem;font-weight:700;color:var(--color-primary)">{{ $transaction->points_spent }}</p>
                                                </div>
                                                <div>
                                                    <p style="color:var(--color-muted-foreground)">Jumlah</p>
                                                    <p style="font-size:1.125rem;font-weight:700;color:var(--color-foreground)">{{ $transaction->quantity }}x</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;font-size:0.875rem">
                                            <div>
                                                <p style="color:var(--color-muted-foreground)">Tanggal</p>
                                                <p style="font-weight:600">{{ $transaction->created_at->format('d/m/Y') }}</p>
                                            </div>
                                            <div>
                                                <p style="color:var(--color-muted-foreground)">Status</p>
                                                @if ($transaction->status === 'pending')
                                                    <span style="display:inline-block;padding:0.25rem 0.5rem;background:#fef9c3;color:#a16207;border-radius:0.375rem;font-size:0.75rem;font-weight:600">⏳ Pending</span>
                                                @elseif ($transaction->status === 'processing')
                                                    <span style="display:inline-block;padding:0.25rem 0.5rem;background:#eff6ff;color:#1d4ed8;border-radius:0.375rem;font-size:0.75rem;font-weight:600">📦 Diproses</span>
                                                @elseif ($transaction->status === 'shipped')
                                                    <span style="display:inline-block;padding:0.25rem 0.5rem;background:#eef2ff;color:#4338ca;border-radius:0.375rem;font-size:0.75rem;font-weight:600">🚚 Dikirim</span>
                                                @elseif ($transaction->status === 'completed')
                                                    <span style="display:inline-block;padding:0.25rem 0.5rem;background:#f0fdf4;color:#166534;border-radius:0.375rem;font-size:0.75rem;font-weight:600">✓ Selesai</span>
                                                @else
                                                    <span style="display:inline-block;padding:0.25rem 0.5rem;background:#fef2f2;color:#991b1b;border-radius:0.375rem;font-size:0.75rem;font-weight:600">✕ Dibatalkan</span>
                                                @endif
                                            </div>
                                            <div style="text-align:right">
                                                <a href="{{ route('loyalty.transaction-detail', $transaction) }}"
                                                    style="color:var(--color-primary);font-weight:500;text-decoration:none">Lihat →</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="page-pagination">{{ $transactions->links() }}</div>
            @else
                <div class="empty-state">
                    <div style="font-size:4rem;margin-bottom:1rem">📋</div>
                    <p class="empty-state-title" style="font-size:1.25rem">Belum ada riwayat penukaran</p>
                    <p class="empty-state-desc">Mulai kumpulkan poin dan tukar dengan barang impian Anda</p>
                    <a href="{{ route('loyalty.shop') }}" class="btn-pill">Mulai Belanja</a>
                </div>
            @endif
        </div>
    </div>
@endsection
