@extends('layouts.admin')

@section('title', 'Manage Products - Admin')

@section('admin-content')
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Manage Products</h1>
            <p class="admin-page-subtitle">Manage your product inventory from the admin panel.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="admin-back-btn">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Dashboard
        </a>
    </div>

    @if (session('success'))
        <div class="admin-alert admin-alert-success">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel Produk --}}
    <div class="admin-table-card">
        <div class="admin-table-card-header">
            <span class="admin-table-card-title">All Products</span>
            <span class="admin-page-subtitle">{{ $products->total() }} total products</span>
        </div>

        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                <span class="font-medium">{{ $product->name }}</span>
                            </td>
                            <td class="td-muted">{{ $product->category }}</td>
                            <td class="td-amount">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>
                                <span class="status-badge status-{{ $product->stock > 0 ? 'active' : 'inactive' }}">
                                    {{ $product->stock }} in stock
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $product->featured ? 'active' : 'inactive' }}">
                                    {{ $product->featured ? 'Featured' : 'Standard' }}
                                </span>
                            </td>
                            <td>
                                <div class="admin-action-group">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="admin-action-link">
                                        <svg width="13" height="13" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                        class="admin-form-inline" onsubmit="return confirm('Delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-action-link admin-action-link--danger">
                                            <svg width="13" height="13" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="td-empty">
                                <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    class="admin-empty-icon">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M3 7h18M3 12h18M3 17h18" />
                                </svg>
                                No products found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="admin-pagination">
            {{ $products->links() }}
        </div>
    </div>

    <div class="admin-page-actions">
        <a href="{{ route('admin.products.create') }}" class="admin-btn admin-btn-primary">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add New Product
        </a>
    </div>
@endsection
