@extends('layouts.app')

@section('title', 'Keranjang - e-TEFA Kompeni')

@section('content')
    <div class="cart-page">
        <div class="page-container" style="max-width:72rem">
            <h1 class="page-title" style="margin-bottom:0.5rem">🛒 Keranjang</h1>
            <p class="page-subtitle-text" style="margin-bottom:2rem">Pilih produk yang ingin dibayar, ubah jumlah, atau hapus dari keranjang</p>

            @if (session('success'))
                <div class="auth-alert-success" style="margin-bottom:1.5rem">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="auth-alert-error" style="margin-bottom:1.5rem">{{ session('error') }}</div>
            @endif

            <div class="cart-grid">
                <div>
                    @if (empty($cartItems))
                        <div class="content-card--large empty-state">
                            <div style="font-size:4rem;margin-bottom:1rem">🛍️</div>
                            <p style="font-size:1.125rem;font-weight:600;margin-bottom:0.5rem">Keranjang masih kosong</p>
                            <p class="empty-state-desc">Mulai belanja produk hidroponik favoritmu!</p>
                            <a href="{{ route('products.index') }}" class="btn-pill">Lanjut belanja</a>
                        </div>
                    @else
                        <form id="prepareCheckoutForm" method="POST" action="{{ route('cart.prepare-checkout') }}" style="display:none">
                            @csrf
                            <input type="hidden" name="selected_product_ids" id="prepareCheckoutSelectedIds">
                        </form>

                        <div class="content-card" style="padding:0;overflow:hidden">
                            <div style="background:linear-gradient(to right,#f0fdf4,#f8fafc);padding:1rem 1.5rem;border-bottom:1px solid var(--color-border);display:flex;align-items:center;justify-content:space-between">
                                <label style="display:flex;align-items:center;gap:0.75rem;cursor:pointer;font-size:0.875rem;font-weight:600">
                                    <input type="checkbox" id="selectAllCheckbox" style="width:1.25rem;height:1.25rem">
                                    Pilih semua
                                </label>
                                <span style="font-size:0.875rem;color:var(--color-muted-foreground)"><span id="selectedCount">0</span> dari {{ count($cartItems) }} dipilih</span>
                            </div>

                            @foreach ($cartItems as $item)
                                <div style="padding:1.5rem;border-bottom:1px solid var(--color-border)">
                                    <div style="display:flex;gap:1rem">
                                        <div style="flex-shrink:0;padding-top:0.25rem">
                                            <input type="checkbox" class="product-checkbox" style="width:1.25rem;height:1.25rem;cursor:pointer"
                                                data-product-id="{{ $item['product']->id }}"
                                                data-price="{{ $item['product']->price }}"
                                                data-quantity="{{ $item['quantity'] }}">
                                        </div>
                                        <div class="cart-item-thumb">
                                            @if ($item['product']->image)
                                                <img src="{{ $item['product']->image_url }}" alt="{{ $item['product']->name }}">
                                            @else
                                                <div style="width:100%;height:100%;background:var(--color-accent);display:flex;align-items:center;justify-content:center">
                                                    <svg style="width:2rem;height:2rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div style="flex:1;min-width:0">
                                            <h3 class="cart-item-name">{{ $item['product']->name }}</h3>
                                            <p class="cart-item-price">{{ $item['product']->category }}</p>
                                            <p style="font-size:1.5rem;font-weight:700;color:var(--color-primary);margin:0.5rem 0">
                                                Rp {{ number_format($item['product']->price, 0, ',', '.') }}
                                                <span style="font-size:0.875rem;font-weight:400;color:var(--color-muted-foreground)"> × {{ $item['quantity'] }} = <strong>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</strong></span>
                                            </p>
                                            <form action="{{ route('checkout.update-quantity') }}" method="POST" style="display:inline-flex;align-items:center;gap:0.5rem;margin-top:0.5rem">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                                <span style="font-size:0.875rem;font-weight:500">Jumlah:</span>
                                                <div style="display:flex;align-items:center;border:1px solid var(--color-border);border-radius:0.5rem">
                                                    <button type="button" class="product-qty-btn qty-decrement" data-product-id="{{ $item['product']->id }}" style="border:none">−</button>
                                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['product']->stock }}"
                                                        style="width:3.5rem;text-align:center;border:none;padding:0.375rem;font-weight:700"
                                                        onchange="this.form.submit()">
                                                    <button type="button" class="product-qty-btn qty-increment" data-product-id="{{ $item['product']->id }}" style="border:none">+</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div style="flex-shrink:0">
                                            <form action="{{ route('checkout.remove') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                                <button type="submit" class="btn-danger" style="width:auto;padding:0.5rem 1rem">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                @if (!empty($cartItems))
                    <div>
                        <div class="cart-summary">
                            <h3 class="cart-summary-title">Ringkasan</h3>
                            <div style="padding:1rem;background:#f0fdf4;border-radius:0.5rem;border:1px solid #bbf7d0;margin-bottom:1.5rem">
                                <p style="font-size:0.875rem;color:var(--color-muted-foreground);margin-bottom:0.25rem">Item dipilih</p>
                                <p style="font-size:2rem;font-weight:700;color:var(--color-primary)" id="sidebarSelectedCount">0</p>
                            </div>
                            <div class="cart-summary-row"><span>Subtotal</span><span id="subtotalAmount">Rp 0</span></div>
                            <div class="cart-summary-row"><span>Ongkir</span><span>Gratis</span></div>
                            <div class="cart-summary-total"><span>Total</span><span id="totalAmount">Rp 0</span></div>
                            <button id="checkoutBtn" disabled class="cart-checkout-btn">Beli sekarang</button>
                            <a href="{{ route('products.index') }}" class="btn-outline" style="width:100%;justify-content:center;margin-top:0.75rem">Lanjut belanja</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@if (!empty($cartItems))
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const selectAll = document.getElementById('selectAllCheckbox');
                const countEl = document.getElementById('selectedCount');
                const sidebarEl = document.getElementById('sidebarSelectedCount');
                const subtotalEl = document.getElementById('subtotalAmount');
                const totalEl = document.getElementById('totalAmount');
                const checkoutBtn = document.getElementById('checkoutBtn');
                const prepareForm = document.getElementById('prepareCheckoutForm');
                const prepareIds = document.getElementById('prepareCheckoutSelectedIds');
                const fmt = n => new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',minimumFractionDigits:0}).format(n);
                function boxes() { return document.querySelectorAll('.product-checkbox'); }
                function update() {
                    let c=0,t=0;
                    boxes().forEach(b=>{if(b.checked){c++;t+=(parseFloat(b.dataset.price)||0)*(parseInt(b.dataset.quantity)||0);}});
                    countEl.textContent=c; sidebarEl.textContent=c;
                    subtotalEl.textContent=fmt(t); totalEl.textContent=fmt(t);
                    checkoutBtn.disabled=c===0;
                    const ch=document.querySelectorAll('.product-checkbox:checked').length,all=boxes().length;
                    selectAll.checked=all>0&&ch===all; selectAll.indeterminate=ch>0&&ch<all;
                }
                selectAll.addEventListener('change',()=>{boxes().forEach(b=>b.checked=selectAll.checked);update();});
                boxes().forEach(b=>b.addEventListener('change',update));
                document.querySelectorAll('.qty-increment').forEach(btn=>btn.addEventListener('click',function(e){
                    e.preventDefault();const i=this.parentElement.querySelector('input[name="quantity"]');
                    if(i&&parseInt(i.value)<parseInt(i.max)){i.value=parseInt(i.value)+1;i.form.submit();}
                }));
                document.querySelectorAll('.qty-decrement').forEach(btn=>btn.addEventListener('click',function(e){
                    e.preventDefault();const i=this.parentElement.querySelector('input[name="quantity"]');
                    if(i&&parseInt(i.value)>1){i.value=parseInt(i.value)-1;i.form.submit();}
                }));
                boxes().forEach(b=>b.checked=true); update();
                checkoutBtn.addEventListener('click',function(){
                    const sel=Array.from(boxes()).filter(b=>b.checked).map(b=>b.dataset.productId);
                    if(!sel.length){alert('Pilih minimal satu produk');return;}
                    prepareIds.value=JSON.stringify(sel); prepareForm.submit();
                });
            });
        </script>
    @endpush
@endif
