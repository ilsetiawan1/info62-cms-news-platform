<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-infoseputar62.png') }}">

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

    <!-- Fonts: Inter for an iOS-like clean typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ===== BASE ===== */
        body { font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased; }

        /* ===== SCROLLBAR ===== */
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Menyembunyikan scrollbar di Chrome, Safari, dan Opera */
        .target-scroll::-webkit-scrollbar {
            display: none;
        }
        /* Menyembunyikan scrollbar di IE, Edge, dan Firefox */
        .target-scroll {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }

        /* ===== CATEGORY HOVER DROPDOWN (Pure CSS, no display:none conflict) ===== */
        .desktop-cat-item { position: relative; }
        .desktop-cat-dropdown {
            pointer-events: none;
            max-height: 0;
            overflow: hidden;
        }
        @media (min-width: 1024px) {
            .desktop-cat-dropdown {
                pointer-events: none;
                max-height: none;
                overflow: visible;
                display: block;
                visibility: hidden;
                opacity: 0;
                position: absolute;
                top: 100%;
                left: 50%;
                transform: translateX(-50%) translateY(6px);
                z-index: 200;
                min-width: 200px;
                transition: opacity 0.2s ease, transform 0.2s ease, visibility 0s linear 0.2s;
                padding-top: 8px;
            }
            .desktop-cat-item:hover .desktop-cat-dropdown {
                pointer-events: auto;
                visibility: visible;
                opacity: 1;
                transform: translateX(-50%) translateY(0);
                transition: opacity 0.18s ease, transform 0.18s ease, visibility 0s;
            }
        }

        /* ===== DROPDOWN CARD ===== */
        .dropdown-card {
            background: rgba(255,255,255,0.98);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 8px 30px -8px rgba(0,0,0,0.12);
            padding: 6px;
        }
        .dark .dropdown-card {
            background: rgba(30,41,59,0.98);
            border-color: #334155;
            box-shadow: 0 8px 30px -8px rgba(0,0,0,0.5);
        }
        .dropdown-card a {
            display: flex; align-items: center;
            padding: 9px 14px; border-radius: 10px;
            font-size: 0.8125rem; font-weight: 500; color: #334155;
            transition: background 0.12s, color 0.12s; white-space: nowrap;
        }
        .dropdown-card a:first-child { font-weight: 700; color: #2563eb; }
        .dropdown-card a:hover { background: #f1f5f9; color: #1e293b; }
        .dark .dropdown-card a { color: #cbd5e1; }
        .dark .dropdown-card a:first-child { color: #60a5fa; }
        .dark .dropdown-card a:hover { background: #1e293b; }

        /* ===== READING PROGRESS ===== */
        #reading-progress { position: fixed; top: 0; left: 0; height: 3px;
            background: linear-gradient(90deg, #2563eb, #ef4444); z-index: 9999; transition: width 0.1s ease; width: 0%; }

        /* ===== TICKER ===== */
        .ticker-wrap { width: 100%; overflow: hidden; display: flex; align-items: center; white-space: nowrap; height: 100%; }
        .ticker-move { display: inline-flex; align-items: center; padding-left: 100%; animation: ticker 40s linear infinite; height: 100%; min-width: max-content; }
        .ticker-move:hover { animation-play-state: paused; }
        .ticker-item { display: inline-flex; align-items: center; padding: 0 2.5rem; font-size: 0.8125rem; height: 100%; }
        @keyframes ticker { 0% { transform: translate3d(0,0,0); } 100% { transform: translate3d(-100%,0,0); } }

        /* ===== ARTICLE PROSE ===== */
        .article-content { font-size: 1.0625rem; line-height: 1.85; color: #334155; }
        .dark .article-content { color: #cbd5e1; }
        .article-content p { margin-bottom: 1.5rem; }
        .article-content h2 { font-size: 1.4rem; font-weight: 800; margin: 2.5rem 0 1rem; color: #0f172a; }
        .dark .article-content h2 { color: #f1f5f9; }
        .article-content h3 { font-size: 1.15rem; font-weight: 700; margin: 2rem 0 0.75rem; color: #1e293b; }
        .dark .article-content h3 { color: #e2e8f0; }
        .article-content a { color: #2563eb; border-bottom: 1px solid transparent; transition: border-color 0.15s; }
        .article-content a:hover { border-bottom-color: #2563eb; }
        .dark .article-content a { color: #60a5fa; }
        .article-content img { border-radius: 14px; margin: 2rem auto; width: 100%; }
        .article-content blockquote { border-left: 4px solid #2563eb; padding: 1rem 1.5rem; margin: 2rem 0; font-style: italic; background: #f8fafc; border-radius: 0 12px 12px 0; color: #475569; }
        .dark .article-content blockquote { background: #1e293b; color: #94a3b8; }
        .article-content ul { list-style: disc; padding-left: 1.5rem; margin-bottom: 1.5rem; }
        .article-content ol { list-style: decimal; padding-left: 1.5rem; margin-bottom: 1.5rem; }
        .article-content li { margin-bottom: 0.4rem; }

        /* ===== AD PLACEHOLDER ===== */
        .ad-placeholder { border: 2px dashed #e2e8f0; background: #f8fafc; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            color: #94a3b8; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; }
        /* ===== NEWS CARD HOVER ===== */
        .news-card { transition: transform 0.25s ease; }
        .news-card:hover { transform: translateY(-2px); }

        /* ===== WING ADS ===== */
        .wing-ad {
            display: flex !important;
        }
        @media (max-width: 1535px) {
            .wing-ad {
                display: none !important;
            }
        }
    </style>

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        function toggleDarkMode() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        }
    </script>
    @stack('head')
</head>
<body class="bg-slate-50 dark:bg-[#0f172a] text-slate-800 dark:text-slate-200 transition-colors duration-300">
    <div id="reading-progress"></div>
    <div class="relative w-full">
        {{-- ==================== IKLAN SAYAP KIRI BERTINGKAT (Slot 1 & 2 — Desktop Only) ==================== --}}
        <div class="hidden lg:block absolute top-0 bottom-0 pointer-events-none" style="left: calc(50% - 780px); width: 160px; z-index: 30;">
            <div class="hidden lg:flex wing-ad" style="position: sticky; top: 145px; width: 160px; height: 600px; z-index: 30; flex-direction: column; gap: 16px; pointer-events: auto;">
                {{-- Slot 1: Sayap Kiri Atas (Eksklusif Desktop) --}}
                <div style="width: 100%; height: 380px; border-radius: 0.75rem; overflow: hidden; flex-shrink: 0;">
                    @if(isset($adSlot1) && $adSlot1)
                        <a href="{{ $adSlot1->url ?? '#' }}" target="_blank" rel="noopener" class="shadow-md" style="display: block; width: 100%; height: 100%; border-radius: 0.75rem; overflow: hidden;">
                            <img src="{{ $adSlot1->image_url }}" alt="{{ $adSlot1->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </a>
                    @endif
                </div>

                {{-- Slot 2: Sayap Kiri Bawah (Eksklusif Desktop) --}}
                <div style="width: 100%; height: 204px; border-radius: 0.75rem; overflow: hidden; flex-shrink: 0;">
                    @if(isset($adSlot2) && $adSlot2)
                        <a href="{{ $adSlot2->url ?? '#' }}" target="_blank" rel="noopener" class="shadow-md" style="display: block; width: 100%; height: 100%; border-radius: 0.75rem; overflow: hidden;">
                            <img src="{{ $adSlot2->image_url }}" alt="{{ $adSlot2->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- ==================== IKLAN SAYAP KANAN BERTINGKAT (Slot 3 & 4 — Desktop Only) ==================== --}}
        <div class="hidden lg:block absolute top-0 bottom-0 pointer-events-none" style="right: calc(50% - 780px); width: 160px; z-index: 30;">
            <div class="hidden lg:flex wing-ad" style="position: sticky; top: 145px; width: 160px; height: 600px; z-index: 30; flex-direction: column; gap: 16px; pointer-events: auto;">
                {{-- Slot 3: Sayap Kanan Atas (Eksklusif Desktop) --}}
                <div style="width: 100%; height: 204px; border-radius: 0.75rem; overflow: hidden; flex-shrink: 0;">
                    @if(isset($adSlot3) && $adSlot3)
                        <a href="{{ $adSlot3->url ?? '#' }}" target="_blank" rel="noopener" class="shadow-md" style="display: block; width: 100%; height: 100%; border-radius: 0.75rem; overflow: hidden;">
                            <img src="{{ $adSlot3->image_url }}" alt="{{ $adSlot3->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </a>
                    @endif
                </div>

                {{-- Slot 4: Sayap Kanan Bawah (Eksklusif Desktop) --}}
                <div style="width: 100%; height: 380px; border-radius: 0.75rem; overflow: hidden; flex-shrink: 0;">
                    @if(isset($adSlot4) && $adSlot4)
                        <a href="{{ $adSlot4->url ?? '#' }}" target="_blank" rel="noopener" class="shadow-md" style="display: block; width: 100%; height: 100%; border-radius: 0.75rem; overflow: hidden;">
                            <img src="{{ $adSlot4->image_url }}" alt="{{ $adSlot4->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- ===== STICKY FROSTED HEADER ===== -->
        <header class="sticky top-0 z-[100] bg-white/90 dark:bg-[#0f172a]/90 backdrop-blur-xl border-b border-slate-200/70 dark:border-slate-700/50 transition-all shadow-sm dark:shadow-slate-900/50">

            <!-- Top row: Logo & Utilities -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 md:h-20 gap-4">

                    <!-- Hamburger (Mobile) -->
                    <button id="mobile-menu-btn" class="flex lg:hidden p-2 rounded-full text-slate-600 hover:bg-slate-100 dark:text-zinc-400 dark:hover:bg-zinc-800 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>

                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="flex items-center gap-2 flex-shrink-0 lg:mr-8">
                        <img src="{{ asset('images/logo-infoseputar62.png') }}"  alt="Info Seputar +62" class="h-8 md:h-10 w-auto rounded-xl">
                    </a>

                    <!-- Desktop Ticker (if exists) -->
                    @if(isset($tickerNews) && $tickerNews->isNotEmpty())
                    <div class="hidden lg:flex items-center flex-1 mx-4 h-10 bg-slate-100/50 dark:bg-zinc-800/50 rounded-full border border-slate-200/50 dark:border-zinc-700/50 overflow-hidden">
                        <div class="flex items-center px-4 bg-white dark:bg-zinc-800 h-full border-r border-slate-200 dark:border-zinc-700 shadow-sm z-10 flex-shrink-0">
                            <span class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-red-600 dark:text-red-500">
                                <span class="relative flex h-2 w-2">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-red-600"></span>
                                </span>
                                LIVE NEWS
                            </span>
                        </div>
                        <div class="ticker-wrap flex-1 h-full">
                            <div class="ticker-move flex items-center h-full">
                                @foreach($tickerNews as $ticker)
                                    <div class="ticker-item h-full flex items-center">
                                        <a href="{{ route('article.show', $ticker->slug) }}" class="font-medium text-slate-600 dark:text-zinc-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                            {{ $ticker->title }}
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="hidden lg:block flex-1"></div>
                    @endif

                    <!-- Right Actions -->
                    <div class="flex items-center gap-2 sm:gap-3 ml-auto">
                        <!-- Desktop Search -->
                        <form action="{{ route('search') }}" method="GET" class="hidden md:flex items-center bg-slate-100 dark:bg-zinc-800 rounded-full px-4 py-2 focus-within:ring-2 focus-within:ring-blue-500/20 transition-all border border-transparent dark:border-zinc-700">
                            <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <input type="text" name="q" placeholder="Cari berita..." value="{{ request('q') }}" required class="bg-transparent border-none outline-none text-sm text-slate-700 dark:text-zinc-200 placeholder-slate-400 w-40 focus:w-56 transition-all duration-300 p-0 ml-2 focus:ring-0">
                        </form>

                        <!-- Dark Mode Toggle -->
                        <button onclick="toggleDarkMode()" class="p-2.5 rounded-full text-slate-600 hover:bg-slate-100 dark:text-zinc-400 dark:hover:bg-zinc-800 transition-colors" aria-label="Toggle dark mode">
                            <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Bottom row: Categories (Horizontal scroll on desktop, Dropdown select on mobile) -->
            <div class="border-t border-slate-100 dark:border-slate-800/50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Desktop & Tablet Category Navigation -->
                    <nav class="hidden md:flex items-center justify-between py-2.5 relative z-[100] w-full">
                        <div class="flex items-center gap-1 flex-wrap lg:flex-nowrap">
                            <a href="{{ route('home') }}" class="flex flex-shrink-0 items-center justify-center text-center px-4 h-8 rounded-full text-[13px] font-bold tracking-wide transition-all whitespace-nowrap {{ request()->routeIs('home') && !request('cat') ? 'bg-slate-900 text-white dark:bg-sky-500 dark:text-white shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">Semua</a>

                            @foreach($navCategories as $index => $cat)
                                <div class="desktop-cat-item flex-shrink-0
                                    @if($index >= 9) hidden
                                    @elseif($index >= 7) hidden xl:block
                                    @elseif($index >= 4) hidden lg:block
                                    @endif">
                                    <a href="{{ route('category.show', $cat->slug) }}"
                                       class="flex items-center justify-center text-center px-4 h-8 rounded-full text-[13px] font-semibold tracking-wide transition-all whitespace-nowrap
                                              {{ request()->is('kategori/'.$cat->slug.'*') ? 'bg-slate-900 text-white dark:bg-sky-500 dark:text-white shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                                        {{ $cat->name }}
                                    </a>

                                    @if($cat->children->isNotEmpty())
                                    <div class="desktop-cat-dropdown">
                                        <div class="dropdown-card">
                                            <a href="{{ route('category.show', $cat->slug) }}">Semua {{ $cat->name }}</a>
                                            @foreach($cat->children as $child)
                                                <a href="{{ route('category.show', $child->slug) }}">{{ $child->name }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Dropdown Menu for Overflow Categories -->
                        @if($navCategories->count() > 4)
                            <div class="relative flex-shrink-0" x-data="{ openMore: false }">
                                <button @click="openMore = !openMore" @click.away="openMore = false"
                                        class="flex items-center justify-center w-8 h-8 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all select-none"
                                        aria-label="More categories">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                    </svg>
                                </button>

                                <div x-show="openMore"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-56 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl overflow-hidden divide-y divide-slate-100 dark:divide-slate-700/50 z-[120]"
                                     style="display: none;">
                                    @foreach($navCategories as $index => $cat)
                                        @if($index >= 4)
                                            <div class="px-1 py-1 
                                                @if($index >= 9) block
                                                @elseif($index >= 7) xl:hidden block
                                                @elseif($index >= 4) lg:hidden block
                                                @endif">
                                                <a href="{{ route('category.show', $cat->slug) }}" class="block px-3 py-1.5 text-xs font-bold text-slate-800 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 rounded-lg">
                                                    {{ $cat->name }}
                                                </a>
                                                @if($cat->children->isNotEmpty())
                                                    <div class="pl-3 pb-1 space-y-0.5">
                                                        @foreach($cat->children as $child)
                                                            <a href="{{ route('category.show', $child->slug) }}" class="block px-3 py-1 text-[11px] font-semibold text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-sky-400">
                                                                ↳ {{ $child->name }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </nav>

                    <!-- Mobile Category Navigation (Dropdown Select Menu) -->
                    <div class="md:hidden py-2" x-data="{ openCatDropdown: false }">
                        <div class="flex items-center justify-between gap-2 relative">
                            <a href="{{ route('home') }}" class="px-4 py-1.5 rounded-full text-[13px] font-bold tracking-wide transition-all whitespace-nowrap {{ request()->routeIs('home') && !request('cat') ? 'bg-slate-900 text-white dark:bg-sky-500 dark:text-white shadow-sm' : 'text-slate-500 dark:text-slate-400 bg-slate-100/50 dark:bg-slate-800/40' }}">Semua</a>
                            
                            @php
                                $activeCatName = 'Pilih Kategori';
                                foreach ($navCategories as $cat) {
                                    if (request()->is('kategori/'.$cat->slug.'*')) {
                                        $activeCatName = $cat->name;
                                        break;
                                    }
                                }
                            @endphp

                            <button @click="openCatDropdown = !openCatDropdown" class="flex items-center gap-1.5 px-4 py-1.5 rounded-full text-[13px] font-bold bg-slate-950 text-white dark:bg-sky-500 transition-all select-none">
                                <span>{{ $activeCatName }}</span>
                                <svg class="w-4 h-4 transition-transform duration-300" :class="openCatDropdown ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                        </div>

                        <!-- Dropdown list -->
                        <div x-show="openCatDropdown" 
                             @click.away="openCatDropdown = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute left-4 right-4 mt-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-xl overflow-hidden divide-y divide-slate-100 dark:divide-slate-700/50 z-[110]"
                             style="display: none;">
                            @foreach($navCategories as $cat)
                                <div class="px-2 py-1 bg-white dark:bg-slate-800">
                                    <a href="{{ route('category.show', $cat->slug) }}" class="block px-4 py-2 text-sm font-bold text-slate-800 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 rounded-xl">
                                        {{ $cat->name }}
                                    </a>
                                    @if($cat->children->isNotEmpty())
                                        <div class="pl-4 pb-1 space-y-1">
                                            @foreach($cat->children as $child)
                                                <a href="{{ route('category.show', $child->slug) }}" class="block px-4 py-1.5 text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-sky-400">
                                                    ↳ {{ $child->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- ===== MOBILE OFFCANVAS MENU ===== -->
        <div id="mobile-overlay" class="fixed inset-0 z-[998] bg-slate-900/40 backdrop-blur-sm hidden lg:hidden transition-opacity opacity-0" onclick="closeSidebar()"></div>
        <div id="mobile-sidebar" class="fixed inset-y-0 left-0 z-[999] w-80 bg-white dark:bg-[#0f172a] shadow-2xl transform -translate-x-full transition-transform duration-300 overflow-y-auto lg:hidden">
            <div class="flex items-center justify-between p-5 sticky top-0 bg-white/90 dark:bg-[#09090b]/90 backdrop-blur-md z-10 border-b border-slate-100 dark:border-zinc-800">
                <img src="{{ asset('images/logo-infoseputar62.png') }}" alt="Logo" class="h-8 w-auto rounded-md">
                <button id="mobile-close-btn" class="p-2 rounded-full text-slate-500 hover:bg-slate-100 dark:text-zinc-400 dark:hover:bg-zinc-800 transition-colors" onclick="closeSidebar()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-4 border-b border-slate-100 dark:border-zinc-800">
                <form action="{{ route('search') }}" method="GET" class="flex items-center bg-slate-100 dark:bg-zinc-800/50 rounded-2xl px-4 py-3 gap-3 border border-transparent dark:border-zinc-700">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" name="q" placeholder="Cari..." value="{{ request('q') }}" required class="bg-transparent border-none outline-none text-sm text-slate-700 dark:text-zinc-200 placeholder-slate-400 w-full p-0 focus:ring-0">
                </form>
            </div>

            <nav class="p-4 space-y-2">
                <div class="pb-2 px-2">
                    <p class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-widest">Menu Utama</p>
                </div>
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : 'text-slate-700 dark:text-zinc-200 hover:bg-slate-50 dark:hover:bg-zinc-800/50' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Beranda
                </a>

                <div class="pt-4 pb-2 px-2">
                    <p class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-widest">Kategori</p>
                </div>

                <div x-data="{ expandedCat: null }" class="space-y-2">
                    @foreach($navCategories as $cat)
                        <div class="rounded-2xl overflow-hidden {{ request()->is('kategori/'.$cat->slug.'*') ? 'bg-slate-50 dark:bg-zinc-800/30 border border-slate-100 dark:border-zinc-800' : 'border border-transparent' }} transition-colors">
                            <div class="flex items-center justify-between px-2">
                                <a href="{{ route('category.show', $cat->slug) }}" class="flex-1 px-2 py-3 text-sm font-bold {{ request()->is('kategori/'.$cat->slug) ? 'text-blue-600 dark:text-blue-400' : 'text-slate-700 dark:text-zinc-200' }}">
                                    {{ $cat->name }}
                                </a>

                                @if($cat->children->isNotEmpty())
                                    <button @click="expandedCat = expandedCat === {{ $cat->id }} ? null : {{ $cat->id }}" class="p-3 text-slate-400 hover:text-blue-600 transition-colors">
                                        <svg class="w-4 h-4 transition-transform duration-300" :class="expandedCat === {{ $cat->id }} ? 'rotate-180 text-blue-600' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                @endif
                            </div>

                            @if($cat->children->isNotEmpty())
                                <div x-show="expandedCat === {{ $cat->id }}" x-collapse class="px-3 pb-3 space-y-1" style="display: none;">
                                    @foreach($cat->children as $child)
                                        <a href="{{ route('category.show', $child->slug) }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm {{ request()->is('kategori/'.$child->slug) ? 'text-blue-600 font-bold bg-blue-50 dark:bg-blue-900/20 dark:text-blue-400' : 'text-slate-500 font-medium dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800' }} transition-colors">
                                            {{ $child->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </nav>
        </div>

        <!-- ===== HEADER ADVERTISEMENT ===== -->
        @if(isset($adHeader) && $adHeader)
            <div class="lg:hidden max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="w-full flex justify-center">
                    <a href="{{ $adHeader->url ?? '#' }}" target="_blank" rel="noopener" class="block w-full max-w-[728px] h-[90px] sm:h-[120px] overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-800/40 p-1 shadow-sm">
                        <img src="{{ $adHeader->image_url }}" alt="{{ $adHeader->title }}" class="w-full h-full object-contain mx-auto">
                    </a>
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <main class="min-h-screen">
            @yield('content')
        </main>
    </div>



    <!-- ===== FOOTER ===== -->
    <footer class="relative z-50 bg-white dark:bg-[#0c1626] border-t border-slate-200 dark:border-slate-800 mt-16 text-slate-600 dark:text-slate-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 lg:gap-16">
                <!-- Brand Info -->
                <div class="md:col-span-12 lg:col-span-5">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 mb-6">
                        <img src="{{ asset('images/logo-infoseputar62.png') }}" alt="Info Seputar +62" class="h-10 rounded-md w-auto dark:brightness-0 dark:invert">
                    </a>
                    <p class="text-sm leading-relaxed max-w-md mb-8">
                        Portal berita digital terdepan di Indonesia yang menyajikan informasi terkini, akurat, dan mendalam dengan antarmuka yang mengutamakan kenyamanan pembaca.
                    </p>

                    <div class="flex gap-3">
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center text-slate-500 dark:text-zinc-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center text-slate-500 dark:text-zinc-400 hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-black transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.747l7.73-8.835L1.254 2.25H8.08l4.259 5.63L18.244 2.25zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center text-slate-500 dark:text-zinc-400 hover:bg-gradient-to-tr hover:from-yellow-400 hover:via-pink-500 hover:to-purple-500 hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Footer Links -->
                <div class="md:col-span-4 lg:col-span-2">
                    <h4 class="text-[11px] font-black text-slate-900 dark:text-zinc-100 uppercase tracking-widest mb-5">Kategori</h4>
                    <ul class="space-y-3">
                        @foreach(collect($navCategories)->take(5) as $cat)
                            <li><a href="{{ route('category.show', $cat->slug) }}" class="text-sm hover:text-blue-600 dark:hover:text-blue-400 transition-colors">{{ $cat->name }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Footer Links -->
                <div class="md:col-span-4 lg:col-span-2">
                    <h4 class="text-[11px] font-black text-slate-900 dark:text-zinc-100 uppercase tracking-widest mb-5">Jaringan</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('jaringan.show', 'yogyakarta') }}" class="text-sm hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Info Seputar Yogyakarta</a></li>
                        <li><a href="{{ route('jaringan.show', 'football') }}" class="text-sm hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Info Seputar Football</a></li>
                        <li><a href="{{ route('jaringan.show', 'fm') }}" class="text-sm hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Info Seputar FM</a></li>
                        <li><a href="{{ route('jaringan.show', 'otomotif') }}" class="text-sm hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Info Seputar Otomotif</a></li>
                        <li><a href="{{ route('jaringan.show', 'kuliner') }}" class="text-sm hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Info Seputar Kuliner</a></li>
                    </ul>
                </div>

                <!-- Corporate -->
                <div class="md:col-span-4 lg:col-span-3">
                    <h4 class="text-[11px] font-black text-slate-900 dark:text-zinc-100 uppercase tracking-widest mb-5">Korporat</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('page.show', 'tentang-kami') }}" class="text-sm hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Tentang Kami</a></li>
                        <li><a href="{{ route('page.show', 'pedoman-media-siber') }}" class="text-sm hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Pedoman Media Siber</a></li>
                        <li><a href="{{ route('page.show', 'kebijakan-privasi') }}" class="text-sm hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Kebijakan Privasi</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="bg-slate-50 dark:bg-[#09090b] border-t border-slate-200 dark:border-zinc-800 py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-center items-center gap-4">
                <p class="text-xs font-medium">
                    &copy; {{ date('Y') }} Info Seputar +62. Seluruh hak cipta dilindungi.
                </p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Reading Progress
        window.addEventListener('scroll', () => {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            const progressBar = document.getElementById("reading-progress");
            if(progressBar) progressBar.style.width = scrolled + "%";
        });

        // Sidebar Handling
        const overlay = document.getElementById('mobile-overlay');
        const sidebar = document.getElementById('mobile-sidebar');
        let sidebarOpen = false;

        function openSidebar() {
            sidebarOpen = true;
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            // Allow display block to apply before transitioning opacity
            setTimeout(() => overlay.classList.remove('opacity-0'), 10);
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebarOpen = false;
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('opacity-0');
            setTimeout(() => overlay.classList.add('hidden'), 300); // match duration
            document.body.style.overflow = '';
        }

        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('mobile-menu-btn');
            if(btn) btn.addEventListener('click', openSidebar);

            // Real-Time Clock & Date in Indonesian
            function updateClock() {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                
                const clockEl = document.getElementById('local-clock');
                if (clockEl) {
                    clockEl.textContent = `${hours}:${minutes}:${seconds}`;
                }
                
                const dateEl = document.getElementById('local-date');
                if (dateEl) {
                    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    
                    const dayName = days[now.getDay()];
                    const dayNum = now.getDate();
                    const monthName = months[now.getMonth()];
                    const year = now.getFullYear();
                    
                    dateEl.textContent = `${dayName}, ${dayNum} ${monthName} ${year}`;
                }
            }
            setInterval(updateClock, 1000);
            updateClock();
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024 && sidebarOpen) closeSidebar();
        });
    </script>
    @stack('scripts')
</body>
</html>
