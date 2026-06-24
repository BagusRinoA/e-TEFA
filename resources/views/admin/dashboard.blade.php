@extends('layouts.admin')

@section('title', 'Admin Dashboard - e-TEFA Kompeni')

@section('admin-page-class', 'admin-page--dashboard')

@section('admin-content')

    {{-- Header --}}
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Admin Dashboard</h1>
            <p class="admin-page-subtitle">Manage your platform and monitor activity</p>
        </div>
        <a href="{{ route('admin.report.sales') }}" class="admin-dash-report-btn">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                </path>
            </svg>
            View Sales Report
        </a>
    </div>

    {{-- Stat Cards --}}
    <div class="admin-dash-stat-grid">

        <div class="admin-dash-stat-card">
            <div class="admin-dash-stat-card-header">
                <span class="admin-dash-stat-label">Total Revenue</span>
                <svg class="admin-dash-stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
            </div>
            <div class="admin-dash-stat-value">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
            <p class="admin-dash-stat-sub">
                {{ ($revenueGrowth ?? 0) >= 0 ? '+' : '' }}{{ number_format($revenueGrowth ?? 0, 1) }}% from last month
            </p>
        </div>

        <div class="admin-dash-stat-card">
            <div class="admin-dash-stat-card-header">
                <span class="admin-dash-stat-label">Total Orders</span>
                <svg class="admin-dash-stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
            <div class="admin-dash-stat-value">{{ $totalOrders ?? 0 }}</div>
            <p class="admin-dash-stat-sub">
                {{ ($ordersGrowth ?? 0) >= 0 ? '+' : '' }}{{ $ordersGrowth ?? 0 }} from last month
            </p>
        </div>

        <div class="admin-dash-stat-card">
            <div class="admin-dash-stat-card-header">
                <span class="admin-dash-stat-label">Products</span>
                <svg class="admin-dash-stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
            </div>
            <div class="admin-dash-stat-value">{{ $totalProducts ?? 0 }}</div>
            <p class="admin-dash-stat-sub">{{ $lowStockCount ?? 0 }} low stock</p>
        </div>

        <div class="admin-dash-stat-card">
            <div class="admin-dash-stat-card-header">
                <span class="admin-dash-stat-label">Active Users</span>
                <svg class="admin-dash-stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
            </div>
            <div class="admin-dash-stat-value">{{ $activeUsers ?? 0 }}</div>
            <p class="admin-dash-stat-sub">+{{ $newUsersThisWeek ?? 0 }} since last week</p>
        </div>

        <div class="admin-dash-stat-card">
            <div class="admin-dash-stat-card-header">
                <span class="admin-dash-stat-label">Point Configuration</span>
                <svg class="admin-dash-stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
            </div>
            <div class="admin-dash-stat-value">{{ $pointConfigurations ?? 0 }}</div>
            <p class="admin-dash-stat-sub">Active Configurations</p>
        </div>

        <div class="admin-dash-stat-card">
            <div class="admin-dash-stat-card-header">
                <span class="admin-dash-stat-label">Redemption Request</span>
                <svg class="admin-dash-stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
            </div>
            <div class="admin-dash-stat-value">{{ $pendingRedemptions ?? 0 }}</div>
            <p class="admin-dash-stat-sub">Pending Redemptions</p>
        </div>

    </div>

    {{-- Info order terbaru & Stok menipis --}}
    <div class="admin-dash-panel-grid">

        <div class="admin-dash-panel">
            <h2 class="admin-dash-panel-title">Recent Orders</h2>
            <div class="admin-dash-panel-list">
                @forelse($recentOrders ?? [] as $order)
                    <div class="admin-dash-panel-row">
                        <div>
                            <p class="admin-dash-panel-row-main">Order #{{ $order->id }}</p>
                            <p class="admin-dash-panel-row-sub">{{ $order->customer_name }}</p>
                        </div>
                        <div style="text-align:right">
                            <p class="admin-dash-panel-row-amount">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                            <span
                                class="status-badge {{ $order->status == 'completed' ? 'status-completed' : 'status-pending' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="admin-dash-panel-empty">No recent orders</p>
                @endforelse
            </div>
        </div>

        <div class="admin-dash-panel">
            <h2 class="admin-dash-panel-title">Low Stock Alert</h2>
            <div class="admin-dash-panel-list">
                @forelse($lowStockProducts ?? [] as $product)
                    <div class="admin-dash-panel-row">
                        <div class="admin-dash-product-cell">
                            <img src="{{ $product->image_url ?? 'https://via.placeholder.com/48x48?text=No+Image' }}"
                                alt="{{ $product->name }}" class="admin-dash-product-thumb">
                            <div>
                                <p class="admin-dash-panel-row-main">{{ $product->name }}</p>
                                <p class="admin-dash-panel-row-sub">{{ $product->category }}</p>
                            </div>
                        </div>
                        <span class="status-badge {{ $product->stock < 10 ? 'status-canceled' : 'status-active' }}">
                            {{ $product->stock }} left
                        </span>
                    </div>
                @empty
                    <p class="admin-dash-panel-empty">All products in stock</p>
                @endforelse
            </div>
        </div>
    </div>

@endsection
