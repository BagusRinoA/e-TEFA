@extends('layouts.admin')

@section('title', 'Manage Forum - Admin')

@section('admin-content')
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Manage Forum</h1>
            <p class="admin-page-subtitle">Manage forum questions and replies from users.</p>
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

    <div class="admin-tabs">
        <button class="admin-tab is-active" data-tab="questions">Forum Questions</button>
        <button class="admin-tab" data-tab="replies">Forum Replies</button>
    </div>

    <div id="questions" class="admin-tab-content">
        <div class="admin-table-card">
            <div class="admin-table-card-header">
                <span class="admin-table-card-title">Forum Questions</span>
                <span class="admin-page-subtitle">{{ $questions->total() }} total questions</span>
            </div>

            <div class="admin-table-scroll">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>Replies</th>
                            <th>Upvotes</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($questions as $question)
                            <tr>
                                <td>
                                    <a href="{{ route('forum.show', $question->id) }}" class="admin-text-link">
                                        {{ Str::limit($question->title, 40) }}
                                    </a>
                                </td>
                                <td>
                                    <span class="status-badge status-active">{{ $question->category }}</span>
                                </td>
                                <td class="td-muted">
                                    {{ $question->author ?? ($question->user?->name ?? 'Unknown') }}</td>
                                <td>
                                    <span class="status-badge status-active">
                                        {{ $question->replies_count ?? $question->replies->count() }}
                                    </span>
                                </td>
                                <td class="td-amount">{{ $question->upvotes ?? 0 }}</td>
                                <td class="td-muted">{{ $question->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="admin-action-group">
                                        <a href="{{ route('forum.show', $question->id) }}" class="admin-action-link">
                                            <svg width="13" height="13" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View
                                        </a>
                                        <form
                                            action="{{ route('admin.forum.destroy', ['type' => 'question', 'id' => $question->id]) }}"
                                            method="POST" class="admin-form-inline"
                                            onsubmit="return confirm('Delete this question and all its replies?');">
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
                                <td colspan="7" class="td-empty">
                                    <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        class="admin-empty-icon">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    No forum questions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="admin-pagination">
                {{ $questions->links() }}
            </div>
        </div>
    </div>

    <div id="replies" class="admin-tab-content is-hidden">
        <div class="admin-table-card">
            <div class="admin-table-card-header">
                <span class="admin-table-card-title">Forum Replies</span>
                <span class="admin-page-subtitle">{{ $replies->total() }} total replies</span>
            </div>

            <div class="admin-table-scroll">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Reply</th>
                            <th>Question</th>
                            <th>Author</th>
                            <th>Upvotes</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($replies as $reply)
                            <tr>
                                <td class="td-muted">{{ Str::limit($reply->content, 50) }}</td>
                                <td>
                                    <a href="{{ route('forum.show', $reply->question_id) }}" class="admin-text-link">
                                        {{ Str::limit($reply->question?->title, 35) ?? 'Unknown' }}
                                    </a>
                                </td>
                                <td class="td-muted">
                                    {{ $reply->author ?? ($reply->user?->name ?? 'Unknown') }}
                                </td>
                                <td class="td-amount">{{ $reply->upvotes ?? 0 }}</td>
                                <td class="td-muted">{{ $reply->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="admin-action-group">
                                        <a href="{{ route('forum.show', $reply->question_id) }}"
                                            class="admin-action-link">
                                            <svg width="13" height="13" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View
                                        </a>
                                        <form
                                            action="{{ route('admin.forum.destroy', ['type' => 'reply', 'id' => $reply->id]) }}"
                                            method="POST" class="admin-form-inline"
                                            onsubmit="return confirm('Delete this reply?');">
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
                                <td colspan="6" class="td-empty">
                                    <svg width="36" height="36" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" class="admin-empty-icon">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    No forum replies found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="admin-pagination">
                {{ $replies->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabBtns = document.querySelectorAll('.admin-tab');
            const tabContents = document.querySelectorAll('.admin-tab-content');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const tabName = this.getAttribute('data-tab');

                    tabContents.forEach(content => content.classList.add('is-hidden'));
                    tabBtns.forEach(b => b.classList.remove('is-active'));

                    document.getElementById(tabName).classList.remove('is-hidden');
                    this.classList.add('is-active');
                });
            });
        });
    </script>
@endpush
