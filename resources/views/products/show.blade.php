@extends('layouts.app')

@section('title', $product->name . ' - Products')

@section('content')
    <div class="page-section">
        <div class="page-container">
            @include('components.back-button', ['href' => route('products.index'), 'label' => 'Back to Products'])

            <div class="product-show-grid">
                {{-- Gallery --}}
                <div class="product-show-gallery">
                    @if ($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                    @else
                        <div style="width:100%;aspect-ratio:1;background:var(--color-accent);display:flex;align-items:center;justify-content:center">
                            <span style="color:var(--color-muted-foreground)">No image available</span>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="product-show-info">
                    <h1 class="product-show-name">{{ $product->name }}</h1>

                    <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap">
                        <span class="article-category-badge">{{ $product->category }}</span>
                        @if ($product->featured)
                            <span style="padding:0.5rem 1rem;border-radius:9999px;background:var(--color-accent);color:var(--color-foreground);font-size:0.875rem">Featured</span>
                        @endif
                    </div>

                    <p class="product-show-price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                    <p class="product-show-desc">{{ $product->description }}</p>

                    <div style="padding:1rem;background:var(--color-secondary);border-radius:0.75rem">
                        <p class="product-show-stock">
                            <strong>Stock:</strong>
                            <span style="color:{{ $product->stock > 0 ? '#16a34a' : 'var(--color-destructive)' }}">
                                {{ $product->stock > 0 ? $product->stock . ' available' : 'Out of stock' }}
                            </span>
                        </p>
                    </div>

                    @auth
                        @if ($product->stock > 0)
                            <form method="POST" id="addToCartForm">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="product-show-qty" style="margin-bottom:1rem">
                                    <label style="font-size:0.875rem;font-weight:500">Quantity:</label>
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                                        id="quantityInput" class="profile-input" style="width:5rem;text-align:center">
                                </div>
                                <div style="display:flex;gap:0.75rem">
                                    <button type="button" id="addToCartBtn" class="product-show-add-btn" style="flex:1">Add to Cart</button>
                                    <button type="submit" formaction="{{ route('checkout.buy-now') }}"
                                        class="btn-outline" style="flex:1;justify-content:center;padding:0.875rem">Buy Now</button>
                                </div>
                            </form>

                            <script>
                                document.getElementById('addToCartBtn').addEventListener('click', function(e) {
                                    e.preventDefault();
                                    const form = document.getElementById('addToCartForm');
                                    const productId = form.querySelector('input[name="product_id"]').value;
                                    const quantity = document.getElementById('quantityInput').value;
                                    const token = form.querySelector('input[name="_token"]').value;
                                    fetch('{{ route("checkout.add") }}', {
                                        method: 'POST',
                                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                                        body: JSON.stringify({ product_id: productId, quantity: quantity })
                                    })
                                    .then(r => r.json())
                                    .then(data => showModal('success', data.message || 'Produk berhasil ditambahkan ke keranjang!'))
                                    .catch(() => showModal('error', 'Terjadi kesalahan saat menambahkan ke keranjang!'));
                                });

                                function showModal(type, message) {
                                    const isSuccess = type === 'success';
                                    const backdrop = document.createElement('div');
                                    backdrop.setAttribute('data-modal-backdrop', 'true');
                                    backdrop.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:40;display:flex;align-items:center;justify-content:center';
                                    backdrop.innerHTML = `<div style="background:#fff;border-radius:1rem;padding:2rem;max-width:28rem;width:calc(100% - 2rem);text-align:center">
                                        <div style="width:4rem;height:4rem;border-radius:9999px;background:${isSuccess ? '#f0fdf4' : '#fef2f2'};display:flex;align-items:center;justify-content:center;margin:0 auto 1rem">
                                            <svg style="width:2rem;height:2rem;color:${isSuccess ? '#16a34a' : '#dc2626'}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${isSuccess ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'}"/></svg>
                                        </div>
                                        <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:0.75rem">${isSuccess ? 'Berhasil!' : 'Terjadi Kesalahan'}</h3>
                                        <p style="color:#6b7280;margin-bottom:1.5rem">${message}</p>
                                        <div style="display:flex;gap:0.75rem">
                                            ${isSuccess ? `<button onclick="this.closest('[data-modal-backdrop]').remove()" style="flex:1;padding:0.75rem;border-radius:0.5rem;background:#e5e7eb;font-weight:600;border:none;cursor:pointer">Lanjut Belanja</button>
                                            <a href="{{ route('cart.index') }}" style="flex:1;padding:0.75rem;border-radius:0.5rem;background:#16a34a;color:#fff;font-weight:600;text-decoration:none;display:flex;align-items:center;justify-content:center">Lihat Keranjang</a>` :
                                            `<button onclick="this.closest('[data-modal-backdrop]').remove()" style="width:100%;padding:0.75rem;border-radius:0.5rem;background:#dc2626;color:#fff;font-weight:600;border:none;cursor:pointer">Tutup</button>`}
                                        </div>
                                    </div>`;
                                    backdrop.addEventListener('click', e => { if (e.target === backdrop) backdrop.remove(); });
                                    document.body.appendChild(backdrop);
                                    if (isSuccess) setTimeout(() => backdrop.remove(), 5000);
                                }
                            </script>
                        @else
                            <button disabled class="product-show-add-btn" style="background:#9ca3af;cursor:not-allowed">Out of Stock</button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="product-show-add-btn" style="text-align:center;text-decoration:none">Login to Purchase</a>
                    @endauth
                </div>
            </div>

            {{-- Related products --}}
            <div style="margin-top:4rem">
                <h2 style="font-size:1.875rem;font-weight:700;margin-bottom:2rem;color:var(--color-foreground)">Similar Products</h2>
                <div class="product-grid">
                    @foreach (\App\Models\Product::where('category', $product->category)->where('id', '!=', $product->id)->take(4)->get() as $related)
                        <a href="{{ route('products.show', $related) }}" class="product-card">
                            <div class="product-card-thumb">
                                @if ($related->image_url)
                                    <img src="{{ $related->image_url }}" alt="{{ $related->name }}">
                                @else
                                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:var(--color-muted-foreground);font-size:0.75rem">No image</div>
                                @endif
                            </div>
                            <div class="product-card-body">
                                <p class="product-card-name">{{ $related->name }}</p>
                                <p class="product-card-price">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
