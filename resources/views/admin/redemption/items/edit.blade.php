@extends('layouts.app')

@section('title', 'Edit Item Penukar')

@section('content')
    <div class="admin-page">
        <div class="admin-container">
            <div class="admin-page-header">
                <div>
                    <h1 class="admin-page-title">Edit Item Penukar</h1>
                    <p class="admin-page-subtitle">Ubah informasi item penukaran poin.</p>
                </div>
                <a href="{{ route('admin.redemption.items.index') }}" class="admin-back-btn">
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
            <form action="{{ route('admin.redemption.items.update', $item) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="admin-form-grid">
                    <label class="block">
                        <span class="admin-form-label">Nama Item <span style="color:var(--color-destructive)">*</span></span>
                        <input type="text" name="name" value="{{ old('name', $item->name) }}" required class="admin-input">
                        @error('name')<p class="text-sm mt-1" style="color:var(--color-destructive)">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="admin-form-label">Harga Poin <span style="color:var(--color-destructive)">*</span></span>
                        <input type="number" name="points_cost" value="{{ old('points_cost', $item->points_cost) }}" required class="admin-input">
                        @error('points_cost')<p class="text-sm mt-1" style="color:var(--color-destructive)">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="admin-form-label">Stok <span style="color:var(--color-destructive)">*</span></span>
                        <input type="number" name="stock" value="{{ old('stock', $item->stock) }}" required class="admin-input">
                        @error('stock')<p class="text-sm mt-1" style="color:var(--color-destructive)">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="admin-form-label">Max Redemption Per User <span style="color:var(--color-destructive)">*</span></span>
                        <input type="number" name="max_redemption_per_user" value="{{ old('max_redemption_per_user', $item->max_redemption_per_user) }}" required class="admin-input">
                        @error('max_redemption_per_user')<p class="text-sm mt-1" style="color:var(--color-destructive)">{{ $message }}</p>@enderror
                    </label>

                    <label class="block" style="grid-column: 1 / -1;">
                        <span class="admin-form-label">Deskripsi</span>
                        <textarea name="description" rows="4" class="admin-input">{{ old('description', $item->description) }}</textarea>
                        @error('description')<p class="text-sm mt-1" style="color:var(--color-destructive)">{{ $message }}</p>@enderror
                    </label>

                    <div style="grid-column: 1 / -1;">
                        <span class="admin-form-label">Gambar Item</span>
                        <div class="flex flex-col gap-4">
                            @if ($item->image_url)
                                <div>
                                    <p class="text-sm text-muted-foreground mb-2">Gambar saat ini:</p>
                                    <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->name }}" class="max-h-48 rounded" style="border:1px solid var(--color-border)">
                                </div>
                            @endif
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:bg-gray-50 transition-colors" onclick="document.getElementById('image').click()">
                                <input type="file" name="image" id="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-12l-3.172-3.172a4 4 0 00-5.656 0L28 28M9 20l3.172-3.172a4 4 0 015.656 0L28 28" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">Klik untuk ganti gambar (Max 10MB)</p>
                                <img id="imagePreview" src="" alt="Preview" class="hidden mt-4 max-h-48 mx-auto rounded">
                            </div>
                        </div>
                        @error('image')<p class="text-sm mt-1" style="color:var(--color-destructive)">{{ $message }}</p>@enderror
                    </div>

                    <label class="inline-flex items-center gap-2" style="grid-column: 1 / -1;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $item->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="text-sm font-medium text-foreground">Aktifkan item ini</span>
                    </label>
                </div>

                <div class="admin-page-actions">
                    <button type="submit" class="admin-btn admin-btn-primary">Update</button>
                    <a href="{{ route('admin.redemption.items.index') }}" class="admin-btn" style="background:var(--color-accent); color:var(--color-primary); margin-left:0.5rem;">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
