@extends('layouts.app')

@section('title', 'Item Penukar Poin')

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
                        <h1 class="admin-page-title">Item Penukar Poin</h1>
                        <p class="admin-page-subtitle">Kelola hadiah yang dapat ditukar dengan poin pelanggan</p>
                    </div>
                    <a href="{{ route('admin.redemption.items.create') }}" class="admin-btn admin-btn-primary">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Item
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
                    @forelse($items as $item)
                        <div class="admin-stat-card" style="display:flex; flex-direction:column; padding:0; overflow:hidden;">
                            {{-- Image --}}
                            @if ($item->image_url)
                                <div class="relative h-48 overflow-hidden bg-gray-100 w-full" style="border-bottom:1px solid var(--color-border)">
                                    <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->name }}" class="w-full h-full object-cover transition-transform duration-300" style="object-fit:cover; width:100%; height:100%;">
                                </div>
                            @else
                                <div class="relative h-48 bg-gray-100 flex items-center justify-center w-full" style="border-bottom:1px solid var(--color-border)">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif

                            {{-- Content --}}
                            <div class="p-4 flex-1 flex flex-col">
                                {{-- Header with status --}}
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-bold text-foreground flex-1 line-clamp-2" style="font-size:1.1rem; line-height:1.4;">{{ $item->name }}</h3>
                                    @if ($item->is_active)
                                        <span class="status-badge status-active ml-2">Aktif</span>
                                    @else
                                        <span class="status-badge status-inactive ml-2">Nonaktif</span>
                                    @endif
                                </div>

                                {{-- Description --}}
                                <p class="text-sm text-muted-foreground mb-4 line-clamp-2">{{ $item->description ?? 'Tidak ada deskripsi' }}</p>

                                {{-- Info --}}
                                <div class="space-y-2 mb-4 pb-4 mt-auto" style="border-bottom:1px solid var(--color-border)">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-muted-foreground">Harga Poin:</span>
                                        <span class="font-bold text-primary">{{ $item->points_cost }} poin</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-muted-foreground">Stok:</span>
                                        <span class="font-semibold text-foreground">{{ $item->stock }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-muted-foreground">Max per User:</span>
                                        <span class="font-semibold text-foreground">{{ $item->max_redemption_per_user }}x</span>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.redemption.items.edit', $item) }}" class="admin-btn" style="flex:1; justify-content:center; background:var(--color-accent); color:var(--color-primary)">Edit</a>
                                    <form action="{{ route('admin.redemption.items.toggle-status', $item) }}" method="POST" class="flex-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="admin-btn w-full justify-center" style="background:#fef3c7; color:#d97706;">
                                            {{ $item->is_active ? 'Nonaktif' : 'Aktif' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.redemption.items.destroy', $item) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin ingin menghapus item ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-btn w-full justify-center" style="background:var(--admin-alert-error-bg); color:var(--color-destructive)">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="grid-column: 1 / -1;" class="admin-form-card text-center py-12">
                            <svg class="mx-auto h-12 w-12 mb-3" style="color:var(--color-muted-foreground)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="font-medium mb-3" style="color:var(--color-foreground)">Belum ada item penukar poin</p>
                            <a href="{{ route('admin.redemption.items.create') }}" class="admin-btn admin-btn-primary">
                                Buat Item Pertama
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if ($items->hasPages())
                    <div class="admin-pagination mt-4">
                        {{ $items->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
