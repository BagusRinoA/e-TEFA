@extends('layouts.app')

@section('title', 'Tambah Konfigurasi Point Earning')

@section('content')
    <div class="admin-page">
        <div class="admin-container">
            <div class="admin-page-header">
                <div>
                    <h1 class="admin-page-title">Tambah Konfigurasi Point</h1>
                    <p class="admin-page-subtitle">Buat konfigurasi penghasilan poin baru.</p>
                </div>
                <a href="{{ route('admin.loyalty.configurations.index') }}" class="admin-back-btn">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>

        @if ($errors->any())
            <div class="admin-alert admin-alert-error">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="admin-form-card">
            <form action="{{ route('admin.loyalty.configurations.store') }}" method="POST">
                @csrf

                <div class="admin-form-grid">
                    <label class="block">
                        <span class="admin-form-label">Minimum Nominal Pembelanjaan (Rp)</span>
                        <input type="number" name="min_purchase_amount" step="1000" min="0" value="{{ old('min_purchase_amount') }}" required class="admin-input" placeholder="0">
                        @error('min_purchase_amount')<p class="text-sm mt-1" style="color:var(--color-destructive)">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="admin-form-label">Maximum Nominal Pembelanjaan (Rp) <span style="font-weight:normal; color:var(--color-muted-foreground);">(Kosongkan untuk unlimited)</span></span>
                        <input type="number" name="max_purchase_amount" step="1000" min="0" value="{{ old('max_purchase_amount') }}" class="admin-input" placeholder="Kosongkan untuk unlimited">
                        @error('max_purchase_amount')<p class="text-sm mt-1" style="color:var(--color-destructive)">{{ $message }}</p>@enderror
                    </label>

                    <label class="block" style="grid-column: 1 / -1;">
                        <span class="admin-form-label">Poin yang Diperoleh <span style="color:var(--color-destructive)">*</span></span>
                        <input type="number" name="points_earned" min="0" value="{{ old('points_earned') }}" required class="admin-input" placeholder="Contoh: 10">
                        @error('points_earned')<p class="text-sm mt-1" style="color:var(--color-destructive)">{{ $message }}</p>@enderror
                    </label>

                    <label class="block" style="grid-column: 1 / -1;">
                        <span class="admin-form-label">Deskripsi</span>
                        <textarea name="description" rows="3" class="admin-input" placeholder="Contoh: Pembelian hingga Rp 100.000">{{ old('description') }}</textarea>
                        @error('description')<p class="text-sm mt-1" style="color:var(--color-destructive)">{{ $message }}</p>@enderror
                    </label>

                    <label class="inline-flex items-center gap-2" style="grid-column: 1 / -1;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="text-sm font-medium text-foreground">Aktifkan konfigurasi ini</span>
                    </label>
                </div>

                <div class="admin-page-actions">
                    <button type="submit" class="admin-btn admin-btn-primary">Simpan</button>
                    <a href="{{ route('admin.loyalty.configurations.index') }}" class="admin-btn" style="background:var(--color-accent); color:var(--color-primary); margin-left:0.5rem;">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
