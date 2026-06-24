@extends('layouts.admin')

@section('title', 'Manage Articles - Admin')

@section('admin-content')
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Manage Articles</h1>
            <p class="admin-page-subtitle">Review and manage existing articles.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="admin-back-btn">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Dashboard
        </a>
    </div>

    @if (session('success'))
        <div class="admin-alert admin-alert-success">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="admin-table-card">
        <div class="admin-table-card-header">
            <span class="admin-table-card-title">All Articles</span>
            <span class="admin-page-subtitle">{{ $articles->total() }} total articles</span>
        </div>

        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Published</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                        <tr>
                            <td>
                                <span class="font-medium">{{ Str::limit($article->title, 50) }}</span>
                            </td>
                            <td class="td-muted">{{ $article->category }}</td>
                            <td class="td-muted">{{ $article->author ?? 'Admin' }}</td>
                            <td class="td-muted">{{ optional($article->published_at)->format('d M Y') }}</td>
                            <td>
                                <div class="admin-action-group">
                                    <a href="{{ route('articles.show', $article->id) }}" class="admin-action-link">
                                        <svg width="13" height="13" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Preview
                                    </a>
                                    <form method="POST" action="{{ route('admin.articles.destroy', $article->id) }}"
                                        class="admin-form-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this article? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-action-link admin-action-link--danger">
                                            <svg width="13" height="13" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="td-empty">
                                <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    class="admin-empty-icon">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                No articles found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="admin-pagination">
            {{ $articles->links() }}
        </div>
    </div>

    <div class="admin-page-actions">
        <a href="{{ route('admin.articles.create') }}" class="admin-btn admin-btn-primary">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add New Article
        </a>
    </div>
@endsection
