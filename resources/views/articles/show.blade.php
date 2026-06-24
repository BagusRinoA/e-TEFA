@extends('layouts.app')

@section('title', $article->title . ' - Articles')

@section('content')
    <div class="page-section">
        <div class="article-show-container">
            @include('components.back-button', ['href' => route('articles.index'), 'label' => 'Back to Articles'])

            <div style="margin-bottom:2rem">
                <h1 class="article-show-title">{{ $article->title }}</h1>
                <div class="article-show-meta">
                    @if ($article->author)
                        <span>By <strong>{{ $article->author }}</strong></span>
                    @endif
                    <span>{{ optional($article->published_at)->format('d M Y') ?? 'Not published' }}</span>
                    @if ($article->category)
                        <span class="article-category-badge">{{ $article->category }}</span>
                    @endif
                    @if ($article->read_time)
                        <span>{{ $article->read_time }}</span>
                    @endif
                </div>
            </div>

            @if ($article->image)
                <div class="article-show-hero">
                    <img src="{{ Storage::url($article->image) }}" alt="{{ $article->title }}">
                </div>
            @else
                <div class="article-show-hero">
                    <img src="https://via.placeholder.com/800x400?text=No+Image" alt="No image">
                </div>
            @endif

            <div class="content-card" style="margin-bottom:2rem">
                <p class="article-show-excerpt">{{ $article->excerpt }}</p>
                <div class="article-show-body">{{ $article->content }}</div>
            </div>

            @if (session('success'))
                <div id="articleSaveToast" style="position:fixed;top:1.5rem;left:50%;transform:translateX(-50%) translateY(-1rem);z-index:50;max-width:24rem;width:100%;border-radius:1.5rem;border:1px solid #bbf7d0;background:rgba(255,255,255,0.97);box-shadow:0 25px 50px rgba(0,0,0,0.15);backdrop-filter:blur(4px);opacity:0;transition:all 0.3s">
                    <div style="display:flex;gap:0.75rem;padding:1rem">
                        <div style="width:2.75rem;height:2.75rem;border-radius:0.75rem;background:#16a34a;color:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5" /></svg>
                        </div>
                        <div style="flex:1">
                            <p style="font-size:0.875rem;font-weight:600;color:var(--color-foreground)">Article saved</p>
                            <p style="font-size:0.875rem;color:var(--color-muted-foreground);margin-top:0.25rem">{{ session('success') }}</p>
                        </div>
                        <button id="articleSaveToastClose" style="color:var(--color-muted-foreground);background:none;border:none;cursor:pointer;font-size:1.25rem">&times;</button>
                    </div>
                </div>
            @endif

            @auth
                <div class="content-card">
                    <form action="{{ route('articles.save-toggle', $article->id) }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="article-show-save-btn article-show-save-btn--saved">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h6a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                            </svg>
                            Save Article
                        </button>
                    </form>
                </div>
            @else
                <div class="content-card" style="text-align:center">
                    <p style="color:var(--color-muted-foreground);margin-bottom:1rem">Login to save this article to your collection.</p>
                    <a href="{{ route('login') }}" class="btn-primary">Login</a>
                </div>
            @endauth
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toast = document.getElementById('articleSaveToast');
                if (!toast) return;
                setTimeout(() => { toast.style.opacity = '1'; toast.style.transform = 'translateX(-50%) translateY(0)'; }, 50);
                const hide = () => { toast.style.opacity = '0'; toast.style.transform = 'translateX(-50%) translateY(-1rem)'; };
                document.getElementById('articleSaveToastClose')?.addEventListener('click', hide);
                setTimeout(hide, 5000);
            });
        </script>
    @endpush
@endsection
