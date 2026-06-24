@extends('layouts.app')

@section('title', 'Products - e-TEFA Kompeni')

@section('content')
    <div class="page-section">
        <div class="page-container">
            <div style="margin-bottom:2rem">
                <h1 class="page-title">Hydroponic Plants</h1>
                <p class="page-subtitle-text">Fresh, healthy plants grown with care</p>
            </div>

            <div class="filter-box">
                <form method="GET" action="{{ route('products.index') }}">
                    <div class="filter-search">
                        <svg class="filter-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" name="search" placeholder="Search products..."
                            value="{{ request('search') }}" class="filter-input">
                    </div>
                    <div class="filter-tags" style="margin-top:1rem">
                        <a href="{{ route('products.index') }}"
                            class="filter-tag {{ !request('category') ? 'is-active' : '' }}">All</a>
                        @foreach ($categories as $category)
                            <a href="{{ route('products.index', ['category' => $category]) }}"
                                class="filter-tag {{ request('category') == $category ? 'is-active' : '' }}">{{ $category }}</a>
                        @endforeach
                    </div>
                </form>
            </div>

            <div class="product-grid">
                @forelse($products as $product)
                    <a href="{{ route('products.show', $product->id) }}" class="product-card">
                        <div class="product-card-thumb">
                            <img src="{{ $product->image_url ?? 'https://via.placeholder.com/300x300?text=No+Image' }}"
                                alt="{{ $product->name }}">
                            @if ($product->stock > 0)
                                <span class="product-card-badge product-card-badge--new">{{ $product->stock }} in stock</span>
                            @else
                                <span class="product-card-badge product-card-badge--out">Out of Stock</span>
                            @endif
                        </div>
                        <div class="product-card-body">
                            <p class="product-card-name">{{ $product->name }}</p>
                            <p style="font-size:0.875rem;color:var(--color-muted-foreground);margin-bottom:0.75rem">{{ Str::limit($product->description, 60) }}</p>
                            <p class="product-card-price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>
                    </a>
                @empty
                    <div style="grid-column:1/-1">
                        <div class="empty-state">
                            <p style="color:var(--color-muted-foreground);font-size:1.125rem">No products found</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if ($products->hasPages())
                <div class="page-pagination">{{ $products->links() }}</div>
            @endif
        </div>
    </div>
@endsection
