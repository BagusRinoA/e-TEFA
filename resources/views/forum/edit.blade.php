@extends('layouts.app')

@section('title', 'Edit Question - Forum')

@section('content')
    <div class="page-section">
        <div class="page-container" style="max-width:48rem">
            <h1 class="page-title">Edit Question</h1>
            <p class="page-subtitle-text" style="margin-bottom:2rem">Update your forum question and details.</p>

            @if ($errors->any())
                <div class="auth-alert-error" style="margin-bottom:1.5rem">
                    <ul style="list-style:disc;padding-left:1.25rem;font-size:0.875rem">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="forum-form-card">
                <form action="{{ route('forum.update', $question) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="forum-form-field">
                        <label for="title" class="forum-form-label">Title</label>
                        <input type="text" id="title" name="title"
                            value="{{ old('title', $question->title) }}"
                            class="forum-form-input" required>
                    </div>

                    <div class="forum-form-field">
                        <label for="category" class="forum-form-label">Category</label>
                        <select id="category" name="category" class="forum-form-select" required>
                            <option value="">Select category</option>
                            @foreach(['general','hydroponics','techniques','products'] as $cat)
                                <option value="{{ $cat }}" {{ old('category', $question->category) === $cat ? 'selected' : '' }}>
                                    {{ ucfirst($cat) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="forum-form-field">
                        <label for="description" class="forum-form-label">Description</label>
                        <textarea id="description" name="description" rows="6"
                            class="forum-textarea" required>{{ old('description', $question->content) }}</textarea>
                    </div>

                    <div class="forum-form-field">
                        <label for="tags" class="forum-form-label">Tags (comma separated)</label>
                        <input type="text" id="tags" name="tags"
                            value="{{ old('tags', $question->tags ? implode(', ', $question->tags) : '') }}"
                            placeholder="e.g. beginner, plants, setup"
                            class="forum-form-input">
                    </div>

                    <div class="forum-form-actions">
                        <button type="submit" class="btn-primary">Save Changes</button>
                        <a href="{{ route('forum.show', $question) }}" class="btn-outline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
