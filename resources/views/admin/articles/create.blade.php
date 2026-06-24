@extends('layouts.admin')

@section('title', 'Add Article - Admin')

@section('admin-content')
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Add Article</h1>
            <p class="admin-page-subtitle">Use this form to create a new article for the blog section.</p>
        </div>
        <a href="{{ route('admin.articles.index') }}" class="admin-back-btn">
            Back to Articles
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
        <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="admin-form-grid">
                <label>
                    <span class="admin-form-label">Title</span>
                    <input type="text" name="title" value="{{ old('title') }}" class="admin-input" required>
                </label>

                <label>
                    <span class="admin-form-label">Category</span>
                    <input type="text" name="category" value="{{ old('category') }}" class="admin-input">
                </label>

                <label>
                    <span class="admin-form-label">Author</span>
                    <input type="text" name="author" value="{{ old('author') }}" class="admin-input">
                </label>

                <label>
                    <span class="admin-form-label">Read Time</span>
                    <input type="text" name="read_time" value="{{ old('read_time') }}" class="admin-input"
                        placeholder="e.g. 5 min">
                </label>

                <label class="admin-form-col-span-2">
                    <span class="admin-form-label">Excerpt</span>
                    <textarea name="excerpt" rows="4" class="admin-textarea" required>{{ old('excerpt') }}</textarea>
                </label>

                <label class="admin-form-col-span-2">
                    <span class="admin-form-label">Content</span>
                    <textarea name="content" rows="8" class="admin-textarea" required>{{ old('content') }}</textarea>
                </label>

                <label class="admin-form-col-span-2">
                    <span class="admin-form-label">Image</span>
                    <input type="file" name="image" accept="image/*" class="admin-input">
                    <p class="admin-page-subtitle admin-form-hint">Optional. Supported formats: JPG, PNG, WebP.</p>
                </label>

                <label>
                    <span class="admin-form-label">Published At</span>
                    <input type="date" name="published_at" value="{{ old('published_at') }}" class="admin-input">
                </label>
            </div>

            <div class="admin-form-actions">
                <p class="admin-page-subtitle">Pastikan semua data artikel sudah benar sebelum menyimpan.</p>
                <button type="submit" class="admin-btn admin-btn-primary">Save Article</button>
            </div>
        </form>
    </div>
@endsection
