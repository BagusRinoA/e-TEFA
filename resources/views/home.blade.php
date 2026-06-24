@extends('layouts.app')

@section('title', 'Home - e-TEFA Kompeni')

@section('content')
    <div class="flex flex-col">
        <!-- Hero Section -->
        <section class="relative overflow-hidden py-20 lg:py-15 bg-white"
            style="
                background-image:
                    linear-gradient(rgba(34,197,94,0.2) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(34,197,94,0.2) 1px, transparent 1px);
                background-size: 40px 40px;
            ">

            <div class="relative z-10 container mx-auto px-4">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div class="space-y-6">

                        <span
                            class="inline-flex items-center px-3 py-1 rounded-lg bg-primary text-white text-sm font-medium">
                            🌱 Grow Sustainably
                        </span>
                        <h1 class="text-4xl lg:text-6xl font-bold text-gray-900 leading-tight">
                            Welcome to <span class="text-primary">E-TEFA Kompeni</span>
                        </h1>
                        <p class="text-lg text-gray-600">
                            Your one-stop platform for hydroponic farming. Buy fresh plants, learn from experts,
                            and connect with a thriving community of growers.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('products.index') }}">
                                <button
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-primary text-primary-foreground hover:bg-primary/90 transition-colors">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    Shop Now
                                </button>
                            </a>
                            <a href="{{ route('forum.index') }}">
                                <button
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg border border-border hover:bg-accent transition-colors">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                        </path>
                                    </svg>
                                    Join Discussion
                                </button>
                            </a>
                        </div>
                    </div>
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl w-full h-[400px]">
                        <img id="slider" src="{{ asset('images/sayur.jpg') }}"
                            class="w-full h-full object-cover transition-all duration-700" alt="Slider Image">
                    </div>

                    <script>
                        const images = [
                            "{{ asset('images/sayur.jpg') }}",
                            "{{ asset('images/sayur1.jpg') }}",
                            "{{ asset('images/sayur2.jpg') }}"
                        ];

                        let index = 0;
                        const slider = document.getElementById('slider');

                        setInterval(() => {
                            index = (index + 1) % images.length;
                            slider.src = images[index];
                        }, 3000); // ganti setiap 3 detik
                    </script>
                </div>
            </div>
    </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">Why Choose Hydroponic Farming?</h2>
                <p class="text-muted-foreground max-w-2xl mx-auto">
                    Discover the benefits of modern agriculture with hydroponics
                </p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-lg border-2 border-border hover:border-primary transition-colors p-6">
                    <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Water Efficient</h3>
                    <p class="text-muted-foreground">
                        Uses up to 90% less water compared to traditional soil farming
                    </p>
                </div>

                <div class="bg-white rounded-lg border-2 border-border hover:border-primary transition-colors p-6">
                    <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Faster Growth</h3>
                    <p class="text-muted-foreground">
                        Plants grow 30-50% faster with optimal nutrient delivery
                    </p>
                </div>

                <div class="bg-white rounded-lg border-2 border-border hover:border-primary transition-colors p-6">
                    <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Year-Round Growing</h3>
                    <p class="text-muted-foreground">
                        Grow fresh produce any season with controlled environments
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-16 bg-secondary">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold mb-2">Featured Products</h2>
                    <p class="text-muted-foreground">Fresh hydroponic plants ready for you</p>
                </div>
                <a href="{{ route('products.index') }}">
                    <button class="px-4 py-2 rounded-lg border border-border hover:bg-accent transition-colors">
                        View All
                    </button>
                </a>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($featuredProducts as $product)
                    <a href="{{ route('products.show', $product->id) }}">
                        <div class="bg-white rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                            <div class="relative h-48 overflow-hidden">
                                <img src="{{ $product->image_url ?? 'https://via.placeholder.com/300x300?text=No+Image' }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                <span
                                    class="absolute top-3 right-3 inline-flex items-center px-2 py-1 rounded-lg bg-white text-primary text-sm font-medium">
                                    {{ $product->stock }} in stock
                                </span>
                            </div>
                            <div class="p-6">
                                <h3 class="text-lg font-semibold mb-1">{{ $product->name }}</h3>
                                <p class="text-sm text-muted-foreground mb-4">{{ $product->description }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-2xl font-bold text-primary">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                    <button
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary text-primary-foreground text-sm hover:bg-primary/90">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Forum Preview -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold mb-2">Community Discussion</h2>
                    <p class="text-muted-foreground">Join the conversation with fellow growers</p>
                </div>
                <a href="{{ route('forum.index') }}">
                    <button
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-border hover:bg-accent transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>
                        View Forum
                    </button>
                </a>
            </div>
            <div class="grid gap-4">
                @foreach ($forumQuestions as $question)
                    <a href="{{ route('forum.show', $question->id) }}">
                        <div class="bg-white rounded-lg border hover:shadow-md transition-shadow p-6">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold mb-2">{{ $question->title }}</h3>
                                    <div class="flex items-center gap-4 text-sm text-muted-foreground">
                                        <span>by {{ $question->author }}</span>
                                        <span>•</span>
                                        <span>{{ $question->replies }} replies</span>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    @foreach ($question->tags as $tag)
                                        <span class="inline-flex items-center px-2 py-1 rounded-lg bg-secondary text-sm">
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Articles Section -->
    <section class="py-16 bg-secondary">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold mb-2">Latest Articles</h2>
                    <p class="text-muted-foreground">Learn from expert growers and researchers</p>
                </div>
                <a href="{{ route('articles.index') }}">
                    <button
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-border hover:bg-accent transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                        All Articles
                    </button>
                </a>
            </div>
            <div class="grid md:grid-cols-2 gap-6">
                @foreach ($articles as $article)
                    <a href="{{ route('articles.show', $article->id) }}">
                        <div class="bg-white rounded-lg overflow-hidden hover:shadow-lg transition-shadow h-full">
                            <div class="h-48 overflow-hidden">
                                <img src="{{ $article->image ? Storage::url($article->image) : 'https://via.placeholder.com/300x200?text=No+Image' }}"
                                    alt="{{ $article->title }}"
                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            </div>
                            <div class="p-6">
                                <h3 class="text-lg font-semibold mb-2">{{ $article->title }}</h3>
                                <p class="text-sm text-muted-foreground">{{ $article->excerpt }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    </div>
@endsection
