@extends('layouts.app')

@section('title', 'Dashboard - e-TEFA Kompeni')

@section('content')
    <div class="user-dash-page">
        <div class="user-dash-container">
            <div class="user-dash-layout">
                @include('dashboard.partials.sidebar')

                <div class="user-dash-main">
                    {{-- Header --}}
                    <div class="hidden lg:block">
                        <h1 class="user-dash-title">Dashboard</h1>
                        <p class="user-dash-subtitle">Welcome back! Here's an overview of your activity</p>
                    </div>

                    {{-- Tabs --}}
                    <div class="user-dash-tabs">
                        <a href="{{ route('dashboard') }}"
                            class="user-dash-tab {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">Overview</a>
                        <a href="{{ route('dashboard.saved-articles') }}"
                            class="user-dash-tab {{ request()->routeIs('dashboard.saved-articles') ? 'is-active' : '' }}">Saved Articles</a>
                        <a href="{{ route('dashboard.my-forum-questions') }}"
                            class="user-dash-tab {{ request()->routeIs('dashboard.my-forum-questions') ? 'is-active' : '' }}">My Questions</a>
                        <a href="{{ route('dashboard.my-forum-replies') }}"
                            class="user-dash-tab {{ request()->routeIs('dashboard.my-forum-replies') ? 'is-active' : '' }}">My Replies</a>
                    </div>

                    {{-- Stat cards --}}
                    <div class="user-dash-stat-grid">
                        <a href="{{ route('dashboard.saved-articles') }}" class="user-dash-stat-card">
                            <div class="user-dash-stat-header">
                                <span class="user-dash-stat-label">Saved Articles</span>
                                <svg class="user-dash-stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                </svg>
                            </div>
                            <div class="user-dash-stat-value">{{ $savedArticlesCount }}</div>
                            <p class="user-dash-stat-sub">Articles bookmarked</p>
                        </a>

                        <a href="{{ route('dashboard.my-forum-questions') }}" class="user-dash-stat-card">
                            <div class="user-dash-stat-header">
                                <span class="user-dash-stat-label">Forum Posts</span>
                                <svg class="user-dash-stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                            </div>
                            <div class="user-dash-stat-value">{{ $forumPostsCount }}</div>
                            <p class="user-dash-stat-sub">Questions asked</p>
                        </a>

                        <div class="user-dash-stat-card">
                            <div class="user-dash-stat-header">
                                <span class="user-dash-stat-label">Orders</span>
                                <svg class="user-dash-stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div class="user-dash-stat-value">{{ $ordersCount }}</div>
                            <p class="user-dash-stat-sub">Total purchases</p>
                        </div>
                    </div>

                    {{-- Loyalty Section --}}
                    <div class="user-loyalty-section">
                        <div class="user-loyalty-header">
                            <div>
                                <h2 class="user-loyalty-title">Program Loyalitas</h2>
                                <p class="user-loyalty-subtitle">Kumpulkan poin di setiap pembelian</p>
                            </div>
                        </div>

                        <div class="user-loyalty-stat-grid">
                            <div class="user-loyalty-stat-box">
                                <p class="user-loyalty-stat-label">Poin Tersedia</p>
                                <p class="user-loyalty-stat-value">{{ $loyaltyPoint->current_points ?? 0 }}</p>
                                <p class="user-loyalty-stat-sub">Siap untuk ditukar</p>
                            </div>
                            <div class="user-loyalty-stat-box">
                                <p class="user-loyalty-stat-label">Total Poin Diperoleh</p>
                                <p class="user-loyalty-stat-value">{{ $loyaltyPoint->total_earned_points ?? 0 }}</p>
                                <p class="user-loyalty-stat-sub">Dari semua pembelian</p>
                            </div>
                            <div class="user-loyalty-stat-box">
                                <p class="user-loyalty-stat-label">Total Poin Ditukar</p>
                                <p class="user-loyalty-stat-value">{{ $loyaltyPoint->total_redeemed_points ?? 0 }}</p>
                                <p class="user-loyalty-stat-sub">Sudah ditukar</p>
                            </div>
                        </div>

                        <div class="user-loyalty-actions">
                            <a href="{{ route('loyalty.shop') }}" class="user-loyalty-btn user-loyalty-btn--primary">Tukar Point</a>
                            <a href="{{ route('loyalty.history') }}" class="user-loyalty-btn user-loyalty-btn--dark">Lihat Riwayat</a>
                        </div>
                        <p class="user-loyalty-note">
                            Poin ditambahkan otomatis setelah pembayaran Midtrans berhasil. Muat ulang untuk melihat angka terbaru.
                        </p>
                    </div>

                    {{-- Recent Activity --}}
                    <div class="user-activity-card">
                        <h2 class="user-activity-title">Recent Activity</h2>
                        <div class="user-activity-list">
                            @forelse($recentActivities as $activity)
                                <div class="user-activity-item">
                                    <div class="user-activity-icon">
                                        @if ($activity->type == 'forum')
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                        @elseif($activity->type == 'order')
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        @elseif($activity->type == 'saved')
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                                        @else
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        @endif
                                    </div>
                                    <div class="user-activity-body">
                                        <p class="user-activity-main">{{ $activity->title }}</p>
                                        <p class="user-activity-desc">{{ $activity->description }}</p>
                                        <p class="user-activity-time">{{ $activity->time }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="user-activity-empty">No recent activity</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
