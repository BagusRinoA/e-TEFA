@extends('layouts.app')

@section('title', 'Sales Report - Admin')

@section('content')
    <div class="admin-page admin-page--dashboard">
        <div class="admin-container">
            <div class="admin-layout">
                {{-- Sidebar --}}
                @include('admin.partials.sidebar')

                {{-- Main Content --}}
                <div class="admin-main">
                    
                    {{-- Header --}}
                    <div class="admin-page-header">
                        <div>
                            <h1 class="admin-page-title">Sales Report</h1>
                            <p class="admin-page-subtitle">Overview of recent sales and revenue.</p>
                        </div>
                        <a href="{{ route('admin.dashboard') }}" class="admin-back-btn">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Dashboard
                        </a>
                    </div>

                    {{-- Filters & Print --}}
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-2">
                        <form method="GET" action="{{ route('admin.report.sales') }}" class="flex items-center gap-2">
                            <input type="date" name="from" value="{{ request('from') }}" class="admin-input" style="margin-top:0; width:auto;">
                            <input type="date" name="to" value="{{ request('to') }}" class="admin-input" style="margin-top:0; width:auto;">
                            <button type="submit" class="admin-btn admin-btn-primary">Apply</button>
                            <a href="{{ route('admin.report.sales') }}" class="admin-btn" style="background:var(--color-accent); color:var(--color-primary)">Reset</a>
                        </form>

                        <a href="{{ route('admin.report.sales.print', ['from' => request('from'), 'to' => request('to')]) }}"
                            target="_blank" class="admin-btn" style="border:1px solid var(--color-border); background:#fff">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Print Report
                        </a>
                    </div>

                    {{-- Stat cards --}}
                    <div class="admin-stat-grid">
                        <div class="admin-stat-card" style="--admin-stat-accent: #22c55e;">
                            <div class="admin-stat-icon">
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="admin-stat-label">Total Revenue</h3>
                            <div class="admin-stat-value">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
                        </div>

                        <div class="admin-stat-card" style="--admin-stat-accent: #3b82f6;">
                            <div class="admin-stat-icon" style="color: #3b82f6; background: rgba(59, 130, 246, 0.1);">
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <h3 class="admin-stat-label">Total Orders</h3>
                            <div class="admin-stat-value">{{ $totalOrders ?? 0 }}</div>
                        </div>

                        <div class="admin-stat-card" style="--admin-stat-accent: #8b5cf6;">
                            <div class="admin-stat-icon" style="color: #8b5cf6; background: rgba(139, 92, 246, 0.1);">
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <h3 class="admin-stat-label">Average Order</h3>
                            <div class="admin-stat-value">Rp {{ number_format($totalOrders ? $totalRevenue / $totalOrders : 0, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    {{-- Top selling products --}}
                    <div class="admin-table-card mb-8">
                        <div class="admin-table-card-header">
                            <span class="admin-table-card-title">Top Selling Products</span>
                        </div>
                        @if (!empty($topProducts) && $topProducts->count())
                            <div class="admin-table-scroll">
                                <table class="admin-table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Quantity Sold</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topProducts as $p)
                                            <tr>
                                                <td>{{ $p->name }}</td>
                                                <td class="td-amount">{{ $p->total_sold }} pcs</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="admin-table-scroll">
                                <table class="admin-table">
                                    <tbody><tr><td class="td-empty">No product sales in this range.</td></tr></tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    {{-- Recent orders table --}}
                    <div class="admin-table-card">
                        <div class="admin-table-card-header">
                            <span class="admin-table-card-title">Recent Orders</span>
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
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td class="td-id">#{{ $order->id }}</td>
                                            <td>
                                                <span class="td-amount">{{ $order->customer_name ?? ($order->user?->full_name ?? 'Guest') }}</span>
                                                <span class="td-sub td-muted">{{ $order->customer_email }}</span>
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
                                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($orders->hasPages())
                            <div class="admin-pagination">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
