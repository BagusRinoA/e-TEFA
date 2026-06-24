@extends('layouts.app')

@section('title', 'Konfigurasi Point Earning')

@section('content')
    <div class="admin-page admin-page--dashboard">
        <div class="admin-container">
            <div class="admin-layout">
                {{-- Sidebar --}}
                @include('admin.partials.sidebar')

                {{-- Main Content --}}
                <div class="admin-main">
                {{-- Header --}}
                <div class="admin-page-header">
                    <div>
                        <h1 class="admin-page-title">Konfigurasi Point Earning</h1>
                        <p class="admin-page-subtitle">Kelola sistem penghasilan poin untuk pelanggan</p>
                    </div>
                    <a href="{{ route('admin.loyalty.configurations.create') }}" class="admin-btn admin-btn-primary">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Konfigurasi
                    </a>
                </div>

                @if (session('success'))
                    <div class="admin-alert admin-alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="admin-alert admin-alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Grid Layout --}}
                <div class="admin-form-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
                    @forelse($configurations as $config)
                        <div class="admin-stat-card" style="display:flex; flex-direction:column;">
                            {{-- Card Header --}}
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="font-bold text-foreground" style="font-size:1.1rem;">Range Pembelanjaan</h3>
                                </div>
                                @if ($config->is_active)
                                    <span class="status-badge status-active">Aktif</span>
                                @else
                                    <span class="status-badge status-inactive">Nonaktif</span>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="space-y-3 mb-6 flex-1">
                                <div>
                                    <p class="text-sm text-muted-foreground mb-1">Rentang Belanja</p>
                                    <p class="text-xl font-bold text-primary">
                                        Rp {{ number_format($config->min_purchase_amount, 0, ',', '.') }}
                                        @if ($config->max_purchase_amount)
                                            <span class="text-sm font-medium text-muted-foreground"> - Rp {{ number_format($config->max_purchase_amount, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-sm font-medium text-muted-foreground"> & ke atas</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="pt-3" style="border-top:1px solid var(--color-border)">
                                    <p class="text-sm text-muted-foreground mb-1">Poin yang Diperoleh</p>
                                    <p class="text-xl font-bold" style="color:var(--color-foreground)">{{ $config->points_earned }} <span class="text-sm font-medium text-muted-foreground">poin</span></p>
                                </div>
                                @if ($config->description)
                                    <div class="pt-3" style="border-top:1px solid var(--color-border)">
                                        <p class="text-sm text-muted-foreground mb-1">Deskripsi</p>
                                        <p class="text-sm text-foreground">{{ $config->description }}</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="flex gap-2 pt-4" style="border-top:1px solid var(--color-border)">
                                <a href="{{ route('admin.loyalty.configurations.edit', $config) }}" class="admin-btn" style="flex:1; justify-content:center; background:var(--color-accent); color:var(--color-primary)">
                                    Edit
                                </a>
                                <form action="{{ route('admin.loyalty.configurations.toggle-status', $config) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="admin-btn w-full justify-center" style="background:#fef3c7; color:#d97706;">
                                        {{ $config->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.loyalty.configurations.destroy', $config) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin ingin menghapus?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn w-full justify-center" style="background:var(--admin-alert-error-bg); color:var(--color-destructive)">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div style="grid-column: 1 / -1;" class="admin-form-card text-center py-12">
                            <svg class="mx-auto h-12 w-12 mb-3" style="color:var(--color-muted-foreground)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="font-medium mb-3" style="color:var(--color-foreground)">Belum ada konfigurasi poin</p>
                            <a href="{{ route('admin.loyalty.configurations.create') }}" class="admin-btn admin-btn-primary">
                                Buat Konfigurasi Pertama
                            </a>
                        </div>
                    @endforelse
                </div>

                @if ($configurations->hasPages())
                    <div class="admin-pagination mt-4">
                        {{ $configurations->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
