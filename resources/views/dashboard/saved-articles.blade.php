@extends('layouts.app')

@section('title', 'Saved Articles - Dashboard')

@section('content')
    <div class="user-dash-page">
        <div class="user-dash-container">
            <div class="user-dash-layout">
                @include('dashboard.partials.sidebar')

                <div class="user-dash-main">
                    <div>
                        <h1 class="user-dash-title">Saved Articles</h1>
                        <p class="user-dash-subtitle">Your collection of favorite articles</p>
                    </div>

                    <div class="user-dash-tabs">
                        <a href="{{ route('dashboard') }}" class="user-dash-tab">Overview</a>
                        <a href="{{ route('dashboard.saved-articles') }}" class="user-dash-tab is-active">Saved Articles</a>
                        <a href="{{ route('dashboard.my-forum-questions') }}" class="user-dash-tab">My Questions</a>
                        <a href="{{ route('dashboard.my-forum-replies') }}" class="user-dash-tab">My Replies</a>
                    </div>

                    @if ($savedArticles->count())
                        <div class="dash-list">
                            @foreach ($savedArticles as $saved)
                                <div class="dash-item-card">
                                    <div class="dash-item-row">
                                        <div class="dash-item-body">
                                            <a href="{{ route('articles.show', $saved->article) }}" class="dash-item-title" style="font-size:1.5rem">
                                                {{ $saved->article->title }}
                                            </a>
                                            <p class="dash-item-meta">
                                                By <strong>{{ $saved->article->author ?? 'Admin' }}</strong> on
                                                {{ $saved->article->published_at->format('d M Y') }}
                                            </p>
                                            <p class="dash-item-excerpt">{{ $saved->article->excerpt }}</p>
                                            <div class="dash-item-tags" style="margin-top:1rem">
                                                <span class="dash-item-category">{{ $saved->article->category }}</span>
                                                <span style="font-size:0.875rem;color:var(--color-muted-foreground)">{{ $saved->article->read_time ?? 5 }} min read</span>
                                            </div>
                                        </div>
                                        <div class="dash-item-actions">
                                            <a href="{{ route('articles.show', $saved->article) }}" class="btn-primary">Read</a>
                                            <form action="{{ route('articles.save-toggle', $saved->article) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn-danger">Remove</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="page-pagination">{{ $savedArticles->links() }}</div>
                    @else
                        <div class="content-card--large empty-state">
                            <div class="empty-state-icon-circle">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h8l6 6v10a2 2 0 01-2 2z" />
                                    <path d="M17 21v-8H7v8" />
                                </svg>
                            </div>
                            <h2 class="empty-state-title">No saved articles yet</h2>
                            <p class="empty-state-desc">Save articles as you browse and they will appear here for quick access.</p>
                            <a href="{{ route('articles.index') }}" class="btn-pill">Browse Articles</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
