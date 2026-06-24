@extends('layouts.app')

@section('title', 'Add Product - Admin')

@section('content')
    <div class="admin-page">
        <div class="admin-container">
            <div class="admin-page-header">
                <div>
                    <h1 class="admin-page-title">Add Product</h1>
                    <p class="admin-page-subtitle">Create a new product for the store.</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="admin-back-btn">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Products
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
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="admin-form-grid">
                        <label class="block">
                            <span class="admin-form-label">Name</span>
                            <input type="text" name="name" value="{{ old('name') }}" class="admin-input" required>
                        </label>
                        <label class="block">
                            <span class="admin-form-label">Category</span>
                            <input type="text" name="category" value="{{ old('category') }}" class="admin-input" required>
                        </label>
                        <label class="block">
                            <span class="admin-form-label">Price</span>
                            <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="admin-input" required>
                        </label>
                        <label class="block">
                            <span class="admin-form-label">Stock</span>
                            <input type="number" name="stock" value="{{ old('stock') }}" class="admin-input" required>
                        </label>
                        <label class="block" style="grid-column: 1 / -1;">
                            <span class="admin-form-label">Image</span>
                            <div class="mt-2 flex items-center gap-3">
                                <label for="image_input" class="inline-flex items-center justify-center cursor-pointer rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 px-6 py-8 text-sm font-medium text-gray-600 hover:bg-gray-100" style="transition:all 0.2s;">
                                    <span id="file_label">Choose File</span>
                                </label>
                                <span id="file_name" class="text-sm text-muted-foreground">No file chosen</span>
                            </div>
                            <input type="file" id="image_input" name="image" accept="image/jpeg,image/png,image/jpg,image/webp,image/gif" class="hidden">
                            <p class="mt-2 text-xs text-muted-foreground">Format: JPG, PNG, WebP. Maks. 10 MB.</p>
                        </label>
                        <script>
                            document.getElementById('image_input').addEventListener('change', function() {
                                const fileName = this.files[0]?.name || 'No file chosen';
                                document.getElementById('file_name').textContent = fileName;
                                document.getElementById('file_label').textContent = 'Change File';
                            });
                        </script>
                        <label class="block" style="grid-column: 1 / -1;">
                            <span class="admin-form-label">Description</span>
                            <textarea name="description" rows="5" class="admin-input" required>{{ old('description') }}</textarea>
                        </label>
                        <label class="inline-flex items-center gap-2" style="grid-column: 1 / -1;">
                            <input type="checkbox" name="featured" value="1" {{ old('featured') ? 'checked' : '' }} class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="text-sm text-muted-foreground">Mark as featured product</span>
                        </label>
                    </div>

                    <div class="admin-page-actions">
                        <button type="submit" class="admin-btn admin-btn-primary">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
