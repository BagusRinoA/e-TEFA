@php $logoPath = public_path('images/login.jpg.jpeg'); @endphp
<header class="nav-header">
    <div class="nav-inner">
        <div class="nav-bar">

            {{-- ── Logo ── --}}
            <a href="{{ route('home') }}" class="nav-logo">
                @if (file_exists($logoPath))
                    <img src="{{ asset('images/login.jpg.jpeg') }}" alt="e-TEFA Kompeni" class="nav-logo-img">
                @else
                    <div style="background:#fff;border-radius:9999px;padding:0.5rem">
                        <svg style="width:1.5rem;height:1.5rem;color:var(--color-primary)" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                @endif
                <span class="nav-logo-text">e-TEFA Kompeni</span>
            </a>

            {{-- ── Desktop Navigation ── --}}
            <nav class="nav-desktop">
                @php
                    $navLinks = [
                        [
                            'route' => 'home',
                            'label' => 'Home',
                            'active' => 'home',
                            'icon' => 'M3 12l9-9 9 9M4 10.5v8.5h5v-5h6v5h5v-8.5',
                        ],
                        [
                            'route' => 'products.index',
                            'label' => 'Product',
                            'active' => 'products.*',
                            'icon' => 'M6 7h12l1.5 10.5H4.5L6 7zM8 7V5a4 4 0 018 0v2',
                        ],
                        [
                            'route' => 'forum.index',
                            'label' => 'Forum',
                            'active' => 'forum.*',
                            'icon' => 'M8 10h8M8 14h4M12 21l-7-7V5a2 2 0 012-2h10a2 2 0 012 2v9a2 2 0 01-2 2H12z',
                        ],
                        [
                            'route' => 'articles.index',
                            'label' => 'Article',
                            'active' => 'articles.*',
                            'icon' => 'M8 4h8M8 8h8M6 19V5a2 2 0 012-2h8a2 2 0 012 2v14l-6-3-6 3z',
                        ],
                    ];
                @endphp
                @foreach ($navLinks as $nl)
                    <a href="{{ route($nl['route']) }}"
                        class="nav-link {{ request()->routeIs($nl['active']) ? 'is-active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="{{ $nl['icon'] }}" />
                        </svg>
                        {{ $nl['label'] }}
                    </a>
                @endforeach
            </nav>

            {{-- ── Right actions ── --}}
            <div class="nav-actions">
                @auth
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="nav-btn hidden md:flex">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 13l3-3 3 3 4-4" />
                            </svg>
                            <span class="nav-btn-text">Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="nav-btn hidden md:flex">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 13l3-3 3 3 4-4" />
                            </svg>
                            <span class="nav-btn-text">Dashboard</span>
                        </a>
                        <a href="{{ route('cart.index') }}" class="nav-btn" title="Keranjang" style="display:none"
                            id="cart-nav-btn">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="nav-btn" style="display:none" id="profile-nav-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 12a5 5 0 100-10 5 5 0 000 10z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 21v-2a4 4 0 00-8 0v2" />
                        </svg>
                        <span class="nav-btn-text-md">{{ Auth::user()->username }}</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                        @csrf
                        <button type="submit" class="nav-btn">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7" />
                            </svg>
                            <span class="nav-btn-text">Logout</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-btn hidden md:flex">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 17l5-5-5-5" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H3" />
                        </svg>
                        <span class="nav-btn-text">Login</span>
                    </a>
                @endauth

                {{-- ── Mobile Toggle (Hamburger) ── --}}
                <div class="nav-mobile-toggle">
                    <button id="mobile-menu-toggle" class="nav-mobile-btn" title="Menu">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Show sm+ elements via JS to avoid Flash of unstyled content
        const w = window.innerWidth;
        if (w >= 640) {
            const cartBtn = document.getElementById('cart-nav-btn');
            const profileBtn = document.getElementById('profile-nav-btn');
            if (cartBtn) cartBtn.style.display = 'inline-flex';
            if (profileBtn) profileBtn.style.display = 'inline-flex';
        }

    });
</script>
