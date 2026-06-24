@extends('layouts.app')

@section('title', 'Order Details - Admin')

@section('content')
    <div class="admin-page">
        <div class="admin-container">
            <div class="admin-page-header">
                <div>
                    <h1 class="admin-page-title">Order #{{ $order->id }}</h1>
                    <p class="admin-page-subtitle">Details for this order and status management.</p>
                </div>
                <a href="{{ route('admin.orders') }}" class="admin-back-btn">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Orders
                </a>
            </div>

        @if(session('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>
        @endif

        <div class="grid gap-6 lg:grid-cols-2 mb-8">
            <div class="admin-form-card">
                <h2 class="text-xl font-semibold mb-4" style="color:var(--color-foreground)">Order Information</h2>
                <div class="space-y-3 text-sm text-muted-foreground">
                    <p><span class="font-semibold text-foreground">Customer:</span> {{ $order->customer_name ?? $order->user?->full_name ?? 'Guest' }}</p>
                    <p><span class="font-semibold text-foreground">Email:</span> {{ $order->customer_email }}</p>
                    <p><span class="font-semibold text-foreground">Phone:</span> {{ $order->customer_phone }}</p>
                    <p><span class="font-semibold text-foreground">Shipping:</span> {{ $order->shipping_address }}, {{ $order->shipping_city }} {{ $order->shipping_postal_code }}</p>
                    <p><span class="font-semibold text-foreground">Payment:</span> {{ ucfirst($order->payment_method) }}</p>
                    <p><span class="font-semibold text-foreground">Total:</span> Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="admin-form-card">
                <h2 class="text-xl font-semibold mb-4" style="color:var(--color-foreground)">Update Status</h2>
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <label class="admin-form-label mb-2">Order Status</label>
                    <select name="status" class="admin-input" style="margin-top:0">
                        @foreach(['pending', 'processing', 'packed', 'delivered', 'completed', 'canceled'] as $status)
                            <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="admin-btn admin-btn-primary mt-4">Update Status</button>
                </form>
            </div>
        </div>

        <div class="admin-form-card">
            <h2 class="text-xl font-semibold mb-4" style="color:var(--color-foreground)">Order Items</h2>
            <div class="space-y-4">
                @foreach($order->items as $item)
                    <div style="border:1px solid var(--color-border); border-radius:0.75rem; padding:1rem;">
                        <div class="flex items-center gap-4">
                            <div>
                                <p class="font-semibold text-foreground">{{ $item->product_name }}</p>
                                <p class="text-sm text-muted-foreground">Qty: {{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <div class="ml-auto text-right">
                                <p class="font-semibold" style="color:var(--color-foreground)">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
