@extends('layouts.app')

@section('title', 'Forum Question - Admin')

@section('content')
    <div class="admin-page">
        <div class="admin-container max-w-3xl">
            <div class="admin-page-header">
                <div>
                    <h1 class="admin-page-title">Forum Question</h1>
                    <p class="admin-page-subtitle">View and manage this forum topic.</p>
                </div>
                <a href="{{ route('admin.forum.index') }}" class="admin-back-btn">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Forum Management
                </a>
            </div>

            {{-- Card Pertanyaan --}}
            <div class="admin-form-card mb-8">
                <div class="mb-4">
                    <h2 class="text-2xl font-bold mb-2" style="color:var(--color-foreground)">{{ $question->title }}</h2>
                    <p class="text-muted-foreground" style="white-space:pre-wrap">{{ $question->content }}</p>
                </div>

                {{-- Info Pertanyaan --}}
                <div class="grid grid-cols-2 gap-4 pt-4 border-t mb-4">
                    <div>
                        <p class="text-sm text-muted-foreground">Category</p>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-accent text-foreground mt-1">
                            {{ $question->category }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-muted-foreground">Author</p>
                        <p class="font-medium mt-1">{{ $question->author ?? ($question->user?->name ?? 'Unknown') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-muted-foreground">Upvotes</p>
                        <p class="font-medium mt-1">{{ $question->upvotes ?? 0 }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-muted-foreground">Posted</p>
                        <p class="font-medium mt-1">{{ $question->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>

                {{-- Tag --}}
                @if ($question->tags && count($question->tags) > 0)
                    <div class="pt-4 border-t">
                        <p class="text-sm text-muted-foreground mb-2">Tags</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($question->tags as $tag)
                                <span class="px-2 py-1 bg-neutral-100 text-xs rounded">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Question Image --}}
                @if ($question->image)
                    <div class="pt-4 border-t">
                        <p class="text-sm text-muted-foreground mb-2">Image</p>
                        <img src="{{ Storage::url($question->image) }}" alt="Question image"
                            class="max-w-full h-auto rounded-lg">
                    </div>
                @endif

                {{-- Tombol Hapus --}}
                <div class="mt-6 pt-4 border-t">
                    <form action="{{ route('admin.forum.destroy', ['type' => 'question', 'id' => $question->id]) }}"
                        method="POST" onsubmit="return confirm('Delete this question and all its replies?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-btn" style="background:var(--admin-alert-error-bg); color:var(--color-destructive); border:1px solid var(--color-destructive);">
                            Delete Question
                        </button>
                    </form>
                </div>
            </div>
            {{-- Replies Section --}}
            <div class="mb-8">
                <h2 class="text-2xl font-bold mb-4" style="color:var(--color-foreground)">Replies ({{ $question->replies->count() }})</h2>

                @forelse($question->replies as $reply)
                    <div class="admin-form-card mb-4" style="padding:1.25rem;">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <p class="text-sm font-semibold" style="color:var(--color-foreground)">
                                    {{ $reply->author ?? ($reply->user?->name ?? 'Unknown') }}</p>
                                <p class="text-xs text-muted-foreground">{{ $reply->created_at->format('d M Y H:i') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="status-badge status-active">{{ $reply->upvotes ?? 0 }} upvotes</span>
                            </div>
                        </div>

                        <p class="text-foreground mb-4" style="white-space:pre-wrap">{{ $reply->content }}</p>

                        {{-- Hapus Reply --}}
                        <div class="pt-3 border-t">
                            <form action="{{ route('admin.forum.destroy', ['type' => 'reply', 'id' => $reply->id]) }}"
                                method="POST" onsubmit="return confirm('Delete this reply?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-action-link admin-action-link--danger">Delete Reply</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="admin-form-card">
                        <p class="text-center text-muted-foreground py-4">No replies yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
