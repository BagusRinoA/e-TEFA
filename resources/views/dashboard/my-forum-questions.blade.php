@extends('layouts.app')

@section('title', 'My Forum Questions - Dashboard')

@section('content')
    <div class="user-dash-page">
        <div class="user-dash-container">
            <div class="user-dash-layout">
                @include('dashboard.partials.sidebar')

                <div class="user-dash-main">
                    <div>
                        <h1 class="user-dash-title">My Forum Questions</h1>
                        <p class="user-dash-subtitle">Questions you've posted in the forum</p>
                    </div>

                    <div class="user-dash-tabs">
                        <a href="{{ route('dashboard') }}" class="user-dash-tab">Overview</a>
                        <a href="{{ route('dashboard.saved-articles') }}" class="user-dash-tab">Saved Articles</a>
                        <a href="{{ route('dashboard.my-forum-questions') }}" class="user-dash-tab is-active">My Questions</a>
                        <a href="{{ route('dashboard.my-forum-replies') }}" class="user-dash-tab">My Replies</a>
                    </div>

                    @if ($questions->count())
                        <div class="dash-list">
                            @foreach ($questions as $question)
                                <div class="dash-item-card">
                                    <div class="dash-item-row">
                                        <div class="dash-item-body">
                                            <a href="{{ route('forum.show', $question) }}" class="dash-item-title">
                                                {{ $question->title }}
                                            </a>
                                            <div class="dash-item-meta">
                                                <span class="dash-item-category">{{ $question->category }}</span>
                                                <span>{{ $question->created_at->format('d M Y') }}</span>
                                                <span>{{ $question->replies_count }} replies</span>
                                            </div>
                                            <p class="dash-item-excerpt">{{ Str::limit($question->content, 150) }}</p>
                                            @if ($question->tags && count($question->tags) > 0)
                                                <div class="dash-item-tags">
                                                    @foreach ($question->tags as $tag)
                                                        <span class="dash-item-tag">{{ $tag }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        <div class="dash-item-actions">
                                            <a href="{{ route('forum.show', $question) }}" class="btn-primary">View</a>
                                            <a href="{{ route('forum.edit', $question) }}" class="btn-outline">Edit</a>
                                            <form action="{{ route('forum.destroy', $question) }}" method="POST"
                                                onsubmit="return confirm('Delete this question?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="page-pagination">{{ $questions->links() }}</div>
                    @else
                        <div class="content-card--large empty-state">
                            <div class="empty-state-icon-circle">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" />
                                </svg>
                            </div>
                            <h2 class="empty-state-title">No questions posted yet</h2>
                            <p class="empty-state-desc">Share your questions with the community and get helpful answers.</p>
                            <a href="{{ route('forum.create') }}" class="btn-pill">Ask a Question</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
