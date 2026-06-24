@extends('layouts.app')

@section('title', 'My Forum Replies - Dashboard')

@section('content')
    <div class="user-dash-page">
        <div class="user-dash-container">
            <div class="user-dash-layout">
                @include('dashboard.partials.sidebar')

                <div class="user-dash-main">
                    <div>
                        <h1 class="user-dash-title">My Forum Replies</h1>
                        <p class="user-dash-subtitle">Replies you've posted in the forum</p>
                    </div>

                    <div class="user-dash-tabs">
                        <a href="{{ route('dashboard') }}" class="user-dash-tab">Overview</a>
                        <a href="{{ route('dashboard.saved-articles') }}" class="user-dash-tab">Saved Articles</a>
                        <a href="{{ route('dashboard.my-forum-questions') }}" class="user-dash-tab">My Questions</a>
                        <a href="{{ route('dashboard.my-forum-replies') }}" class="user-dash-tab is-active">My Replies</a>
                    </div>

                    @if ($replies->count())
                        <div class="dash-list">
                            @foreach ($replies as $reply)
                                <div class="dash-item-card">
                                    <div class="dash-item-row">
                                        <div class="dash-item-body">
                                            <a href="{{ route('forum.show', $reply->question) }}" class="dash-item-title">
                                                Re: {{ $reply->question->title }}
                                            </a>
                                            <p class="dash-item-meta">
                                                Posted on {{ $reply->created_at->format('d M Y \a\t H:i') }}
                                            </p>
                                            <div class="dash-item-reply-box">
                                                <p>{{ $reply->content }}</p>
                                            </div>
                                        </div>
                                        <div class="dash-item-actions">
                                            <a href="{{ route('forum.show', $reply->question) }}" class="btn-primary">View Question</a>
                                            <form action="{{ route('forum.reply.delete', $reply) }}" method="POST"
                                                onsubmit="return confirm('Delete this reply?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="page-pagination">{{ $replies->links() }}</div>
                    @else
                        <div class="content-card--large empty-state">
                            <div class="empty-state-icon-circle">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" />
                                    <path d="M9 10h.01M15 10h.01" />
                                </svg>
                            </div>
                            <h2 class="empty-state-title">No replies posted yet</h2>
                            <p class="empty-state-desc">Join the conversation by replying to questions in the forum.</p>
                            <a href="{{ route('forum.index') }}" class="btn-pill">Browse Forum</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
