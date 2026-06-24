@extends('layouts.app')

@section('title', 'Learning Articles - e-TEFA Kompeni')

@section('content')
    <div class="page-section">
        <div class="page-container">
            <div style="margin-bottom:2rem">
                <h1 class="page-title">Learning Articles</h1>
                <p class="page-subtitle-text">Expand your knowledge with expert guides and tutorials</p>
            </div>

            <div class="filter-box">
                <form method="GET" action="{{ route('articles.index') }}">
                    <div class="filter-search">
                        <svg class="filter-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" name="search" placeholder="Search articles..."
                            value="{{ request('search') }}" class="filter-input">
                    </div>
                    <div class="filter-tags" style="margin-top:1rem">
                        <a href="{{ route('articles.index') }}"
                            class="filter-tag {{ !request('category') ? 'is-active' : '' }}">All</a>
                        @foreach ($categories as $category)
                            <a href="{{ route('articles.index', ['category' => $category]) }}"
                                class="filter-tag {{ request('category') == $category ? 'is-active' : '' }}">{{ $category }}</a>
                        @endforeach
                    </div>
                </form>
            </div>

            <div class="article-grid">
                @forelse($articles as $article)
                    <a href="{{ route('articles.show', $article->id) }}" class="article-card">
                        <div class="article-card-thumb">
                            <img src="{{ $article->image ? Storage::url($article->image) : 'https://via.placeholder.com/300x200?text=No+Image' }}"
                                alt="{{ $article->title }}">
                            @auth
                                <div class="article-card-save">
                                    <form method="POST" action="{{ route('articles.save-toggle', $article->id) }}">
                                        @csrf
                                        <button type="submit"
                                            class="article-save-btn {{ in_array($article->id, $savedArticles ?? []) ? 'article-save-btn--saved' : 'article-save-btn--unsaved' }}">
                                            <svg class="h-3 w-3"
                                                fill="{{ in_array($article->id, $savedArticles ?? []) ? 'currentColor' : 'none' }}"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                            </svg>
                                            {{ in_array($article->id, $savedArticles ?? []) ? 'Saved' : 'Save' }}
                                        </button>
                                    </form>
                                </div>
                            @endauth
                        </div>
                        <div class="article-card-body">
                            <div class="article-card-meta">
                                <span class="article-category-badge">{{ $article->category }}</span>
                                <div class="article-read-time">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ $article->read_time }}</span>
                                </div>
                            </div>
                            <h3 class="article-card-title">{{ $article->title }}</h3>
                            <p class="article-card-excerpt">{{ $article->excerpt }}</p>
                            <div class="article-card-footer">
                                <div class="article-card-author">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>{{ $article->author }}</span>
                                </div>
                                <span>{{ $article->published_at }}</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div style="grid-column:1/-1">
                        <div class="empty-state">
                            <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:4rem;height:4rem;display:block;margin:0 auto 1rem">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <p style="color:var(--color-muted-foreground);font-size:1.125rem">No articles found</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if ($articles->hasPages())
                <div class="page-pagination">{{ $articles->links() }}</div>
            @endif
        </div>
    </div>
@endsection
