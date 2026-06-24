@extends('layouts.app')

@section('title', 'Ask a Question - e-TEFA Kompeni')

@section('content')
    <div class="page-section">
        <div class="page-container" style="max-width:56rem">
            <div style="margin-bottom:2rem">
                <a href="{{ route('forum.index') }}" class="btn-outline" style="margin-bottom:1rem;display:inline-flex">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Forum
                </a>
                <h1 class="page-title">Ask a Question</h1>
                <p class="page-subtitle-text">Share your hydroponic questions with the community</p>
            </div>

            <div style="display:grid;gap:1.5rem" class="lg-grid-3">
                <div style="grid-column:span 2">
                    <div class="forum-form-card">
                        <h2 style="font-size:1.125rem;font-weight:600;margin-bottom:0.5rem">Question Details</h2>
                        <p style="font-size:0.875rem;color:var(--color-muted-foreground);margin-bottom:1.5rem">
                            Be specific and imagine you're asking a question to another person
                        </p>

                        @if($errors->any())
                            <div class="auth-alert-error" style="margin-bottom:1rem">
                                <ul style="list-style:none;padding:0;margin:0;font-size:0.875rem">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('forum.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="forum-form-field">
                                <label for="title" class="forum-form-label">Title</label>
                                <input id="title" name="title" type="text"
                                    placeholder="e.g., How do I fix root rot in my DWC system?"
                                    value="{{ old('title') }}" required class="forum-form-input">
                            </div>
                            <div class="forum-form-field">
                                <label for="category" class="forum-form-label">Category</label>
                                <select id="category" name="category" required class="forum-form-select">
                                    <option value="">Select a category</option>
                                    @foreach(['Nutrients','pH Balance','Systems','Lighting','Pests & Disease','Equipment','Getting Started','Troubleshooting'] as $cat)
                                        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="forum-form-field">
                                <label for="description" class="forum-form-label">Description</label>
                                <textarea id="description" name="description" rows="8"
                                    placeholder="Provide details about your question..." required
                                    class="forum-textarea">{{ old('description') }}</textarea>
                            </div>
                            <div class="forum-form-field">
                                <label for="tags" class="forum-form-label">Tags (Optional)</label>
                                <input id="tags" name="tags" type="text"
                                    placeholder="Add tags separated by commas (e.g., lettuce, pH, nutrients)"
                                    value="{{ old('tags') }}" class="forum-form-input">
                                <p class="forum-form-hint">Add up to 5 tags to help others find your question</p>
                            </div>
                            <div class="forum-form-field">
                                <label for="image" class="forum-form-label">Image (Optional)</label>
                                <input id="image" name="image" type="file" accept="image/*" class="forum-form-input">
                            </div>
                            <div class="forum-form-actions">
                                <button type="submit" class="btn-primary" style="padding:0.625rem 1.5rem">Post Question</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div>
                    <div class="content-card">
                        <h3 style="font-weight:600;margin-bottom:1rem">Writing Tips</h3>
                        <div style="display:flex;flex-direction:column;gap:1rem">
                            @foreach([['Be specific','Include details about your setup, what you\'ve tried, and what results you\'ve observed'],['Use clear titles','Make it easy for others to understand your question at a glance'],['Add context','Mention your experience level and what you\'re trying to achieve'],['Include images','Photos can help the community better understand your situation']] as $tip)
                                <div>
                                    <h4 style="font-weight:500;margin-bottom:0.25rem">{{ $tip[0] }}</h4>
                                    <p style="font-size:0.875rem;color:var(--color-muted-foreground)">{{ $tip[1] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media (min-width:1024px) {
            .lg-grid-3 { grid-template-columns: 2fr 1fr; }
        }
    </style>
@endsection
