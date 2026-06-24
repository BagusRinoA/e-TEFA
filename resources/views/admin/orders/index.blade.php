@extends('layouts.admin')

@section('title', 'Manage Orders - Admin')

@section('admin-content')
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Manage Orders</h1>
            <p class="admin-page-subtitle">Review order activity and update order statuses.</p>
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

    {{-- Tabel Pesanan --}}
    <div class="admin-table-card">
        <div class="admin-table-card-header">
            <span class="admin-table-card-title">All Orders</span>
            <span class="admin-page-subtitle">{{ $orders->total() }} total orders</span>
        </div>

        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="td-id">#{{ $order->id }}</td>
                            <td>
                                <span
                                    class="font-medium">{{ $order->customer_name ?? ($order->user?->full_name ?? 'Guest') }}</span>
                                <span class="td-muted td-sub">{{ $order->customer_email }}</span>
                            </td>
                            <td class="td-amount">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td>
                                <span class="status-badge status-{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="td-muted">{{ $order->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="admin-action-link">
                                    <svg width="13" height="13" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Open
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="td-empty">
                                <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    class="admin-empty-icon">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                No orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="admin-pagination">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
