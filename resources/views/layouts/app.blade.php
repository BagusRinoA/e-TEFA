<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'e-TEFA Kompeni - Hydroponic Agriculture Platform')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background text-foreground overflow-x-hidden">
    <div class="min-h-screen flex flex-col">
        @include('components.header')

        <!-- Mobile Sidebar Overlay -->
        <div id="mobile-overlay" class="mobile-sidebar-overlay hidden"></div>

        <!-- Global Mobile Sidebar -->
        <aside id="mobile-sidebar" class="mobile-sidebar-wrapper translate-x-full">
            <div class="mobile-sidebar-content">
                <!-- Sidebar Header -->
                @auth
                    <div class="mobile-sidebar-header">
                        <div class="mobile-sidebar-header-top">
                            <span class="mobile-sidebar-header-brand">e-TEFA Kompeni</span>
                            <button id="mobile-menu-close" class="mobile-sidebar-close-btn">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <div class="mobile-sidebar-user">
                            <div class="mobile-sidebar-avatar">
                                {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                            </div>
                            <div>
                                <p class="mobile-sidebar-username">{{ Auth::user()->username }}</p>
                                <span class="mobile-sidebar-role-badge">
                                    {{ Auth::user()->role }}
                                </span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mobile-sidebar-guest-header">
                        <h2 class="mobile-sidebar-guest-title">Menu</h2>
                        <button id="mobile-menu-close" class="mobile-sidebar-close-btn" style="color: #6b7280;" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='transparent'">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                @endauth

                <!-- Main Navigation -->
                <div class="mobile-sidebar-section">
                    <p class="mobile-sidebar-heading">Navigation</p>
                    <nav class="mobile-sidebar-nav">
                        @php
                            $mainNavLinks = [
                                ['route' => 'home',           'label' => 'Home',     'active' => 'home',       'icon' => 'M3 12l9-9 9 9M4 10.5v8.5h5v-5h6v5h5v-8.5'],
                                ['route' => 'products.index', 'label' => 'Products', 'active' => 'products.*', 'icon' => 'M6 7h12l1.5 10.5H4.5L6 7zM8 7V5a4 4 0 018 0v2'],
                                ['route' => 'forum.index',    'label' => 'Forum',    'active' => 'forum.*',    'icon' => 'M8 10h8M8 14h4M12 21l-7-7V5a2 2 0 012-2h10a2 2 0 012 2v9a2 2 0 01-2 2H12z'],
                                ['route' => 'articles.index', 'label' => 'Articles', 'active' => 'articles.*', 'icon' => 'M8 4h8M8 8h8M6 19V5a2 2 0 012-2h8a2 2 0 012 2v14l-6-3-6 3z'],
                            ];
                        @endphp
                        @foreach ($mainNavLinks as $nl)
                            <a href="{{ route($nl['route']) }}"
                                class="mobile-sidebar-link {{ request()->routeIs($nl['active']) ? 'is-active' : '' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $nl['icon'] }}" />
                                </svg>
                                {{ $nl['label'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>

                <!-- Auth Section -->
                @auth
                    <div class="mobile-sidebar-section">
                        <p class="mobile-sidebar-heading">My Account</p>
                        <nav class="mobile-sidebar-nav">
                            @if (!in_array(Auth::user()->role, ['admin', 'superadmin']))
                                @php
                                    $accountLinks = [
                                        ['route' => 'dashboard',        'label' => 'Dashboard',        'active' => 'dashboard',         'icon' => 'M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1v-3z'],
                                        ['route' => 'dashboard.orders', 'label' => 'My Orders',        'active' => 'dashboard.orders*', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                                        ['route' => 'cart.index',       'label' => 'Keranjang',        'active' => 'cart.*',            'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'],
                                        ['route' => 'profile.edit',     'label' => 'Profile Settings', 'active' => 'profile.*',         'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                                    ];
                                @endphp
                            @else
                                @php
                                    $accountLinks = [
                                        ['route' => 'admin.dashboard', 'label' => 'Dashboard',       'active' => 'admin.dashboard', 'icon' => 'M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1v-3z'],
                                        ['route' => 'profile.edit',    'label' => 'Profile Setting', 'active' => 'profile.*',       'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                                    ];
                                @endphp
                            @endif
                            @foreach ($accountLinks as $link)
                                <a href="{{ route($link['route']) }}"
                                    class="mobile-sidebar-link {{ request()->routeIs($link['active']) ? 'is-active' : '' }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}" />
                                    </svg>
                                    {{ $link['label'] }}
                                </a>
                            @endforeach
                        </nav>
                    </div>

                    <div class="mobile-sidebar-section">
                        <p class="mobile-sidebar-heading">Quick Actions</p>
                        <nav class="mobile-sidebar-nav">
                            @if (!in_array(Auth::user()->role, ['admin', 'superadmin']))
                                @php
                                    $quickLinks = [
                                        ['route' => 'forum.create',   'label' => 'Ask a Question',  'active' => 'forum.create',   'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
                                        ['route' => 'products.index', 'label' => 'Browse Products', 'active' => 'products.index', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'],
                                        ['route' => 'articles.index', 'label' => 'Read Articles',   'active' => 'articles.index', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
                                    ];
                                @endphp
                            @else
                                @php
                                    $quickLinks = [
                                        ['route' => 'admin.products.index',               'label' => 'Manage Products',   'active' => 'admin.products.*',             'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m0 10v10l8 4'],
                                        ['route' => 'admin.orders',                       'label' => 'Manage Orders',     'active' => 'admin.orders*',                'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
                                        ['route' => 'admin.users',                        'label' => 'Manage Users',      'active' => 'admin.users*',                 'icon' => 'M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM16 11h4'],
                                        ['route' => 'admin.articles.index',               'label' => 'Manage Articles',   'active' => 'admin.articles.*',             'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                                        ['route' => 'admin.forum.index',                  'label' => 'Manage Forum',      'active' => 'admin.forum.*',                'icon' => 'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z'],
                                        ['route' => 'admin.report.sales',                 'label' => 'Sales Report',      'active' => 'admin.report.sales*',          'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                                        ['route' => 'admin.loyalty.configurations.index', 'label' => 'Loyalty Config',    'active' => 'admin.point-configurations*',  'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                                        ['route' => 'admin.redemption.items.index',       'label' => 'Redemption Items',  'active' => 'admin.redemption.items*',      'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                        ['route' => 'admin.redemption.transactions',      'label' => 'Redemption Trans.', 'active' => 'admin.redemption.transactions*','icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    ];
                                @endphp
                            @endif
                            @foreach ($quickLinks as $link)
                                <a href="{{ route($link['route']) }}"
                                    class="mobile-sidebar-link {{ request()->routeIs($link['active']) ? 'is-active' : '' }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}" />
                                    </svg>
                                    {{ $link['label'] }}
                                </a>
                            @endforeach
                        </nav>
                    </div>

                    <div class="mobile-sidebar-section">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="mobile-sidebar-link is-danger">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <div class="mobile-sidebar-section">
                        <a href="{{ route('login') }}" class="mobile-sidebar-link is-active" style="justify-content:center;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 17l5-5-5-5" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H3" />
                            </svg>
                            Login
                        </a>
                    </div>
                @endauth
            </div>
        </aside>

        <main class="flex-1">
            @yield('content')
        </main>

        @include('components.footer')
    </div>

    @if (session('success') || session('error') || session('info'))
        <div id="notification" class="fixed bottom-6 right-6 max-w-sm z-50">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 rounded-lg p-4 shadow-lg flex items-start gap-3">
                    <svg class="h-5 w-5 text-green-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            @elseif (session('error'))
                <div class="bg-red-100 border border-red-400 rounded-lg p-4 shadow-lg flex items-start gap-3">
                    <svg class="h-5 w-5 text-red-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            @elseif (session('info'))
                <div class="bg-blue-100 border border-blue-400 rounded-lg p-4 shadow-lg flex items-start gap-3">
                    <svg class="h-5 w-5 text-blue-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-blue-800">{{ session('info') }}</p>
                    </div>
                </div>
            @endif
        </div>

        <script>
            setTimeout(() => {
                const notification = document.getElementById('notification');
                if (notification) {
                    notification.style.opacity = '0';
                    notification.style.transition = 'opacity 0.3s ease';
                    setTimeout(() => notification.remove(), 300);
                }
            }, 3000);
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const mobileOverlay = document.getElementById('mobile-overlay');
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const mobileMenuClose = document.getElementById('mobile-menu-close');
            const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');

            function toggleSidebar() {
                mobileSidebar.classList.toggle('translate-x-full');
                mobileSidebar.classList.toggle('translate-x-0');
                mobileOverlay.classList.toggle('hidden');
            }

            function closeSidebarMenu() {
                mobileSidebar.classList.add('translate-x-full');
                mobileSidebar.classList.remove('translate-x-0');
                mobileOverlay.classList.add('hidden');
            }

            // Toggle sidebar from header menu button
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleSidebar();
                });
            }

            // Close sidebar
            if (mobileMenuClose) {
                mobileMenuClose.addEventListener('click', closeSidebarMenu);
            }
            if (mobileOverlay) {
                mobileOverlay.addEventListener('click', closeSidebarMenu);
            }

            // Close sidebar when clicking on links
            mobileNavLinks.forEach(link => {
                link.addEventListener('click', () => {
                    closeSidebarMenu();
                });
            });
        });

        // Global Password Toggle
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('svg');
            if (!input || !icon) return;
            
            if (input.type === 'password') {
                input.type = 'text';
                btn.title = "Hide Password";
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
            } else {
                input.type = 'password';
                btn.title = "Show Password";
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }
    </script>

    @stack('scripts')
</body>

</html>
