@extends('layouts.app')

@section('title', 'Community Forum - e-TEFA Kompeni')

@section('content')
    <div class="page-section">
        <div class="page-container">
            <div class="page-header-row">
                <div>
                    <h1 class="page-title">Community Forum</h1>
                    <p class="page-subtitle-text">Ask questions and share knowledge with fellow growers</p>
                </div>
                <a href="{{ route('forum.create') }}" class="btn-primary">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Ask Question
                </a>
            </div>

            <div class="filter-box">
                <form method="GET" action="{{ route('forum.index') }}">
                    <div class="filter-search">
                        <svg class="filter-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" name="search" placeholder="Search questions..."
                            value="{{ request('search') }}" class="filter-input">
                    </div>
                    <div style="margin-top:1rem">
                        <p class="filter-tag-label">Filter by topic:</p>
                        <div class="filter-tags">
                            <a href="{{ route('forum.index') }}"
                                class="filter-tag {{ !request('tag') ? 'is-active' : '' }}">All</a>
                            @foreach ($allTags as $tag)
                                <a href="{{ route('forum.index', ['tag' => $tag]) }}"
                                    class="filter-tag {{ request('tag') == $tag ? 'is-active' : '' }}">{{ $tag }}</a>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>

            <div class="dash-list">
                @forelse($questions as $question)
                    <a href="{{ route('forum.show', $question->id) }}" class="forum-card">
                        <div class="forum-card-inner">
                            <div class="forum-avatar-wrap">
                                @if ($question->user && $question->user->profile_photo)
                                    <div class="forum-avatar-img">
                                        <img src="{{ Storage::url($question->user->profile_photo) }}" alt="{{ $question->author }}">
                                    </div>
                                @else
                                    <div class="forum-avatar-initials">
                                        {{ strtoupper(substr($question->author, 0, 2)) }}
                                    </div>
                                @endif
                            </div>

                            <div class="forum-card-body">
                                <div class="forum-card-title-row">
                                    <h3 class="forum-card-title">{{ $question->title }}</h3>
                                    <span class="forum-card-badge">Forum Post</span>
                                </div>

                                <p class="forum-card-excerpt">{{ $question->content }}</p>

                                @if ($question->image)
                                    <div class="forum-card-thumb">
                                        <img src="{{ Storage::url($question->image) }}" alt="{{ $question->title }}">
                                    </div>
                                @endif

                                <div class="forum-card-meta">
                                    <span class="forum-meta-author">
                                        <span class="forum-meta-author-name">{{ $question->author }}</span>
                                    </span>
                                    <span>•</span>
                                    <span>{{ $question->created_at->format('d M Y') }}</span>
                                </div>

                                <div class="forum-card-stats">
                                    <div class="forum-stat-pill forum-stat-pill--primary">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                        </svg>
                                        {{ $question->upvotes }} Upvotes
                                    </div>
                                    <div class="forum-stat-pill forum-stat-pill--neutral">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                        {{ count($question->replies) }} replies
                                    </div>
                                </div>

                                @if ($question->tags)
                                    <div class="forum-tags">
                                        @foreach ($question->tags as $tag)
                                            <span class="forum-tag">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="empty-state">
                        <p style="color:var(--color-muted-foreground);font-size:1.125rem">No questions found</p>
                    </div>
                @endforelse
            </div>

            @if ($questions->hasPages())
                <div class="page-pagination">{{ $questions->links() }}</div>
            @endif
        </div>
    </div>
@endsection
