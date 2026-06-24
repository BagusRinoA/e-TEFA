@extends('layouts.app')

@section('title', $question->title . ' - Forum')

@section('content')
    <div class="page-section">
        <div class="forum-show-container">
            @include('components.back-button', ['href' => route('forum.index'), 'label' => 'Back to Forum'])

            {{-- Question header --}}
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;gap:1rem">
                <div style="display:flex;align-items:flex-start;gap:1rem;flex:1">
                    <div class="forum-reply-avatar" style="width:3rem;height:3rem;font-size:1rem">
                        @if ($question->user && $question->user->profile_photo)
                            <img src="{{ Storage::url($question->user->profile_photo) }}" alt="{{ $question->author }}">
                        @else
                            {{ strtoupper(substr($question->author, 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <h1 class="forum-show-title">{{ $question->title }}</h1>
                        <div class="forum-show-meta">
                            <span>By <strong>{{ $question->author }}</strong></span>
                            <span>{{ $question->created_at->format('d M Y') }}</span>
                            <span class="forum-card-badge">{{ $question->category }}</span>
                        </div>
                    </div>
                </div>
                @auth
                    @if (Auth::id() === $question->user_id)
                        <div class="forum-show-actions">
                            <a href="{{ route('forum.edit', $question) }}" class="btn-outline">Edit</a>
                            <form action="{{ route('forum.destroy', $question) }}" method="POST"
                                onsubmit="return confirm('Delete this question?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger" style="width:auto">Delete</button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>

            {{-- Question content --}}
            <div class="forum-show-question">
                <div style="display:grid;gap:1.5rem" class="forum-q-grid">
                    <div>
                        <p class="forum-show-body">{{ $question->content }}</p>
                        @if ($question->tags)
                            <div class="forum-tags" style="margin-top:1rem">
                                @foreach ($question->tags as $tag)
                                    <span class="forum-tag">#{{ $tag }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div style="margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid #e5e7eb">
                            @auth
                                <form action="{{ route('forum.upvote', $question->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    <button type="submit"
                                        class="forum-upvote-btn {{ $question->isUpvotedBy(Auth::id()) ? 'is-voted' : '' }}">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        </svg>
                                        Upvote ({{ $question->upvotes ?? 0 }})
                                    </button>
                                </form>
                            @else
                                <button disabled class="forum-upvote-btn" style="opacity:0.5;cursor:not-allowed">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                    Upvote ({{ $question->upvotes ?? 0 }})
                                </button>
                                <p style="font-size:0.75rem;color:var(--color-muted-foreground);margin-top:0.5rem">
                                    <a href="{{ route('login') }}" style="color:var(--color-primary)">Login</a> to upvote
                                </p>
                            @endauth
                        </div>
                    </div>

                    @if ($question->image)
                        <div class="forum-show-image">
                            <img src="{{ Storage::url($question->image) }}" alt="{{ $question->title }}">
                        </div>
                    @endif
                </div>
            </div>

            {{-- Replies --}}
            <div class="forum-replies-section">
                <h2 class="forum-replies-title">Replies ({{ $question->replies->count() }})</h2>

                @forelse($question->replies as $reply)
                    <div class="forum-reply-card">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start">
                            <div class="forum-reply-header">
                                <div class="forum-reply-avatar">
                                    @if ($reply->user && $reply->user->profile_photo)
                                        <img src="{{ Storage::url($reply->user->profile_photo) }}" alt="{{ $reply->author }}">
                                    @else
                                        {{ strtoupper(substr($reply->author, 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <p class="forum-reply-author">{{ $reply->author }}</p>
                                    <p class="forum-reply-time">{{ $reply->created_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            @auth
                                @if (Auth::id() === $reply->user_id)
                                    <form action="{{ route('forum.reply.delete', $reply) }}" method="POST"
                                        onsubmit="return confirm('Delete this reply?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="color:var(--color-destructive);font-size:0.875rem;background:none;border:none;cursor:pointer">Delete</button>
                                    </form>
                                @endif
                            @endauth
                        </div>

                        <p class="forum-reply-body">{{ $reply->content }}</p>

                        <div class="forum-reply-actions">
                            @auth
                                <form action="{{ route('forum.reply.upvote', $reply->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    <button type="submit"
                                        class="forum-upvote-btn {{ $reply->isUpvotedBy(Auth::id()) ? 'is-voted' : '' }}"
                                        style="font-size:0.875rem;padding:0.25rem 0.75rem">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        </svg>
                                        {{ $reply->upvotes ?? 0 }}
                                    </button>
                                </form>
                            @else
                                <button disabled class="forum-upvote-btn" style="opacity:0.5;cursor:not-allowed;font-size:0.875rem;padding:0.25rem 0.75rem">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                    {{ $reply->upvotes ?? 0 }}
                                </button>
                            @endauth
                        </div>
                    </div>
                @empty
                    <p style="text-align:center;color:var(--color-muted-foreground);padding:2rem 0">No replies yet. Be the first to reply!</p>
                @endforelse
            </div>

            {{-- Reply form --}}
            @auth
                <div class="forum-reply-form">
                    <h3 class="forum-reply-form-title">Post a Reply</h3>
                    @if ($errors->any())
                        <div class="auth-alert-error" style="margin-bottom:1rem">
                            <ul style="list-style:disc;padding-left:1.25rem;font-size:0.875rem">
                                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('forum.reply', $question) }}" method="POST">
                        @csrf
                        <div style="margin-bottom:1rem">
                            <label style="display:block;font-size:0.875rem;font-weight:500;margin-bottom:0.5rem">Your Reply</label>
                            <textarea name="content" rows="4" class="forum-textarea" required>{{ old('content') }}</textarea>
                        </div>
                        <button type="submit" class="btn-primary">Post Reply</button>
                    </form>
                </div>
            @else
                <div class="content-card" style="text-align:center">
                    <p style="color:var(--color-muted-foreground);margin-bottom:1rem">You must be logged in to reply.</p>
                    <a href="{{ route('login') }}" class="btn-primary">Login</a>
                </div>
            @endauth
        </div>
    </div>
    <style>
        @media (min-width:1024px) { .forum-q-grid { grid-template-columns: 1.5fr 1fr; } }
    </style>
@endsection
