<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>@yield('meta_title', config('app.name', 'Info Seputar +62'))</title>
    <meta name="description" content="@yield('meta_description', 'Portal berita terpercaya Indonesia — Info Seputar +62.')">
    <meta name="keywords" content="@yield('meta_keywords', 'berita Indonesia, berita terkini, portal berita')">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('og_title', config('app.name'))">
    <meta property="og:description" content="@yield('og_description', 'Portal berita terpercaya Indonesia.')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.png'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="Info Seputar +62">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', config('app.name'))">
    <meta name="twitter:description" content="@yield('og_description', 'Portal berita terpercaya Indonesia.')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/og-default.png'))">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet"/>

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* === CATEGORY BAR DROPDOWN === */
        .cat-item { position: relative; }
        .cat-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            z-index: 999;
            min-width: 200px;
            animation: fadeDropdown 0.15s ease;
        }
        .cat-item:hover .cat-dropdown { display: block; }
        @keyframes fadeDropdown {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* === PROSE CONTENT === */
        .article-content p { margin-bottom: 1.25rem; line-height: 1.85; }
        .article-content h2 { font-size: 1.4rem; font-weight: 800; margin: 2rem 0 1rem; }
        .article-content h3 { font-size: 1.15rem; font-weight: 700; margin: 1.5rem 0 0.75rem; }
        .article-content blockquote { border-left: 4px solid #1E3A8A; padding-left: 1.25rem; font-style: italic; color: #64748b; }
        .dark .article-content blockquote { border-color: #3B82F6; color: #94a3b8; }

        /* === AD PLACEHOLDER === */
        .ad-box { background: repeating-linear-gradient(45deg, #f8fafc, #f8fafc 10px, #f1f5f9 10px, #f1f5f9 20px); border: 2px dashed #cbd5e1; border-radius: 12px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #94a3b8; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        .dark .ad-box { background: repeating-linear-gradient(45deg, #1e293b, #1e293b 10px, #0f172a 10px, #0f172a 20px); border-color: #334155; color: #475569; }
    </style>

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
            localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        }
    </script>

    @stack('head')
</head>
<body class="font-sans antialiased bg-slate-50 dark:bg-navy-900 text-slate-800 dark:text-gray-200 transition-colors duration-300">

    <!-- ===== STICKY HEADER (solid bg, never transparent on scroll) ===== -->
    <header class="sticky top-0 z-50 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700/80 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">

                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2 flex-shrink-0">
                    <img src="{{ asset('images/logo-infoseputar62.png') }}" alt="Info Seputar +62" class="h-8 w-auto">
                </a>

                <!-- Desktop Nav Links -->
                <nav class="hidden md:flex items-center gap-1">
                    <a href="{{ route('home') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('home') ? 'text-primary dark:text-primary-500 bg-primary/8 dark:bg-primary-500/10' : 'text-slate-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary-400 hover:bg-slate-100 dark:hover:bg-gray-800' }}">Beranda</a>
                    <a href="{{ route('home') }}#terkini" class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary-400 hover:bg-slate-100 dark:hover:bg-gray-800 transition-all">Terbaru</a>
                    <a href="{{ route('home') }}#trending" class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary-400 hover:bg-slate-100 dark:hover:bg-gray-800 transition-all">Trending</a>
                </nav>

                <!-- Right Side Actions -->
                <div class="flex items-center gap-2 ml-auto">
                    <!-- Desktop Search (hidden on mobile) -->
                    <div class="hidden md:flex items-center bg-slate-100 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 px-3 py-2 gap-2 focus-within:ring-2 focus-within:ring-primary/30 dark:focus-within:ring-primary-500/30 transition-all">
                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" placeholder="Cari berita..." class="bg-transparent border-none outline-none text-sm text-slate-700 dark:text-gray-200 placeholder-slate-400 w-44 focus:w-56 transition-all duration-300">
                    </div>

                    <!-- Dark Mode Toggle -->
                    <button onclick="toggleDarkMode()" class="p-2 rounded-xl text-slate-500 hover:bg-slate-100 dark:text-gray-400 dark:hover:bg-gray-800 transition-all" aria-label="Toggle dark mode">
                        <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </button>

                    <!-- Hamburger: ONLY on mobile/tablet (hidden on md+) -->
                    <button id="mobile-menu-btn" class="flex md:hidden p-2 rounded-xl text-slate-500 hover:bg-slate-100 dark:text-gray-400 dark:hover:bg-gray-800 transition-all" aria-label="Open menu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- ===== CATEGORY BAR (Desktop, below main nav) ===== -->
        <div class="hidden md:block border-t border-gray-100 dark:border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <ul class="flex items-center gap-1 h-10 overflow-x-auto scrollbar-none">
                    @foreach($navCategories as $cat)
                        <li class="cat-item relative flex-shrink-0">
                            <a href="{{ route('category.show', $cat->slug) }}"
                               class="flex items-center gap-1 px-4 py-1.5 text-sm font-semibold rounded-lg transition-all duration-200 text-slate-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary-400 hover:bg-primary/5 dark:hover:bg-primary-500/10 {{ request()->is('kategori/'.$cat->slug) ? 'text-primary dark:text-primary-400 bg-primary/8' : '' }}">
                                {{ $cat->name }}
                                @if($cat->children->isNotEmpty())
                                    <svg class="w-3.5 h-3.5 opacity-50 transition-transform cat-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                @endif
                            </a>
                            @if($cat->children->isNotEmpty())
                                <div class="cat-dropdown absolute top-full left-0 mt-1 z-50 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xl py-2 min-w-48">
                                    @foreach($cat->children as $child)
                                        <a href="{{ route('category.show', $child->slug) }}"
                                           class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary-400 hover:bg-slate-50 dark:hover:bg-gray-800 transition-colors">
                                            <span class="w-1.5 h-1.5 rounded-full bg-primary/40 dark:bg-primary-500/40 flex-shrink-0"></span>
                                            {{ $child->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </header>

    <!-- ===== MOBILE SIDEBAR (hidden on desktop md+) ===== -->
    <div id="mobile-overlay" class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm hidden md:hidden"></div>
    <div id="mobile-sidebar" class="fixed inset-y-0 right-0 z-50 w-72 bg-white dark:bg-gray-900 shadow-2xl transform translate-x-full transition-transform duration-300 overflow-y-auto md:hidden">
        <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-gray-800">
            <img src="{{ asset('images/logo-infoseputar62.png') }}" alt="Logo" class="h-7 w-auto">
            <button id="mobile-close-btn" class="p-2 rounded-xl text-slate-400 hover:bg-slate-100 dark:hover:bg-gray-800 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <nav class="p-4 space-y-1">
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-700 dark:text-gray-200 hover:bg-slate-100 dark:hover:bg-gray-800 transition-all">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Beranda
            </a>
            <div class="pt-2 pb-1 px-4">
                <p class="text-xs font-bold text-slate-400 dark:text-gray-500 uppercase tracking-wider">Kategori</p>
            </div>
            @foreach($navCategories as $cat)
                <a href="{{ route('category.show', $cat->slug) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-700 dark:text-gray-200 hover:bg-slate-100 dark:hover:bg-gray-800 transition-all">
                    <span class="w-2 h-2 rounded-full bg-primary dark:bg-primary-500 flex-shrink-0"></span>
                    {{ $cat->name }}
                </a>
                @foreach($cat->children as $child)
                    <a href="{{ route('category.show', $child->slug) }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm text-slate-500 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-gray-800/50 transition-all ml-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-gray-600 flex-shrink-0"></span>
                        {{ $child->name }}
                    </a>
                @endforeach
            @endforeach
        </nav>
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <!-- Brand -->
                <div class="md:col-span-1">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 mb-4">
                        <img src="{{ asset('images/logo-infoseputar62.png') }}" alt="Info Seputar +62" class="h-9 w-auto">
                    </a>
                    <p class="text-sm text-slate-500 dark:text-gray-400 leading-relaxed">
                        Portal berita terpercaya yang menyajikan informasi terkini seputar Indonesia. Tepat, akurat, dan independen.
                    </p>
                    <div class="flex gap-3 mt-5">
                        <a href="#" class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-gray-800 flex items-center justify-center text-slate-500 dark:text-gray-400 hover:bg-primary dark:hover:bg-primary-500 hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-gray-800 flex items-center justify-center text-slate-500 dark:text-gray-400 hover:bg-primary dark:hover:bg-primary-500 hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.747l7.73-8.835L1.254 2.25H8.08l4.259 5.63L18.244 2.25zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-gray-800 flex items-center justify-center text-slate-500 dark:text-gray-400 hover:bg-green-500 hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Categories -->
                <div>
                    <h4 class="text-sm font-black text-slate-900 dark:text-gray-100 uppercase tracking-wider mb-5">Kategori</h4>
                    <ul class="space-y-2.5">
                        @foreach($navCategories as $cat)
                            <li>
                                <a href="{{ route('category.show', $cat->slug) }}" class="text-sm text-slate-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary-500 transition-colors font-medium">
                                    {{ $cat->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Most Read -->
                <div>
                    <h4 class="text-sm font-black text-slate-900 dark:text-gray-100 uppercase tracking-wider mb-5">Terpopuler</h4>
                    <ol class="space-y-3">
                        @foreach(App\Models\Article::where('status','published')->orderByDesc('views_count')->limit(4)->get() as $i => $pop)
                            <li class="flex gap-2.5 items-start">
                                <span class="text-xl font-black text-slate-200 dark:text-gray-700 leading-none w-5 flex-shrink-0 mt-px">{{ $i + 1 }}</span>
                                <a href="{{ route('article.show', $pop->slug) }}" class="text-sm text-slate-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary-500 transition-colors leading-snug line-clamp-2">{{ $pop->title }}</a>
                            </li>
                        @endforeach
                    </ol>
                </div>

                <!-- About -->
                <div>
                    <h4 class="text-sm font-black text-slate-900 dark:text-gray-100 uppercase tracking-wider mb-5">Informasi</h4>
                    <ul class="space-y-2.5">
                        <li><a href="#" class="text-sm text-slate-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary-500 transition-colors font-medium">Tentang Kami</a></li>
                        <li><a href="#" class="text-sm text-slate-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary-500 transition-colors font-medium">Pedoman Media Siber</a></li>
                        <li><a href="#" class="text-sm text-slate-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary-500 transition-colors font-medium">Kebijakan Privasi</a></li>
                        <li><a href="#" class="text-sm text-slate-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary-500 transition-colors font-medium">Hubungi Kami</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-100 dark:border-gray-800 mt-10 pt-6 flex flex-col sm:flex-row justify-between items-center gap-3">
                <p class="text-xs text-slate-400 dark:text-gray-500">
                    &copy; {{ date('Y') }} Info Seputar +62. Seluruh hak cipta dilindungi.
                </p>
                <p class="text-xs text-slate-400 dark:text-gray-500">
                    Dibuat dengan ❤️ di Indonesia &bull; Tepat, Akurat, Independen
                </p>
            </div>
        </div>
    </footer>

    <!-- Mobile Sidebar JS -->
    <script>
        // Sidebar open/close
        const _btn = document.getElementById('mobile-menu-btn');
        const _sidebar = document.getElementById('mobile-sidebar');
        const _overlay = document.getElementById('mobile-overlay');
        const _closeBtn = document.getElementById('mobile-close-btn');

        function openSidebar() {
            _sidebar.classList.remove('translate-x-full');
            _overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            _sidebar.classList.add('translate-x-full');
            _overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }
        if (_btn) _btn.addEventListener('click', openSidebar);
        if (_closeBtn) _closeBtn.addEventListener('click', closeSidebar);
        if (_overlay) _overlay.addEventListener('click', closeSidebar);

        // Close sidebar on resize to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) closeSidebar();
        });
    </script>

    @stack('scripts')
</body>
</html>
