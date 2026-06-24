<aside class="user-sidebar">
    <div class="user-sidebar-card">
        <div class="user-sidebar-profile">
            <div class="user-sidebar-avatar">
                @if (Auth::user()->profile_photo)
                    <img src="{{ Storage::url(Auth::user()->profile_photo) . '?t=' . time() }}"
                        alt="{{ Auth::user()->full_name }}">
                @else
                    {{ strtoupper(substr(Auth::user()->username, 0, 2)) }}
                @endif
            </div>
            <h3 class="user-sidebar-name">{{ Auth::user()->full_name ?? Auth::user()->username }}</h3>
            <p class="user-sidebar-handle">{{ '@' . Auth::user()->username }}</p>
        </div>

        <div class="user-sidebar-section">
            <h2 class="user-sidebar-heading">My Account</h2>
            <nav class="user-sidebar-nav">
                <a href="{{ route('dashboard') }}"
                    class="user-sidebar-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1v-3z">
                        </path>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('dashboard.orders') }}"
                    class="user-sidebar-link {{ request()->routeIs('dashboard.orders*') ? 'is-active' : '' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span>My Orders</span>
                </a>
                <a href="{{ route('profile.edit') }}"
                    class="user-sidebar-link {{ request()->routeIs('profile.edit') ? 'is-active' : '' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>Profile Settings</span>
                </a>
            </nav>
        </div>

        <div class="user-sidebar-section user-sidebar-section--compact">
            <h2 class="user-sidebar-heading">Quick Actions</h2>
            <nav class="user-sidebar-nav">
                <a href="{{ route('forum.create') }}"
                    class="user-sidebar-link {{ request()->routeIs('forum.create') ? 'is-active' : '' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                        </path>
                    </svg>
                    <span>Ask a Question</span>
                </a>
                <a href="{{ route('products.index') }}"
                    class="user-sidebar-link {{ request()->routeIs('products.index') ? 'is-active' : '' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    <span>Browse Products</span>
                </a>
                <a href="{{ route('articles.index') }}"
                    class="user-sidebar-link {{ request()->routeIs('articles.index') ? 'is-active' : '' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    <span>Read Articles</span>
                </a>
            </nav>
        </div>
    </div>
</aside>
